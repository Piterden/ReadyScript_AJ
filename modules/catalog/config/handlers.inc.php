<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Catalog\Config;
use \RS\Orm\Type as OrmType;

/**
* Класс предназначен для объявления событий, которые будет прослушивать данный модуль и обработчиков этих событий.
* @ingroup Catalog
*/
class Handlers extends \RS\Event\HandlerAbstract
{
    function init()
    {
        $this
            ->bind('getroute')
            ->bind('orm.init.users-user')
            ->bind('orm.beforewrite.users-user')
            ->bind('getmenus')
            ->bind('getpages');
            
        if (\Setup::$INSTALLED) {
            $this->bind('orm.afterwrite.site-site', $this, 'onSiteCreate');
        }
    }
    
    public static function getRoute($routes) 
    {
        //Просмотр категории продукции
        $routes[] = new \RS\Router\Route('catalog-front-listproducts', array(
            '/catalog/{category}/',
            '/catalog/'
        ), null, t('Просмотр категории продукции'));
        
        
        //Карточка товара
        $routes[] = new \RS\Router\Route('catalog-front-product', 
            '/product/{id}/', null, t('Карточка товара'));
        
        //Сравнение товаров    
        $routes[] = new \RS\Router\Route('catalog-front-compare', 
            '/compare/', null, t('Сравнение товаров'));
        
        //Обработка страницы купить в 1 клик, добавление маршрута    
        $routes[] = new \RS\Router\Route('catalog-front-oneclick', 
            '/oneclick/{product_id}/', null, t('Купить в один клик'));
            
        //Отображение всех брендов в алфавитном порядке
        $routes[] = new \RS\Router\Route('catalog-front-allbrands', 
            '/brand/all/', null, t('Список брендов'));
        
        //Отображение отдельно брендов
        $routes[] = new \RS\Router\Route('catalog-front-brand', 
            '/brand/{id}/', null, t('Просмотр отдельного бренда'));
            
        //Отображение отдельно склада
        $routes[] = new \RS\Router\Route('catalog-front-warehouse', 
            '/warehouse/{id}/', null, t('Просмотр отдельного склада'));
            
        
        
        return $routes;
    }    
    
    public static function onSiteCreate($params)
    {
        if ($params['flag'] == \RS\Orm\AbstractObject::INSERT_FLAG) {
            
            //Добавляем цену по-умолчанию
            $site = $params['orm'];
            $defaultCost = new \Catalog\Model\Orm\Typecost();
            $defaultCost->getFromArray(array(
                'site_id' => $site['id'],
                'title' => t('Розничная'),
                'type' => 'manual',
                'round' => \Catalog\Model\Orm\Typecost::ROUND_DISABLED
            ))->insert();
            
            //Добавляем валюту по-умолчанию
            $defaultCurrency = new \Catalog\Model\Orm\Currency();
            $defaultCurrency->getFromArray(array(
                'site_id' => $site['id'],
                'title' => 'RUB',
                'stitle' => 'р.',
                'is_base' => 1,
                'ratio' => 1,
                'public' => 1,
                'default' => 1
            ))->insert();
            
            $catalog_config = \RS\Config\Loader::byModule('catalog', $site['id']);
            if ($catalog_config){
               $catalog_config['default_cost'] = $defaultCost['id'];
               $catalog_config->update(); 
            }
            
            $module = new \RS\Module\Item('catalog');
            $installer = $module->getInstallInstance();
            $installer->importCsv(new \Catalog\Model\CsvSchema\Warehouse(), 'warehouse', $site['id']);
        }
    }
    
    /**
    * Расширяем объект User, добавляя в него доп свойство - тип цены
    * 
    * @param \Users\Model\Orm\User $user
    */
    public static function ormInitUsersUser(\Users\Model\Orm\User $user)
    {
        $user->getPropertyIterator()->append(array(
            t('Настройка цен'),
            'user_cost' => new OrmType\ArrayList(array(
                'description' => t('Персональная цена'),
                'template'  => '%catalog%/form/user/personal_price.tpl'
            )),
            'cost_id' => new OrmType\Varchar(array(
                'description' => t('Персональная цена'),
                'visible'   => false, 
                'maxLength'  => 1000,
            )),
        ));
    }
    
