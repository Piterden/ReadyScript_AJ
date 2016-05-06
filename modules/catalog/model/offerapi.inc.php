<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Catalog\Model;

/**
* API комплектаций товара
*/
class OfferApi extends \RS\Module\AbstractModel\EntityList
{
    protected
        $filter_parts = array();
        
    function __construct()
    {
        parent::__construct(new \Catalog\Model\Orm\Offer, array(
            'defaultOrder' => 'sortn',
            'sortField' => 'sortn',
            'titleField' => 'title'
        ));
    }
    
    /**
    * Устанавливает фильтры по комплектациям для карточки товара
    * 
    * @return void
    */
    function applyFormFilter($get_filters, $url_params = array())
    {
        $this->filter_parts = array();
        $allow_keys = array(
                        'title' => t('Название'), 
                        'barcode' => t('Артикул'), 
                        'num' => t('Остаток'));
                        
        $allow_cmp = array('=', '<', '>');
        $like_cmp_keys = array('title', 'barcode');
        $router = \RS\Router\Manager::obj();
        
        foreach($get_filters as $key => $value) {
            if (!in_array($key, array_keys($allow_keys)) || $value === '') continue;
            
            if (in_array($key, $like_cmp_keys)) {
                $cmp = '%like%';
            } else {
                $cmp = isset($get_filters['cmp_'.$key]) ? html_entity_decode($get_filters['cmp_'.$key]) : false;
                if (!in_array($cmp, $allow_cmp)) $cmp = false;
            }
            $this->setFilter($key, $value, $cmp ?: '=');
            
            $without_filter = $get_filters;
            unset($without_filter[$key]);
            unset($without_filter['cmp_'.$key]);
            
            if (in_array($cmp, array('<', '>'))) {
                $value = $cmp.$value;
            }
            
            $this->filter_parts[] = array(
                'text' => $allow_keys[$key].': '.$value,
                'clean_url' => $router->getAdminUrl(false, array('offer_filter' => $without_filter) + $url_params, 'catalog-block-offerblock')
            );
        }
    }
    
    /**
    * Возвращает установленные фильтры в карточке товара
    * 
    * @return array
    */
    function getFormFilterParts()
    {
        return $this->filter_parts;
    }
    
    /**
    * Сохраняет комплектации для товара
    * 
    * @param integer $product_id ID товара
    * @param array $offers массив с комплектациями. Если передан один элемент с ключем main, значит 
    * передана только основная комплектация. В этом случае Дополнительные комплектации (где sortn>0) не будут затронуты.
    * В противном случае (когда все ключи - числовые), считается, что переданные комплектации должны 
    * полностью заместить существующие комплектации.
    * @param bool $use_unconverted_propsdata - Если true, то будет учитываться _propsdata для сохранения характеристик комплектаций
    * @return void
    */
    function saveOffers($product_id, $offers, $use_unconverted_propsdata = false)
    { 
        //Если true, значит передана только основная комплектация.
        //Это означает, что не следует удалять оставшиеся комплектации
        $is_only_main_offer = (count($offers) == 1 && isset($offers['main']));
        
        $exclude_items = array();
        $n = 0;
        foreach($offers as $key => $item) {
            
            if ($item instanceof Orm\Offer) {
                $item = $item->getValues();
            }            
            if (!is_array($item)) $item = array();
            $item += array(
                'photos_arr' => array(),
                'pricedata_arr' => array()
            );
            
            if ($use_unconverted_propsdata) {
                $item += array('_propsdata' => array());
            }
            
            $offer = new Orm\Offer();
            if ($is_only_main_offer) {
                $offer = Orm\Offer::loadByWhere(array(
                    'product_id' => $product_id,
                    'sortn' => 0
                ));
            }
            $offer->getFromArray($item);
            
            $offer['sortn'] = $n;
            $offer['product_id'] = $product_id;
            
            if ($offer['id']>0){
               $offer->update();
            }else{
               $offer->insert();  
            }
            
            $exclude_items[] = $offer['id'];
            $n++;
        }  
        
        if (!$is_only_main_offer) {
            //Удалим комплектации отсутствующие
            $this->deleteExcludedOffers($product_id, $exclude_items);
        }
        
        $this->updateProductNum($product_id);
    }
    
    
    /**
    * Обновляет общее количество товара, на основе количества комплектаций
    * 
    * @param integer $product_id - id товара
    * @param integer $cnt - количество товара
    * @return void
    */
    function updateProductNum($product_id)
    {
        $sub_query = \RS\Orm\Request::make()
                        ->select('SUM(num)')
                        ->from(new Orm\Offer(), 'O')
                        ->where('O.product_id = P.id')
                        ->toSql();
        
        \RS\Orm\Request::make()
            ->update(new \Catalog\Model\Orm\Product)
            ->asAlias('P')
            ->set("P.num = ($sub_query)")
            ->where(array(
                'id' => $product_id
            ))->update()
            ->exec(); 
    }
    
