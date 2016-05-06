<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Catalog\Model;

class CostApi extends \RS\Module\AbstractModel\EntityList
{    
    protected static
        $session_old_cost_id,
        $session_default_cost_id,
        $instance,
        $full_costlist;
    
    function __construct()
    {
        parent::__construct(new \Catalog\Model\Orm\Typecost,
        array(
            'multisite' => true,
            'nameField' => 'title',
            'defaultOrder' => 'id'
        ));
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
    * Аналог getSelectList, только для статичского вызова
    * 
    */
    static function staticSelectList($prepend_no_select = false)
    {
        $no_select = $prepend_no_select ? array(0 => 'Не выбрано') : array();
        return $no_select + parent::staticSelectList();
    }    
        
    /**
    * Возвращает подготовленный для отображения в таблице массив данных
    */
    function getTableList($page = 0, $size = 0)
    {
        $config = \RS\Config\Loader::byModule($this);
        
        $list = $this->getList($page, $size);
        
        foreach($list as $item) {
            if ($item['type'] == 'manual') {
                $item['_type_text'] = 'Задается вручную';
            } else {
                $val_type = ($item['val_type'] == 'sum') ? ' руб' : '%';
                $item['_type_text'] = "Вычисляется автоматически: <strong>{$item['val_znak']}{$item['val']}{$val_type}</strong> от {$item->getDependCost()->title}";
            }
            
            if ($item['id'] == $config['default_cost']) {
                $item['title'] = "<strong>{$item['title']}</strong> (по умолчанию)";
                
            }
        }
        return $list;
    }
    
    /**
    * Возвращает список цен. Из списка исключаются автовычесляемые цены и редактируемая сейчас.
    */
    public static function getEditList()
    {
        $obj = self::getInstance();
        
        //Убираем самого себя из списка
        $cur_id = $obj->getElement()->offsetGet('id');
        if ($cur_id >0) {
            $obj->setFilter('id', $cur_id, '!=');
        }
        //Отображаем только не автоматически вычесляемые типы цен
        $obj->setFilter('type', 'manual');
        
        return $obj->getSelectList();
    }
    
    /**
    * Возвращает список цен для селектора в меню пользователи
    */
    public static function getUserSelectList($showDefault = false)
    {
        
        $sites = \RS\Orm\Request::make()
                    ->from(new \Site\Model\Orm\Site())
                    ->orderby('title ASC')
                    ->objects();
        
        foreach ($sites as $k=>$site){
            $prices = \RS\Orm\Request::make()
                         ->from(new \Catalog\Model\Orm\Typecost())
                         ->where(array(
                            'site_id' => $site['id'] 
                         ))
                         ->orderby('title ASC')
                         ->objects();
                                       
            
            $prices = array(-1 => array(
                      'id'    => 0,
                      'title' => 'По умолчанию'
                    )) + $prices;
            $sites[$k]['prices'] = $prices;
        }
        
        
 
        return $sites;
    }
    
    /**
    * Заполняет массив с полями цен и сайтов, значениями цен установленными для данного пользователя
    * 
    * @param \Users\Model\Orm\User $user
    * @param array $sites
    */
    public static function fillUsersPriceList(\Users\Model\Orm\User $user,array $sites){
        if (is_numeric($user['cost_id'])) {
            //Для совместимости с предыдущей версией
            $cost_array = array(\RS\Site\Manager::getSiteId() => $user['cost_id']);
        } else {
            $cost_array = @unserialize($user['cost_id']); //Массив текущими ценами пользователя 
        }
        
       
        if (!empty($sites) && $cost_array){
           foreach ($sites as $key=>$site){ //Походимся по сайтам
               
                if (!empty($site['prices'])){
                    foreach($site['prices'] as $keyp=>$price){ //Походимся по ценам
                        if ($cost_array[$site['id']]==$price['id']){
                          $sites[$key]['prices'][$keyp]['selected'] = true;
                        }
                    }
                }
                
            } 
        }
        
        
        return $sites; 
    }
    
    /**
    * Возвращает расчитанные "автоматические" цены на основе "заданных вручную"
    * 
    * @param array $cost_values
    */
    function getCalculatedCostList($cost_values)
    {
        if (!isset(self::$full_costlist)) $this->getAllCategory();
        
        //Устанавливаем вычисляемые значения.
        $cost_val = array();
        foreach(self::$full_costlist as $onecost) {
            if ($onecost['type'] == 'auto') {
                $source_val = isset($cost_values[$onecost['depend']]) ? $cost_values[$onecost['depend']] : 0;
            } else {
                $source_val = isset($cost_values[$onecost['id']]) ? $cost_values[$onecost['id']] : 0;
            }
            
            $cost_val[$onecost['id']]  = $this->calculateAutoCost($source_val, $onecost);
        }
        return $cost_val;
    }
    
    /**
    * Высчитывает автоматическую цену из исходной
    * 
    * @param double $source_cost исходная цена
    * @param array $auto_cost_arr массив со строкой автоматической цены
    * @return double возвращает высчитанную цену
    */
    function calculateAutoCost($source_cost, $cost_arr)
    {
        if ($cost_arr['type'] == 'auto') {
            if ($cost_arr['val_type'] == 'sum') {
                $dst_val = (float)$cost_arr['val_znak'].$cost_arr['val'];
            } else {
                $dst_val = $source_cost * ($cost_arr['val_znak'].($cost_arr['val']/100));
            }
            $source_cost = $source_cost + $dst_val;
            
            $source_cost = $cost_arr->getRounded($source_cost); //Округление
            $source_cost = number_format($source_cost, 2, '.', '');
        }
        return $source_cost;
    }
    
    
    /**
    * Устанавливает тип цены по умолчанию (если ни одна из групп пользователя не сопоставлена с другой ценой)
    */
    function setDefaultCost($cost_id)
    {
        $config = \RS\Config\Loader::byModule($this);
        $config['default_cost'] = $cost_id;
        $config->update();
    }
    
    function getAllCategory()
    {
        $this->clearFilter();
        self::$full_costlist = $this->getAssocList('id');
    }
    
    /**
    * Возвращает id типа цен, от которой зависит цена $type_id.
    * 
    * @param int $type_id
    */
    function getManualType($type_id)
    {
        if (!isset(self::$full_costlist)) $this->getAllCategory();
        return (self::$full_costlist[$type_id]['type'] == 'manual') ? $type_id : self::$full_costlist[$type_id]['depend'];
    }
    
    /**
    * Возвращает массив с минимальной и максимальной ценой товаров
    * 
    * @param \RS\Orm\Request $minmax_query исходный запрос на выборку товаров
    * @return array
    */
    function getMinMaxCost(\RS\Orm\Request $minmax_query)
    {
        $current_cost_type = $this->getUserCost(); //Текущий тип цен
        $manual_cost_type = $this->getManualType($current_cost_type);
        $minmax_query->leftjoin(new \Catalog\Model\Orm\Xcost(), "A.id = XC.product_id AND XC.cost_id='{$manual_cost_type}'", 'XC');
        $minmax_query->orderby(null)->limit(null);
        $minmax_query->select = 'max(XC.cost_val) interval_to, min(XC.cost_val) interval_from';
        $money_array = $minmax_query->exec()->fetchRow() ?: array('interval_from' => 0, 'interval_to' => 0);
        
        $current_cost = self::$full_costlist[$current_cost_type];
        if ($current_cost['type'] == 'auto') {
            $money_array['interval_from'] = $this->calculateAutoCost($money_array['interval_from'], $current_cost);
            $money_array['interval_to'] = $this->calculateAutoCost($money_array['interval_to'], $current_cost);
        }
        return $money_array;
    }
    
    /**
    * Возвращает исходную цену из выщитанной автоматически для текущего Пользователя
    * 
    * @param mixed $value Сумма
    */
    function correctCost($value)
    {
        if (!isset(self::$full_costlist)) $this->getAllCategory();        
        $cost_id = self::getUserCost();
        $cost = self::$full_costlist[$cost_id];
        
        if ($cost['type'] == 'auto') {
            $dst_val = 0;
            if ($cost['val_type'] == 'sum') {
                //Если у цены установлено прибавить 300, значит здесь мы должы отнять 300
                $dst_val = (float)$cost['val_znak'].$cost['val'];
                $value  = $value - $dst_val;                
            } else {
                //Если у цены установлено прибавить 30%, значит здесь мы должы отнять 30%
                $value  = $value / abs(($cost['val_znak'].'1')+($cost['val']/100));
            }
           $value  = number_format( $value, 2, '.', '' );
        }
        return $value;
    }
    
    /**
    * Возвращает персональную цену пользователя, если таковая установлена, иначе цену по-умолчанию(из настроект модуля Каталог)
    * 
    * @return integer - ID цены
    */
    public static function getUserCost(\Users\Model\Orm\User $user = null)
    {
        if ($user === null) {
            $user = \RS\Application\Auth::getCurrentUser();
        }

        $site_id   = \RS\Site\Manager::getSiteId();      //Текущий сайт
        
        //Для совместимости с предыдущей версией
        $user_cost = is_numeric($user['cost_id']) ? array($site_id => $user['cost_id']) : @unserialize($user['cost_id']);
        
        if ($user && !empty($user_cost[$site_id])) {
            return $user_cost[$site_id];
        }      
        return self::getDefaultCostId();
    }

    /**
    * Устанавливает тип цен по-умолчанию для одной сессии выполнения скрипта
    *         
    * @param integer $cost_id
    */
    public static function setSessionDefaultCost($cost_id)
    {
        self::$session_default_cost_id = $cost_id;
    }
    
    /**
    * Возвращает ID типа цен
    * 
    * @return integer
    */
    public static function getDefaultCostId()
    {
        if (!self::$session_default_cost_id) {
            self::$session_default_cost_id = \RS\Config\Loader::byModule('catalog')->default_cost;
        }
        return self::$session_default_cost_id;
    }
    
    /**
    * Возвращает id Старой цены или false, в случае, если такой цены - нет
    * 
    * @return integer | bool(false)
    */
    public static function getOldCostId()
    {
        if (!self::$session_old_cost_id) {
             $old_cost_id  = \RS\Config\Loader::byModule('catalog')->old_cost;
             if (!$old_cost_id) {
                 
                 //Для совместимости со старыми версиями
                 $old_cost_type = orm\Typecost::loadByWhere(array(
                    'site_id' => \RS\Site\Manager::getSiteId(),
                    'title' => t('Зачеркнутая цена')
                 ));
                 
                 $old_cost_id = $old_cost_type['id'];
             }
             self::$session_old_cost_id = $old_cost_id;
        }
        return self::$session_old_cost_id;
    }
    
    /**
    * Устанавливает тип цен по-умолчанию для одной сессии выполнения скрипта
    *         
    * @param integer $cost_id
    */
    public static function setSessionOldCost($cost_id)
    {
        self::$session_old_cost_id = $cost_id;
    }    
    
    /**
    * Пересчитывает цены всех товаров сайта с учетом курсов валют
    * 
    * @param integer | null $site_id - id сайта, на котором необходимо пересчитать цены. Если Null, то на текущем
    * @return void
    */
    public static function recalculateCosts($site_id = null)
    {
        if (!$site_id) {
            $site_id = \RS\Site\Manager::getSiteId();
        }
        $xcost = new Orm\Xcost();
        $q = \RS\Orm\Request::make()
            ->select('X.*')
            ->from(new Orm\Product(), 'P')
            ->join($xcost, 'P.id = X.product_id', 'X')
            ->where(array(
                'P.site_id' => $site_id,
            ))
            ->where('X.cost_original_currency>0');
        
        $offset = 0;            
        $pagesize = 200;
        $res = $q->limit($offset, $pagesize)->exec();
        while( $res->rowCount() ) {
            $values = array();
            while($row = $res->fetchRow()) {
                $curr = Orm\Currency::loadSingle($row['cost_original_currency']);
                $cost_val = $curr['id'] ? \Catalog\Model\CurrencyApi::convertToBase($row['cost_original_val'], $curr) : $row['cost_original_val']; 
                $values[] = "({$row['product_id']},{$row['cost_id']},'{$cost_val}')";
            }
            $sql = "INSERT INTO ".$xcost->_getTable()."(product_id, cost_id, cost_val) VALUES".implode(',', $values).
                    " ON DUPLICATE KEY UPDATE cost_val = VALUES(cost_val)";
            \RS\Db\Adapter::sqlExec($sql);
            
            $offset += $pagesize;
            $res = $q->limit($offset, $pagesize)->exec();
        }
        
        //Обновляем цены в комплектациях.
        $offer_api = new OfferApi();
        $offset = 0;
        $pageSize = 200;
        $q = \RS\Orm\Request::make()
            ->from(new Orm\Offer())
            ->where(array('site_id' => $site_id))
            ->limit($offset, $pagesize);
            
        while($list = $q->objects()) {
            foreach($list as $offer) {
                $offer->update();
            }
            $offset += $pageSize;
            $q->offset($offset);
        }
    }
}