<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Config;
use \RS\Router;
use \RS\Orm\Type as OrmType;
use \RS\Html\Table\Type as TableType;
use \RS\Html\Filter;

class Handlers extends \RS\Event\HandlerAbstract
{
    function init()
    {
        $this
            ->bind('getroute')
            ->bind('getmenus')
            ->bind('user.auth')
            ->bind('delivery.gettypes')
            ->bind('payment.gettypes')
            ->bind('printform.getlist')
            ->bind('orm.init.catalog-product')
            ->bind('orm.init.catalog-dir')
            ->bind('controller.exec.users-admin-ctrl.index');
            
        if (\Setup::$INSTALLED) {
            $this->bind('orm.afterwrite.site-site', $this, 'onSiteCreate');
        }                
    }
    
    public static function controllerExecUsersAdminCtrlIndex(\RS\Controller\Admin\Helper\CrudCollection $crud_collection)
    {
        //Добавим колонку "Баланс" в таблицу с пользователями
        /**
        * @var \RS\Html\Table\Control
        */
        $table = $crud_collection['table'];
        $table->getTable()->addColumn(new TableType\Text('balance', 'Баланс', array('Sortable' => SORTABLE_BOTH)), -1);
        
        //Добавим фильтр по балансу
        /**
        * @var \RS\Html\Filter\Control
        */
        $filter = $crud_collection['filter'];
        
        $container = $filter->getContainer();
        $lines = $container->getLines();
        $lines[0]->addItem(new Filter\Type\Text('balance', 'Баланс', array(
            'showType' => true,
            'attr' => array(
                'class' => 'w50'
            )
        )));
        $container->cleanItemsCache();
        
        //Добавим действия в таблицу
        $router = \RS\Router\Manager::obj();
        $columns = $table->getTable()->getColumns();
        foreach($columns as $column) {
            if ($column instanceof TableType\Actions) {
                foreach($column->getActions() as $action) {
                    if ($action instanceof TableType\Action\DropDown) {
                        $action->addItem(array(
                                'title' => t('история транзакций'),
                                'attr' => array(
                                    '@href' => $router->getAdminPattern(false, array(':f[user_id]' => '@id'), 'shop-transactionctrl'),
                                )
                        ));
                        
                        $action->addItem(array(
                                'title' => t('исправить баланс'),
                                'attr' => array(
                                    'class' => 'crud-get',
                                    '@href' => $router->getAdminPattern('fixBalance', array(':id' => '@id'), 'shop-balancectrl'),
                                )
                        ));
                    }
                }
                $column->addAction(new TableType\Action\Action($router->getAdminPattern('addfunds', array(':id' => '~field~', 'writeoff' => 0), 'shop-balancectrl'), t('пополнить баланс'), array('class' => 'incbalance crud-add')), 0);
                $column->addAction(new TableType\Action\Action($router->getAdminPattern('addfunds', array(':id' => '~field~', 'writeoff' => 1), 'shop-balancectrl'), t('списать с баланса'), array('class' => 'decbalance crud-add')), 0);                
            }
        }
    }
    
    /**
    * Возвращает маршруты данного модуля
    */
    public static function getRoute(array $routes) 
    {        
        $routes[] = new Router\Route('shop-front-cartpage', '/cart/', null, t('Корзина'));
        $routes[] = new \RS\Router\Route('shop-front-multioffers', 
            '/multioffers/{product_id}/', null, t('Выбор многомерной комплектации'));
        $routes[] = new Router\Route('shop-front-checkout', array('/checkout/{Act}/', '/checkout/'), null, t('Оформление заказа'));
        $routes[] = new Router\Route('shop-front-documents', '/paydocuments/', null, t('Документ на оплату'));
        $routes[] = new Router\Route('shop-front-licenseagreement', '/license-agreement/', null, t('Лицензионное соглашение'));
        $routes[] = new Router\Route('shop-front-myorderview', array('/my/orders/view-{order_id}/'), null, t('Просмотр заказа'));
        $routes[] = new Router\Route('shop-front-myorders', array('/my/orders/'), null, t('Мои заказы'));
        $routes[] = new Router\Route('shop-front-mybalance', array('/my/balance/{Act}/', '/my/balance/'), null, t('Лицевой счет'));
        $routes[] = new Router\Route('shop-front-onlinepay', array('/onlinepay/{PaymentType}/{Act:(success|fail|result)}/', '/onlinepay/{Act}/'), null, t('Online платежи'));
        $routes[] = new Router\Route('shop-front-regiontools', '/regiontools/', null, t('Список регионов'), true);
        $routes[] = new Router\Route('shop-front-reservation', '/reservation/', null, t('Предварительный заказ товара'));
        
        return $routes;
    }
    