    /**
    * Удаляет комплектации, которые которые удалены у товара 
    * 
    * @param integer $product_id - id товара для которого исключаеются комплектации
    * @param array $offers_ids   - id комлектаций, которые дожны быть не тронуты(остатся) 
    */
    function deleteExcludedOffers($product_id, $offers_ids)
    {
        if ($this->noWriteRights()) return false;
        
        if ($offers_ids) {
            //Удаляем комплектации.
            \RS\Orm\Request::make()
                    ->from(new \Catalog\Model\Orm\Offer())
                    ->where('id NOT IN ('.implode(",",$offers_ids).')')
                    ->where(array(
                        'product_id' => $product_id
                    ))
                    ->delete()->exec();
            //Удаляем количество товаров комплектаций
            \RS\Orm\Request::make()
                    ->from(new \Catalog\Model\Orm\Xstock())
                    ->where('offer_id NOT IN ('.implode(",",$offers_ids).')')
                    ->where(array(
                        'product_id' => $product_id
                    ))
                    ->delete()->exec();
        }
        
        return true;
    }
    
    /**
    * Удаляет комплектации по id товара или массиву с id товаров
    * 
    * @param integer|array $product_id - id товара или массив с id товаров
    */
    function deleteOffersByProductId($product_id) 
    {
       if ($this->noWriteRights()) return false;
       
       if (is_array($product_id)){ 
          //Удаляем комлектации
          \RS\Orm\Request::make()->delete()->from(new Orm\Offer())
              ->whereIn('product_id',
                $product_id
              )
              ->where('sortn > 0')
              ->exec();       
       }else{
          //Удаляем комлектации
          \RS\Orm\Request::make()->delete()->from(new Orm\Offer())->where(array(
            'product_id' => $product_id
          ))->exec();  
       } 
       
       //Удаляем остатки по складам 
       $sub_query = \RS\Orm\Request::make()
            ->select('id')
            ->from(new \Catalog\Model\Orm\Offer())
            ->toSql();
       $q = \RS\Orm\Request::make()
            ->from(new \Catalog\Model\Orm\Xstock())
            ->where('offer_id NOT IN ('.$sub_query.')')
            ->delete()
            ->exec(); 
            
       return true;
       
    }
    
