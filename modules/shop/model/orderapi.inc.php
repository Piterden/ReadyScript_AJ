<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Model;
use \Shop\Model\UserStatusApi,
    \Shop\Model\Orm\UserStatus;

/**
* API для работы с заказами
*/
class OrderApi extends \RS\Module\AbstractModel\EntityList
{
    const
        ORDER_FILTER_ALL = 'all',
        ORDER_FILTER_SUCCESS = 'success',
        
        ORDER_SHOW_TYPE_NUM = 'num',
        ORDER_SHOW_TYPE_SUMM = 'summ';
        
    
    function __construct()
    {
        parent::__construct(new \Shop\Model\Orm\Order,
        array(
            'multisite' => true,
            'defaultOrder' => 'id DESC'
        ));
    }
    
    /**
    * Возвращает статистику по заказам
    * 
    * @return array
    */
    function getStatistic()
    {
        $query = \RS\Orm\Request::make()
            ->select('COUNT(*) cnt')
            ->from($this->obj_instance);
        
        $queries = array();
        $queries['total_orders'] = $query;
        $queries['open_orders'] = clone $query->where("status != '#status'", array(
            'status' => UserStatusApi::getIdByType(UserStatus::STATUS_NEW)
        ));
        $queries['closed_orders'] = clone $query->where(array(
            'status' => UserStatusApi::getIdByType(UserStatus::STATUS_NEW) 
        ));
        $queries['last_order_date'] = \RS\Orm\Request::make()
            ->select('dateof cnt')
            ->from($this->obj_instance)
            ->where(array('site_id' => \RS\Site\Manager::getSiteId()))
            ->orderby('dateof DESC')->limit(1);
        
        foreach($queries as &$one) {
            $one = $one->exec()->getOneField('cnt', 0);
        }
        return $queries;
    }
    
    /**
    * Возвращает даты заказов, сгруппированные по годам. Для видета статистики
    * 
    * @param string $order_filter фильтр заказов. Если all - то все заказы, success - только завершенные
    * @param mixed $lastrange
    * @param bool $cache - флаг кэширования, если true, то кэш будет использоваться
    * @return array
    */
    function ordersByYears($order_filter = self::ORDER_FILTER_ALL, $show_type = self::ORDER_SHOW_TYPE_NUM, $lastrange = 5, $cache = true)
    {
        $site_id = \RS\Site\Manager::getSiteId();
        
        if ($cache) {
            $result = \RS\Cache\Manager::obj()
                ->request(array($this, 'ordersByYears'), $order_filter, $show_type, $lastrange, false, $site_id);
        } else {
            $q = \RS\Orm\Request::make()
                ->select('dateof, COUNT(*) cnt, SUM(totalcost) total_cost')
                ->from($this->obj_instance)
                ->where('dateof >= NOW()-INTERVAL #lastrange YEAR', array('lastrange' => $lastrange))
                ->where(array('site_id' => $site_id))
                ->groupby('YEAR(dateof), MONTH(dateof)')
                ->orderby('dateof');
            
            if ($order_filter == self::ORDER_FILTER_SUCCESS) {
                $status_id = UserStatusApi::getStatusIdByType('success');
                $q->where(array('status' => $status_id));
            }
            
            $res = $q->exec();
            $result = array();
            while($row = $res->fetchRow()) {
                $date = strtotime($row['dateof']);
                $year = date('Y', $date);
                $result[$year]['label'] = $year;
                $result[$year]['data'][date('n', $date)-1] = array(
                    'x' => mktime(4,0,0, date('n', $date), 1)*1000,
                    'y' => $show_type == self::ORDER_SHOW_TYPE_NUM ? $row['cnt'] : $row['total_cost'],
                    'pointDate' => $date*1000,
                    'total_cost' => $row['total_cost'],
                    'count' => $row['cnt']
                );
            }
            
            //Добавляем нулевые месяцы
            foreach($result as $year=>$val) {
                $month_list = array();
                for($month=1; $month<=12; $month++) {
                    $month_list[$month-1] = isset($result[$year]['data'][$month-1])? $result[$year]['data'][$month-1] : array(
                        'x' => mktime(4,0,0, $month, 1)*1000,
                        'y' => 0,
                        'pointDate' => mktime(4,0,0, $month, 1, $year)*1000,
                        'total_cost' => 0,
                        'count' => 0
                    );
                }
                $result[$year]['data'] = $month_list;
            }

        }
        return $result;
    }
    