    /**
    * Функция срабытывает перед сохранением пользователя
    * Сериализует массив c ценами сайтов для поля cost_id
    *                                               
    * @param array $user_array - массив с параметра
    */
    public static function ormBeforeWriteUsersUser($user_array)
    {
       $flag = $user_array['flag'];
      
       /**
       * @var \Users\Model\Orm\User
       */ 
       $user            = $user_array['orm'];
       
       if ($user->isModified('user_cost')) {
          $user['cost_id'] = serialize($user['user_cost']);
       }
    }
    
    
    public static function getPages($urls)
    {
        //Добавим страницы из каталога товаров в sitemap
        $api = new \Catalog\Model\Api();
        $api->setFilter('public', 1);
        $page = 1;
        while($list = $api->getList($page, 100)) {
            $page++;
            foreach($list as $product) {
                $urls[] = array(
                    'loc' => $product->getUrl()
                );
            }
        }
        
        //Добавим страницы брендов в sitemap
        $api = new \Catalog\Model\BrandApi();
        $api->setFilter('public', 1);
        $page = 1;
        while($list = $api->getList($page, 100)) {
            $page++;
            foreach($list as $brand) {
                $urls[] = array(
                    'loc' => $brand->getUrl()
                );
            }
        }
        
        //Добавим страницы складов в sitemap
        $api = new \Catalog\Model\WareHouseApi();
        $api->setFilter('public', 1);
        $api->setFilter('use_in_sitemap', 1);
        $page = 1;
        while($list = $api->getList($page, 100)) {
            $page++;
            foreach($list as $warehouse) {
                $urls[] = array(
                    'loc' => $warehouse->getUrl()
                );
            }
        }
        
        return $urls;
    }
    
    /**
    * Возвращает пункты меню этого модуля в виде массива
    * 
    */
    public static function getMenus($items)
    {
        $items[] = array(
                'title' => 'Товары',
                'alias' => 'products',
                'link' => '%ADMINPATH%/catalog-ctrl/',
                'sortn' => 20,
                'typelink' => 'link',
                'parent' => 0
            );        
        $items[] = array(
                'title' => 'Каталог товаров',
                'alias' => 'catalog',
                'link' => '%ADMINPATH%/catalog-ctrl/',
                'sortn' => 0,
                'typelink' => 'link',                   
                'parent' => 'products'
            );
        $items[] = array(
                'title' => 'Характеристики',
                'alias' => 'property',
                'link' => '%ADMINPATH%/catalog-propctrl/',
                'sortn' => 1,
                'typelink' => 'link',                        
                'parent' => 'products'
            );
        $items[] = array(
                'title' => 'Склады',
                'alias' => 'warehouse',
                'link' => '%ADMINPATH%/catalog-warehousectrl/',
                'typelink' => 'link',
                'sortn' => 2,
                'typelink' => 'link',                
                'parent' => 'products'
            );            
        $items[] = array(
                'title' => 'Бренды',
                'alias' => 'brand',
                'link' => '%ADMINPATH%/catalog-brandctrl/', //здесь %ADMINPATH% - URL админ. панели; shoplist - модуль; control - класс фронт контроллера
                'typelink' => 'link', //Тип пункта меню - ссылка
                'sortn' => 2,
                'typelink' => 'link',                
                'parent' => 'products'
            );
        $items[] = array(
                'title' => 'Справочник цен',
                'alias' => 'costs',
                'link' => '%ADMINPATH%/catalog-costctrl/',
                'sortn' => 3,
                'typelink' => 'link',                      
                'parent' => 'products'
            );
        $items[] = array(
                'title' => 'Единицы измерения',
                'alias' => 'unit',
                'link' => '%ADMINPATH%/catalog-unitctrl/',
                'sortn' => 4,
                'typelink' => 'link',                     
                'parent' => 'products'
            );
        $items[] = array(
                'title' => 'Валюты',
                'alias' => 'currency',
                'link' => '%ADMINPATH%/catalog-currencyctrl/',
                'sortn' => 5,
                'typelink' => 'link',                      
                'parent' => 'products'
            );
        $items[] = array(
                'typelink'  => 'separator',
                'alias' => 'oneclick_separator',
                'sortn' => 14,                     
                'parent' => 'products'
            );
        $items[] = array(
                'title' => 'Покупки в 1 клик',
                'alias' => 'oneclick',
                'link' => '%ADMINPATH%/catalog-oneclickctrl/',
                'sortn' => 16,
                'typelink' => 'link',                     
                'parent' => 'products'
            );
        return $items;
    }
}