    /**
    * Перестраивает сортировочные индексы у дополнительных комплектаций,
    * т.к. в результате удаления комплектаций, могут появиться пропуски в sortn
    */
    function rebuildSortn($product_id)
    {
        if ($this->noWriteRights()) return false;
        
        $offers_sortn = \RS\Orm\Request::make()
            ->select('id, sortn')
            ->from($this->obj_instance)
            ->where('sortn > 0')
            ->where(array(
                'product_id' => $product_id
            ))
            ->orderby('sortn')
            ->exec()->fetchSelected('id', 'sortn');
        
        $i = 1;
        foreach($offers_sortn as $id => $sortn) {
            if ($sortn != $i) {
                \rs\Orm\Request::make()
                    ->update($this->obj_instance)
                    ->set(array(
                        'sortn' => $i
                    ))
                    ->where(array('id' => $id))
                    ->exec();
            }
            $i++;
        }
        return true;
    }
    
    
    /**
    * Обновляет свойства у группы объектов
    * 
    * @param array $data - ассоциативный массив со значениями обновляемых полей
    * @param array $ids - список id объектов, которые нужно обновить
    * @return integer - возвращает количество обновленных элементов
    */
    function multiUpdate(array $data, $ids = array())
    {
        if ($this->noWriteRights()) return false;
        
        if (isset($data['photos_arr'])) {
            $photos = serialize($data['photos_arr']);
            \RS\Orm\Request::make()
                ->update(new Orm\Offer())
                ->set(array(
                    'photos' => $photos
                ))
                ->whereIn('id', $ids)
                ->exec();
                
            unset($data['photos_arr']);
        }        
        
        if (isset($data['pricedata_arr'])) {
            
            if (empty($data['pricedata_arr']['oneprice']['use'])) { 
                unset($data['pricedata_arr']['oneprice']);
                
                //Отсечем цены, которые не заданы                
                foreach($data['pricedata_arr']['price'] as $key => $one_price) {
                    if ($one_price['original_value'] === '') {
                        unset($data['pricedata_arr']['price'][$key]);
                    }
                }
                
                $data['pricedata_arr'] = $this->obj_instance->convertValues($data['pricedata_arr']);
                
                $q = \RS\Orm\Request::make()
                    ->select('id, pricedata')
                    ->from($this->obj_instance)
                    ->whereIn('id', $ids)
                    ->limit(50);
                
                $offset = 0;
                while($offer_rows = $q->offset($offset)->exec()->fetchAll()) {
                    foreach($offer_rows as $row) {
                        $old_pricedata = @unserialize($row['pricedata']) ?: array();
                        $old_pricedata += array('price' => array());
                        
                        //Дополняем старыми ценами
                        $new_pricedata = $data['pricedata_arr'];
                        $new_pricedata['price'] += $old_pricedata['price'];
                
                        $pricedata = serialize($new_pricedata);
                        \RS\Orm\Request::make()
                            ->update($this->obj_instance)
                            ->set(array(
                                'pricedata' => $pricedata
                            ))
                            ->where(array(
                                'id' => $row['id']
                            ))->exec();                
                    }
                    $offset +=50;
                }                
            } else {
                //Если одна цена задана, то обновляем одним запросом у всех
                $data['pricedata_arr'] = $this->obj_instance->convertValues($data['pricedata_arr']);
                $pricedata = serialize($data['pricedata_arr']);
                \RS\Orm\Request::make()
                    ->update($this->obj_instance)
                    ->set(array(
                        'pricedata' => $pricedata
                    ))
                    ->whereIn('id', $ids)->exec();                      
            }
                
            unset($data['pricedata_arr']);
        }        
        
        if (isset($data['_propsdata'])) {
            $props_data_arr = array();
            if (!empty($data['_propsdata'])) {
                foreach($data['_propsdata']['key'] as $n => $val) {
                    if ($val !== '') {
                        $props_data_arr[$val] = $data['_propsdata']['val'][$n];
                    }
                }
            }
            $data['propsdata_arr'] = $props_data_arr;
            unset($data['_propsdata']);
        }
        
        if (isset($data['propsdata_arr'])) {
            $propsdata = serialize($data['propsdata_arr']);
            \RS\Orm\Request::make()
                ->update(new Orm\Offer())
                ->set(array(
                    'propsdata' => $propsdata
                ))
                ->whereIn('id', $ids)
                ->exec();
                
            unset($data['propsdata_arr']);
        }
        
        if (isset($data['stock_num'])) {
            //Очистим остатки по складам
            \RS\Orm\Request::make()
                ->delete()           
                ->from(new \Catalog\Model\Orm\Xstock())
                ->whereIn('offer_id', $ids)
                ->exec();
                
            $offerId_productId = \RS\Orm\Request::make()
                ->select('id, product_id')
                ->from($this->obj_instance)
                ->whereIn('id', $ids)
                ->exec()->fetchSelected('id', 'product_id');
           
            $data['num'] = 0;
           
            foreach ($data['stock_num'] as $warehouse_id => $stock_num) {
                foreach($ids as $offer_id) {
                    //Добавим остатки по складам  
                    $offer_stock = new \Catalog\Model\Orm\Xstock(); 
                    $offer_stock['product_id']   = $offerId_productId[$offer_id];
                    $offer_stock['offer_id']     = $offer_id;
                    $offer_stock['warehouse_id'] = $warehouse_id;
                    $offer_stock['stock']        = $stock_num;
                    $offer_stock->insert();                    
                }
                $data['num'] += $stock_num;
           }             
           unset($data['stock_num']);
        }
        
        return parent::multiUpdate($data, $ids);
    }    
    