    /**
    * Возвращает даты заказов, сгруппированные по годам. Для видета статистики
    * 
    * @param mixed $lastrange
    * @param string $order_filter фильтр заказов. Если all - то все заказы, success - только завершенные
    * @param bool $cache - флаг кэширования, если true, то кэш будет использоваться
    * @return array
    */
    function ordersByMonth($order_filter = self::ORDER_FILTER_ALL, $show_type = self::ORDER_SHOW_TYPE_NUM, $lastrange = 1, $cache = true)
    {
        $site_id = \RS\Site\Manager::getSiteId();
        
        if ($cache) {
            $result = \RS\Cache\Manager::obj()
                ->request(array($this, 'ordersByMonth'), $order_filter, $show_type, $lastrange, false, $site_id);
        } else {
            $currency = \Catalog\Model\CurrencyApi::getBaseCurrency()->stitle;
            $start_time = strtotime('-1 month');
            
            $q = \RS\Orm\Request::make()
                ->select('dateof, COUNT(*) cnt, SUM(totalcost) total_cost')
                ->from($this->obj_instance)
                ->where("dateof >= '#starttime'", array('starttime' => date('Y-m-d', $start_time)))
                ->where(array('site_id' => $site_id))
                ->groupby('DATE(dateof)')
                ->orderby('dateof');
            
            if ($order_filter == self::ORDER_FILTER_SUCCESS) {
                $status_id = UserStatusApi::getStatusIdByType('success');
                $q->where(array('status' => $status_id));
            }
            
            $res = $q->exec();
            $min_date = null;
            $max_date = null;
            $result = array();
            while($row = $res->fetchRow()) {
                $date = strtotime($row['dateof']);
                if ($min_date === null || $date<$min_date) {
                    $min_date = $date;
                }
                if ($max_date === null || $date>$max_date) {
                    $max_date = $date;
                }
                $ymd = date('Ymd', $date);
                $result[0][$ymd] = array(
                    'x' => $date*1000,
                    'y' => $show_type == self::ORDER_SHOW_TYPE_NUM ? $row['cnt'] : $row['total_cost'],
                    'total_cost' => $row['total_cost'],
                    'count' => $row['cnt']
                );
            }
            
            //Заполняем пустые дни
            $i=0;
            $today = mktime(23,59,59);
            while( ($time = strtotime("+$i day",$start_time)) && $time <=$today ) {
                $ymd = date('Ymd', $time);
                if (!isset($result[0][$ymd])) {
                    $result[0][$ymd] = array(
                        'x' => $time*1000,
                        'y' => 0,
                        'total_cost' => 0,
                        'count' => 0
                    );
                }
                $i++;
            }
            ksort($result[0]);
            $result[0] = array_values($result[0]);
        }
        return $result;
    }    
    
    /**
    * Возвращает количество заказов для каждого из существующих статусов
    * 
    * @return array
    */
    function getStatusCounts()
    {
        $q = clone $this->queryObj();
        $q->select = 'status, COUNT(*) cnt';
        $q->groupby('status');
        return $q->exec()->fetchSelected('status', 'cnt');
    }
    