    /**
    * Привязывает корзину к пользователю после авторизации
    */
    public static function userAuth($params)
    {
        $user = $params['user'];
        //Привязываем корзину к пользователю
        $cart = \Shop\Model\Cart::currentCart();
        $items = $cart->getCartItemsByType();
        if (count($items)) {
            //Если будучи неавторизованным, пользователь собрал новую корзину, 
            //то НЕ импортируем корзину от авторизованного пользователя
            \RS\Orm\Request::make()->delete()
                ->from(new \Shop\Model\Orm\CartItem())
                ->where("user_id = '#user_id' AND session_id != '#session_id'", array(
                    'session_id' => session_id(),
                    'user_id' => $user['id']
                ))
                ->exec();
        } else {
            //Если текущая корзина пользователя пуста, а у авторизованного пользователя была собрана, 
            //то импортируем её
            \RS\Orm\Request::make()->update(new \Shop\Model\Orm\CartItem())
            ->set(array(
                'session_id' => session_id()
            ))->where(array(
                'user_id' => $user['id']
            ))->exec();
            
            $cart->cleanInfoCache();
        }
        
        \RS\Orm\Request::make()->update(new \Shop\Model\Orm\CartItem())
        ->set(array(
            'user_id' => $user['id']
        ))->where(array(
            'session_id' => session_id()
        ))->exec();
        
        \Shop\Model\Cart::destroy();
    }
    
    /**
    * Возвращает процессоры(типы) доставки, присутствующие в текущем модуле
    * 
    * @param array $list - массив из передаваемых классов доставок
    */
    public static function deliveryGetTypes($list)
    {        
        $list[] = new \Shop\Model\DeliveryType\FixedPay();
        $list[] = new \Shop\Model\DeliveryType\Myself();
        $list[] = new \Shop\Model\DeliveryType\Manual();
        $list[] = new \Shop\Model\DeliveryType\Ems();
        $list[] = new \Shop\Model\DeliveryType\Spsr();
        $list[] = new \Shop\Model\DeliveryType\RussianPost();
        $list[] = new \Shop\Model\DeliveryType\Universal();
        $list[] = new \Shop\Model\DeliveryType\Sheepla();
        $list[] = new \Shop\Model\DeliveryType\Cdek();
        return $list;
    }
    
    /**
    * Возвращает способы оплаты, присутствующие в текущем модуле
    * 
    * @param array $list - массив из передаваемых классов оплат
    */
    public static function paymentGetTypes($list)
    {
        $list[] = new \Shop\Model\PaymentType\Cash();
        $list[] = new \Shop\Model\PaymentType\Bill();
        $list[] = new \Shop\Model\PaymentType\FormPd4();
        $list[] = new \Shop\Model\PaymentType\Robokassa();
        $list[] = new \Shop\Model\PaymentType\Assist();
        $list[] = new \Shop\Model\PaymentType\PayPal();
        $list[] = new \Shop\Model\PaymentType\YandexMoney();
        $list[] = new \Shop\Model\PaymentType\PersonalAccount();
        return $list;
    }
    
    /**
    * Обрабатывает событие - создание сайта
    */
    public static function onSiteCreate($params)
    {
        if ($params['flag'] == \RS\Orm\AbstractObject::INSERT_FLAG) {
            $site = $params['orm'];
            \Shop\Model\Orm\UserStatus::insertDefaultStatuses($site['id']); //Добавляем статусы заказов по-умолчанию
            
            $module = new \RS\Module\Item('shop');
            $installer = $module->getInstallInstance();
            $installer->importCsv(new \Shop\Model\CsvSchema\Region(), 'regions', $site['id']);
            $installer->importCsv(new \Shop\Model\CsvSchema\Zone(), 'zones', $site['id']);
        }
    }    
    
    /**
    * Добавляем раздел "Налоги" в карточку товара
    */
    public static function ormInitCatalogProduct(\Catalog\Model\Orm\Product $orm_product)
    {
        $orm_product->getPropertyIterator()->append(array(
            t('Налоги'),
            'tax_ids' => new OrmType\Varchar(array(
                'description' => 'Налоги',
                'template' => '%shop%/productform/taxes.tpl',
                'default' => 'category',
                'list' => array(array('\Shop\Model\TaxApi', 'staticSelectList'))
            ))
        ));
    }
    