    /**
    * Удаляет несвязанные с товарами комплектации
    * Операция необходима для очистки базы от неиспользуемых записей
    * 
    * @return int возвращает 
    */
    function cleanUnusedOffers()
    {
        return \RS\Orm\Request::make()
            ->delete()
            ->from(new \Catalog\Model\Orm\Offer())
            ->where('product_id < 0')
            ->exec()->affectedRows();
    }
    
    /**
    * Возвращает данные, необходимые для отображения диалога связи комплектаций и фото
    * 
    * @param array $mainoffer - основная комплектация
    * [
    * 
    * ]
    * @param integer|null $product_id - id товара
    * @param integer $photo_id - id фотографии
    * 
    * @return array
    * [
    *   'params' => [ключ => [значение1, значение2, значение3]],
    *   'offers' => [id комплектации => название],
    *   'selected' => [id комплектации, id комплектации]
    * ]
    */
    function getOffersLinkDialogData($mainoffer, $product_id, $photo_id = null)
    {
        $result = array(
            'params' => array(),
            'offers' => array(),
            'selected' => array()
        );
        
        $offer = new Orm\Offer();
        $mainoffer['propsdata_arr'] = isset($mainoffer['_propsdata']) ? $offer->convertPropsData($mainoffer['_propsdata']) : array();
        
        $offers = array_merge(array($mainoffer),
                    \RS\Orm\Request::make()
                        ->from($this->obj_instance)
                        ->where('sortn>0')
                        ->where(array('product_id' => $product_id))
                        ->orderby('sortn')
                        ->exec()->fetchAll());
            
        
        foreach($offers as $offer_arr) {
            $title = array();
            $offer->clear();
            $offer->getFromArray($offer_arr);
            
            foreach($offer['propsdata_arr'] as $key => $value) {
                $result['params'][$key][$value] = $value;
                $title[] = $value;
            }
            
            $title = trim(implode(', ', $title));
            $result['offers'][$offer['id']] = array(
                'title' => ($title == '') ? $offer['title'] : $title,
                'params' => $offer['propsdata_arr']
            );
            
            if ($photo_id) {
                if (in_array($photo_id, $offer['photos_arr'] ?: array())) {
                    $result['selected'][] = $offer['id'];
                }
            }
        }
        return $result;
    }
    
    /**
    * Привязывает/отвязывает фото к комплектациям
    * 
    * @param array $photos_id 
    * @param array $offers_id
    * @return array возвращает массив выбранных фото для Основной комплектации
    */
    function linkPhotosToOffers($product_id, $photos_id, $offers_id, $mainoffer)
    {
        if ($this->noWriteRights()) return false;
        
        //Все комплектации товара
        $all_offers = array_merge(array($mainoffer), 
            \RS\Orm\Request::make()
            ->select('id, photos')
            ->from($this->obj_instance)
            ->where(array(
                'product_id' => $product_id
            ))
            ->where('sortn>0')
            ->exec()->fetchAll());
        
        $result = array();
        
        foreach($all_offers as $n => $offer) {
            if (isset($offer['photos'])) {
                $photos_arr = @unserialize($offer['photos']) ?: array();
            } else {
                $photos_arr = isset($offer['photos_arr']) ? $offer['photos_arr'] : array();
            }
            
            if (in_array($offer['id'], $offers_id)) {
                //Если фотографии назначены комплектации, то включаем их в список
                $photos_arr = array_unique( array_merge($photos_arr, $photos_id) );
            } else {
                //Если фотографии не назначены комплектации, то исключаем их
                $photos_arr = array_diff($photos_arr, $photos_id);
            }
            
            //Обновляем комплектации
            \RS\Orm\Request::make()
                ->update($this->obj_instance)
                ->set(array(
                    'photos' => serialize($photos_arr)
                ))
                ->where(array(
                    'id' => $offer['id']
                ))->exec();
            
            if ($n == 0) $result = array_values($photos_arr);
        }
        
        return $result;
    }
}
?>
