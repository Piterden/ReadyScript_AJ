<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Config;

/**
* Класс отвечает за установку и обновление модуля
*/
class Install extends \RS\Module\AbstractInstall
{
    function install()
    {
        $result = parent::install();
        if ($result) {
            //Вставляем в таблицы данные по-умолчанию, в рамках нового сайта, вызывая принудительно обработчик события
            \Shop\Config\Handlers::onSiteCreate(array(
                'orm' => \RS\Site\Manager::getSite(),
                'flag' => \RS\Orm\AbstractObject::INSERT_FLAG
            ));
            
            //Обновляем структуру базы данных для объекта Товар
            $product = new \Catalog\Model\Orm\Product();
            $product->dbUpdate();
            
            //Обновляем структуру базы данных для объекта Категория Товара
            $dir = new \Catalog\Model\Orm\Dir();
            $dir->dbUpdate();
            
            //Добавляем виджеты на рабочий стол
            $widget = new \Main\Model\Orm\Widgets();
            $user_id = 1;
            
            //Виджет Недавние заказы
            $widget->getFromArray(array(
                'user_id' => $user_id,
                'col' => 'left',
                'class' => 'shop-widget-lastorders'
            ))->insert();

            //Виджет Статусы заказов
            $widget->getFromArray(array(
                'id' => null,
                'position' => null,
                'user_id' => $user_id,
                'col' => 'left',
                'class' => 'shop-widget-orderstatuses'
            ))->insert();
            
        }
        return $result;
    }  
    
    
    /**
    * Добавляет демонстрационные данные
    * 
    * @param array $params - произвольные параметры. 
    * @return boolean|array
    */
    function insertDemoData($params = array())
    {
        return $this->importCsvFiles(array(
            array('\Shop\Model\CsvSchema\Delivery', 'delivery'),
            array('\Shop\Model\CsvSchema\Payment', 'payments'),
         ), 'utf-8', $params);
    }
    
    /**
    * Возвращает true, если модуль может вставить демонстрационные данные
    * 
    * @return bool
    */
    function canInsertDemoData()
    {
        return true;
    }  
    
}