    /**
    * Добавляем раздел "Налоги" в категорию товара
    */
    public static function ormInitCatalogDir(\Catalog\Model\Orm\Dir $orm_dir)
    {
        $orm_dir->getPropertyIterator()->append(array(
            t('Налоги'),
            'tax_ids' => new OrmType\Varchar(array(
                'description' => 'Налоги',
                'default' => 'all',
                'template' => '%shop%/productform/taxes_dir.tpl',
                'list' => array(array('\Shop\Model\TaxApi', 'staticSelectList')),
                'rootVisible' => false
            ))
        ));
    }
    
    /**
    * Добавляет в систему печатные формы для заказа
    */
    public static function printFormGetList($list)
    {
        $list[] = new \Shop\Model\PrintForm\OrderForm();
        $list[] = new \Shop\Model\PrintForm\CommodityCheck();
        $list[] = new \Shop\Model\PrintForm\DeliveryNote();
        return $list;
    }
    
    /**
    * Возвращает пункты меню этого модуля в виде массива
    * 
    */
    public static function getMenus($items){

        $items[] = array(
                'title' => 'Магазин',
                'alias' => 'orders',
                'link' => '%ADMINPATH%/shop-orderctrl/',
                'parent' => 0,
                'sortn' => 10,
                'typelink' => 'link',                        
            );
        $items[] = array(
                'title' => 'Заказы',
                'alias' => 'allorders',
                'link' => '%ADMINPATH%/shop-orderctrl/',
                'sortn' => 0,
                'typelink' => 'link',                       
                'parent' => 'orders'
            );
        $items[] = array(
                'title' => 'Предварительные заказы',
                'alias' => 'advorders',
                'link' => '%ADMINPATH%/shop-reservationctrl/',
                'sortn' => 1,
                'typelink' => 'link',                       
                'parent' => 'orders'
            );
        $items[] = array(
                'typelink' => 'separator',
                'alias' => 'afteradvorders',
                'sortn' => 2,                  
                'parent' => 'orders'
            );
        $items[] = array(
                'title' => 'Скидочные купоны',
                'alias' => 'discount',
                'link' => '%ADMINPATH%/shop-discountctrl/',
                'sortn' => 3,
                'typelink' => 'link',                       
                'parent' => 'orders'
            );
        $items[] = array(
                'title' => 'Доставка',
                'alias' => 'deliverygroup',
                'link' => '%ADMINPATH%/shop-regionctrl/',
                'sortn' => 4,
                'typelink' => 'link',                        
                'parent' => 'orders'
            );
        $items[] = array(
                'title' => 'Способы доставки',
                'alias' => 'delivery',
                'link' => '%ADMINPATH%/shop-deliveryctrl/',
                'parent' => 'deliverygroup',
                'typelink' => 'link',  
                'sortn' => 1,
                                   
            );            
        $items[] = array(
                'title' => 'Регионы доставки',
                'alias' => 'regions',
                'link' => '%ADMINPATH%/shop-regionctrl/',
                'parent' => 'deliverygroup',
                'typelink' => 'link',      
                'sortn' => 2,
                                
            );
        $items[] = array(
                'title' => 'Зоны',
                'alias' => 'zones',
                'link' => '%ADMINPATH%/shop-zonectrl/',
                'parent' => 'deliverygroup',
                'sortn' => 3,
                'typelink' => 'link',                      
            );            
        $items[] = array(
                'title' => 'Способы оплаты',
                'alias' => 'payment',
                'link' => '%ADMINPATH%/shop-paymentctrl/',
                'sortn' => 5,
                'typelink' => 'link',                   
                'parent' => 'orders'
            );
        $items[] = array(
                'title' => 'Статусы заказов',
                'alias' => 'statuses',
                'link' => '%ADMINPATH%/shop-userstatusctrl/',
                'sortn' => 6,
                'typelink' => 'link',                    
                'parent' => 'orders'
            );
        $items[] = array(
                'title' => 'Налоги',
                'alias' => 'taxes',
                'link' => '%ADMINPATH%/shop-taxctrl/',
                'sortn' => 7,
                'typelink' => 'link',                    
                'parent' => 'orders'
            );
        $items[] = array(
                'title' => 'Транзакции',
                'alias' => 'transactions',
                'parent'=> 'userscontrol',
                'link' => '%ADMINPATH%/shop-transactionctrl/',
                'sortn' => 40,
                'typelink' => 'link',                     
            );
        return $items;
    }
    
}