    /**
    * Генерирует уникальный идентификатор заказа
    * 
    * @param \Shop\Model\Orm\Order $order - объект заказа
    */
    function generateOrderNum($order){
        $config    = \RS\Config\Loader::byModule('shop');
        $mask      = $config['generated_ordernum_mask'];
        $numbers   = $config['generated_ordernum_numbers'];
        
        $order_num = \RS\Helper\Tools::generatePassword($numbers,'0123456789');
        
        $code = mb_strtoupper(str_replace("{n}",$order_num,$mask));
        
        //Посмотрим есть ли такой код уже в базе
        $found_order = \RS\Orm\Request::make()
                ->from(new \Shop\Model\Orm\Order())
                ->where(array(
                    'order_num' => $code,
                    'site_id' => $order['site_id']
                ))
                ->object();
                
        if ($found_order){
            return $this->generateOrderNum($order);
        }
                
        return $code;
    }
    
    /**
    * Возвращает количество заказов, оформленных пользователем
    * 
    * @param mixed $user_id
    */
    function getUserOrdersCount($user_id)
    {
        return \RS\Orm\Request::make()
            ->from(new Orm\Order)
            ->where(array(
                'user_id' => $user_id,
                'site_id' => \RS\Site\Manager::getSiteId()
            ))->count();
    }
    
    /**
    * Создаёт отчёт о заказах за выбранный период и при заданных параметрах
    * 
    * @param \RS\Orm\Request $request - объект запроса списка заказов
    */
    function getReport(\RS\Orm\Request $request)
    {
       $list = array();
       //Соберём общую статистику 
       $request->select = ""; 
       $request->limit  = "";    
       $list['all'] = $request
            ->select('
                COUNT(id) as orderscount,
                SUM(totalcost) as totalcost,
                SUM(user_delivery_cost) as user_delivery_cost,
                SUM(deliverycost) as single_deliverycost
            ')
            ->exec()
            ->fetchRow();     
            
       //Цена без учёта стоимости доставки
       $list['all']['deliverycost']           = $list['all']['user_delivery_cost'] + $list['all']['single_deliverycost'];
       $list['all']['total_without_delivery'] = $list['all']['totalcost'] - $list['all']['deliverycost']; 
       //Соберём статистику по типам оплаты
       $request->select = "";
       $list['payment'] = $request
            ->select('
                payment,
                COUNT(id) as orderscount,
                SUM(totalcost) as totalcost,
                SUM(user_delivery_cost) as user_delivery_cost,
                SUM(deliverycost) as single_deliverycost
            ')
            ->groupby('payment')
            ->exec()
            ->fetchSelected('payment');
       
       if (!empty($list['payment'])){
            
           foreach($list['payment'] as $key=>$item){
              $list['payment'][$key]['deliverycost'] = $item['user_delivery_cost'] + $item['single_deliverycost']; 
              $list['payment'][$key]['total_without_delivery'] = $item['totalcost'] - $list['payment'][$key]['deliverycost'];  
           }
       }
       
       //Соберём статистику по типам доставки
       $request->select  = "";
       $request->groupby = "";
       $list['delivery'] = $request
            ->select('
                delivery,
                COUNT(id) as orderscount,
                SUM(totalcost) as totalcost,
                SUM(user_delivery_cost) as user_delivery_cost,
                SUM(deliverycost) as single_deliverycost
            ')
            ->groupby('delivery')
            ->exec()
            ->fetchSelected('delivery');  
       
       if (!empty($list['delivery'])){
           
           foreach($list['delivery'] as $key=>$item){
              $list['delivery'][$key]['deliverycost'] = $item['user_delivery_cost'] + $item['single_deliverycost']; 
           }
       }
            
       return $list;
    }
    
    /**
    * Добавляет массив дополнительных экстра данных в заказ, которые будут отображаться в заказе в админ панели
    * 
    * @param \Shop\Model\Orm\Order $order - объект заказа
    * @param string $step - идентификатор шага оформления (address, delivery, payment, confirm)
    * @param array $order_extra - массив доп. данных
    * 
    * @return void
    */
    function addOrderExtraDataByStep(\Shop\Model\Orm\Order $order, $step = 'order', $order_extra = array())
    {
         if (!empty($order_extra)) { //Заносим дополнительные данные, если они есть
            $arr = array($step => $order_extra);
            $order['order_extra'] = array_merge((array)$order['order_extra'], $arr);
         }
    }
}

