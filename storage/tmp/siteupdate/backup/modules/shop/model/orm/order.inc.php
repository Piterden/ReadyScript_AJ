<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Model\Orm;
use \RS\Orm\Type,
    \Catalog\Model\Orm\Product;

/**
* ORM Объект - заказ.
*/
class Order extends \RS\Orm\OrmObject
{
    const
        ACL_WRITE_ORDER_BIT = 2,
        ORDER_SESS_VAR = 'ORDER-ORMOBJECT';    
        
    protected 
        $use_generated_order_num = null, //Флаг использовать уникальный номер заказа
        $products_hash = null, //хэш от товаров в заказе
        $this_before_write;
    
    public
        $address,
        $order_cart,
        $session_cart;
    
    protected static
        $currency,
        $table = 'order';
    
    function _init()
    {
        $properties = parent::_init()
            ->groupSet('condition', array('step' => 'init'))
            ->append(array(
                'site_id' => new Type\CurrentSite(),
                'order_num' => new Type\String(array(
                    'maxLength' => '20',
                    'description' => t('Уникальный идентификатор номера заказа'),
                )),
                'step' => new Type\Mixed(array(
                )),
                'user_id' => new Type\User(array(
                    'maxLength' => '11',
                    'attr' => array(array(
                        'data-autocomplete-body' => '1'
                    )),
                    'description' => t('ID покупателя'),
                )),
                'basket' => new Type\Mixed(array(
                    'description' => t('Объект - корзина'),
                )),
                'currency' => new Type\String(array(
                    'maxLength' => '5',
                    'description' => t('Трехсимвольный идентификатор на момент оформления заказа'),
                )),
                'currency_ratio' => new Type\Float(array(
                    'description' => t('Курс относительно базововй валюты')
                )),
                'currency_stitle' => new Type\String(array(
                    'description' => t('Символ валюты'),
                    'maxLength' => 10
                )),
                'ip' => new Type\String(array(
                    'maxLength' => '15',
                    'description' => t('IP'),
                )),
                'notify_user' => new Type\Integer(array(
                    'runtime' => true,
                    'description' => t('Уведомлять пользователя об изменении в заказе')
                )),
        
            //Подтверждение
                'dateof' => new Type\Datetime(array(
                    'description' => t('Дата заказа'),
                )),
                'totalcost' => new Type\Decimal(array(
                    'maxLength' => '15',
                    'decimal' => 2,
                    'description' => t('Общая стоимость'),
                )),
                'user_delivery_cost' => new Type\Decimal(array(
                    'maxLength' => '15',
                    'decimal' => 2,
                    'description' => t('Стоимость доставки, определенная администратором'),
                )),
                'is_payed' => new Type\Integer(array(
                    'maxLength' => '1',
                    'description' => t('Заказ полностью оплачен?'),
                    'CheckBoxView' => array(1,0),
                )),
                'status' => new Type\Integer(array(
                    'maxLength' => '11',
                    'description' => t('Статус'),
                    'index' => true,
                )),
                'admin_comments' => new Type\Text(array(
                    'description' => t('Комментарии администратора (не отображаются пользователю)'),
                    'Attr' => array(array('class' => 'fullwide')),
                )),
                'user_text' => new Type\Richtext(array(
                    'description' => t('Текст для покупателя'),
                    'Attr' => array(array('class' => 'fullwide'))
                )),
                '_serialized' => new Type\Text(),
                'userfields' => new Type\Text(array(
                    'description' => t('Дополнительные сведения'),
                    'condition' => array('step' => 'address'),
                    'visible' => false
                )),                            
                'extra' => new Type\ArrayList(array(
                    'visible' => false
                )),
                'hash' => new Type\String(array(
                    'maxLength' => '32',
                )),
                'expired' => new Type\Mixed(),                
                'is_exported' => new Type\Integer(array(
                    'maxLength' => '1',
                    'default' => 0,
                    'description' => t('Выгружен ли заказ'),
                    'visible' => false
                )),
        ))->cancelGroupSet();
        
        $this->addIndex(array('site_id', 'order_num'),self::INDEX_UNIQUE);
        
        $chk_depend = array(__CLASS__, 'chkDepend');
        
        
        //поля для регистрации пользователя
        $chk_condition = array(
            'user_type' => array('person', 'company'),
            'step' => 'address'
        );        
        
        $properties
            ->append(array(
                'user_type' => new Type\String(array(
                    'maxLength' => '30',
                    'condition' => array('step' => 'address'),
                    'runtime' => true
                ))
            ))
            ->groupSet('condition', $chk_condition)
            ->groupSet('runtime', true)
            ->append(array(    
                'reg_name' => new Type\String(array(
                    'description' => t('Имя'),
                    'maxLength' => '200',
                )),
                'reg_surname' => new Type\String(array(
                    'description' => t('Фамилия'),
                    'maxLength' => '200',
                )),
                'reg_midname' => new Type\String(array(
                    'description' => t('Отчество'),
                    'maxLength' => '200',
                )),
                'reg_phone' => new Type\String(array(
                    'description' => t('Телефон'),
                    'maxLength' => '100',
                )),
                'reg_e_mail' => new Type\String(array(
                    'description' => t('E-mail'),
                    'maxLength' => '100',
                )),
                'reg_autologin' => new Type\Integer(array(
                    'maxLength' => '1',
                    'CheckBoxView' => array(1,0),
                )),
                'reg_login' => new Type\String(array(
                    'description' => t('Логин'),
                    'maxLength' => '70',
                )),
                'reg_openpass' => new Type\String(array(
                    'description' => t('Пароль'),
                    'maxLength' => '70',
                    'attr' => array(array('type' => 'password')),
                )),
                'reg_pass2' => new Type\String(array(
                    'maxLength' => '70',
                    'attr' => array(array('type' => 'password')),
                )),
                'regfields' => new Type\ArrayList(array(
                    'description' => t('Дополнительные сведения'),
                ))
            ));
            
            
        // поля для регистрации предприятия
        $chk_condition = array(
            'user_type' => 'company',
            'step' => 'address'
        );
        $properties
            ->groupSet('condition', $chk_condition)
            ->append(array(
                'reg_company' => new Type\String(array(
                    'maxLength' => '255',
                )),
                'reg_company_inn' => new Type\String(array(
                    'maxLength' => '50'
                )),
            ));
            
        
        // поля для авторизации пользователя
        $chk_condition = array(
            'user_type' => 'user',
            'step' => 'address'
        );
        $properties
            ->groupSet('condition', $chk_condition)            
            ->append(array(
                'login' => new Type\String(array(
                    'maxLength' => '100',
                )),
                'password' => new Type\String(array(
                    'maxLength' => '100',
                    'Attr' => array(array('type' => 'password')),
                ))
            ))
            ->cancelGroupSet();
        
        // Поля для адреса доставки
        $properties
            ->append(array(
                'contact_person' => new Type\String(array(
                    'maxLength' => '255',
                    'description' => t('Контактное лицо'),
                    'condition' => array('step' => 'address'),
                )),
                'use_addr' => new Type\Integer(array(
                    'maxLength' => '11',
                    'description' => t('ID адреса доставки'),
                    'condition' => array('step' => 'address'),
                ))
            ));

        $chk_condition = array(
            'use_addr' => '0',
            'step' => 'address'
        );
        
        $properties
            ->groupSet('condition', $chk_condition)
            ->groupSet('runtime', true)
            ->append(array(
                'addr_country_id' => new Type\String(array(
                    'maxLength' => '100',
                    'description' => t('Страна'),
                    'Checker' => array('chkEmpty', t('Страна - обязательное поле')),
                    'List' => array(array('\Shop\Model\RegionApi', 'countryList')),
                    'Attr' => array(array('size' => 1)),
                )),
                'addr_region' => new Type\String(array(
                    'maxLength' => '100',
                )),
                'addr_region_id' => new Type\String(array(
                    'maxLength' => '100',
                    'description' => t('Область/Край'),
                    'List' => array(array(__CLASS__, 'regionList')),
                    'Attr' => array(array('size' => 1)),
                )),
                'addr_city' => new Type\String(array(
                    'maxLength' => '100',
                    'description' => t('Город'),
                    'Checker' => array('chkEmpty', t('Город - обязательное поле')),
                )),
                'addr_zipcode' => new Type\String(array(
                    'maxLength' => '20',
                    'description' => t('Индекс'),
                )),
                'addr_address' => new Type\String(array(
                    'maxLength' => '255',
                    'description' => t('Адрес'),
                    'Checker' => array(function($object, $value, $error) 
                    {
                        $config = \RS\Config\Loader::byModule($object);
                        if ($config['require_address'] && empty($value)) {
                            return $error;
                        }
                        return true;
                    }, 'Адрес - обязательное поле'),
                ))
            ))->cancelGroupSet();

        $properties->append(array(
            'userfields_arr' => new Type\ArrayList(array(
                'description' => t('Дополнительные сведения'),
                'condition' => array('step' => 'address'),
            )),
            'code' => new Type\Captcha(array(
                'condition' => array('step' => 'address'),
            )),
            
        //Шаг 2
            'delivery' => new Type\Integer(array(
                'maxLength' => '11',
                'description' => t('Доставка'),
                'condition' => array('step' => 'delivery'),
                'Checker' => array('chkEmpty', 'Укажите тип доставки'),
                'List' => array(array('\Shop\Model\DeliveryApi', 'staticSelectList')),     

            )),
            'deliverycost' => new Type\Decimal(array(
                'maxLength' => '15',
                'decimal' => 2,
                'description' => t('Стоимость доставки'),
                'condition' => array('step' => 'delivery'),
            )),
            
        //Шаг 2.2 - только при самовывозе
            'warehouse' => new Type\Integer(array(
                'maxLength' => '11',
                'description' => t('Склад'),
                'condition' => array('step' => 'warehouses'),
                'Checker' => array('chkEmpty', 'Укажите склад для забора товара'),
                'List' => array(array('\Catalog\Model\WarehouseApi', 'staticSelectList')),     
            )),
            
        //Шаг 3
            'payment' => new Type\Integer(array(
                'description' => t('Тип оплаты'),
                'condition' => array('step' => 'pay'),
                'Checker' => array('chkEmpty', 'Укажите способ оплаты'),
                'List' => array(array('\Shop\Model\PaymentApi', 'staticSelectList')),     
            )),
            
        //Шаг 4
            'comments' => new Type\Text(array(
                'description' => t('Комментарий'),
                'condition' => array('step' => 'confirm')
            )),

        ));
        
        //Поля для заказа без авторизации или регистрации
        $properties->append(array(
            'user_fio' => new Type\String(array(
                'description' => t('Ф.И.О.'),
                'Attr' => array(array(
                    'size' => 40,
                )),
                'maxLength' => 255
            )),
            'user_email' => new Type\String(array(
                'description' => t('E-mail'),
                'Attr' => array(array(
                    'size' => 40,
                )),
                'maxLength' => 255
            )),
            'user_phone' => new Type\String(array(
                'description' => t('Телефон'),
                'Attr' => array(array(
                    'size' => 40,
                )),
                'maxLength' => 255
            )),
        ));
        
        $this->setWriteBit(self::ACL_WRITE_ORDER_BIT);        
    }
    
    
    /**
    * Функция срабатывает перед записью заказа
    * 
    * @param string $flag - insert или update
    * @return void
    */
    function beforeWrite($flag)
    {
        $this->this_before_write = new Order($this['id'], false);
        
        if ($this['id'] < 0) {
            $this['_tmpid'] = $this['id'];
            unset($this['id']);
        }
        
        $config = \RS\Config\Loader::byModule($this);
        
        //Проверка уникального идентификатора номера заказа
        if (empty($this['order_num']) && $config['use_generated_order_num']) {
            $api = new \Shop\Model\OrderApi();
            $this['order_num'] = $api->generateOrderNum($this);
        }     

        if ($flag == self::INSERT_FLAG) {
            //Проверяем наличие необходимого количества товаров.
            if ($config['check_quantity']) {
                $pnum_check = $this->checkProductsNum();
                if ($pnum_check !== true) {
                    return $this->addError($pnum_check);
                }
            }

            $pay = $this->getPayment();
            $pay->getTypeObject()->onCreateOrder();

            $delivery = $this->getDelivery();
            
            //Устанавливаем первоначальный статус заказа
            if (!empty($config['first_order_status'])) {
                $status = $config['first_order_status'];
            } else {
                $status = \Shop\Model\UserStatusApi::getStatusIdByType( \Shop\Model\Orm\UserStatus::STATUS_NEW );
            }
            
            if ($pay['first_status']>0) {
                $status = $pay['first_status'];
            }
            
            if ($delivery['first_status']>0) {
                $status = $delivery['first_status'];
            }
            
            $this['status'] = $status;
            $this['ip']     = $_SERVER['REMOTE_ADDR'];
            $this['dateof'] = date('Y-m-d H:i:s');
            $this['hash']   = md5(uniqid(mt_rand(), true));
            if(!$this->isModified('is_exported')){
                $this['is_exported'] = 0;
            }

            //Создаем корзину к заказу
            if ($this['basket']) {
                $cart = $this->getCart(); //Подготавливаем корзину к переносу в заказ
                if ($cart && ($error = $cart->makeOrderCart()) !== true) {
                    return $this->addError($error);
                }
            }
            
            //Если есть массив дополнительных данных, то сохраним их
            if (!empty($this['order_extra'])){
                foreach ($this['order_extra'] as $order_key_step=>$order_step){
                    $m=0;
                    foreach ($order_step as $extra_title=>$extra_value){
                        $extra_uniq_key = $order_key_step."_".$m;
                        $this->addExtraInfoLine($extra_title, $extra_value, null, $extra_uniq_key); 
                        $m++;   
                    }
                }
            }
        }      
                
        if ($flag == self::UPDATE_FLAG) { //При обновлении заказа 
            /**
            * @var \Shop\Model\Orm\Order
            */
            $this->before_this = new self($this['id']);
            //Подгрузим данные для дальнейшей сверки с новыми
            //Прежняя корзина
            $this->before_this['cart_md5'] = $this->before_this->getProductsHash(); 
            
            if ($config['check_quantity']) { //Если у товара меняется статус, изменяем количество у товара, если нужно.
                    $cancelled = \Shop\Model\UserStatusApi::getStatusIdByType( \Shop\Model\Orm\UserStatus::STATUS_CANCELLED );
                    if ($this['status'] == $cancelled && $this->before_this['status'] != $cancelled ) {
                        $this->incProductsNum(); //Заказ был отменен, возвращаем товарам их количество
                    }
                    if ($this['status'] != $cancelled && $this->before_this['status'] == $cancelled ) {
                        $this->decProductsNum(); //Заказ был возобновлен, отнимаем у товаров количество
                    }
                    
                    if (isset($this['back_warehouse'])){ //Если склад изменили, то вернём остатки на старый склад
                       $this->incProductsNum($this['back_warehouse']);
                    }
            }
        }
        
        $this['_serialized'] = serialize($this['extra']);
        $this['userfields']  = serialize($this['userfields_arr']);   
    }
    
    /**
    * Фукнция срабатывает после записи объекта в БД
    * 
    * @param string $flag - insert или update
    * @return void
    */
    function getProductsHash()
    {
        if (!$this['id']) {
            $products = $this->getCart()->getProductItems();
            $arr = array();
            foreach($products as $uniq=>$item){
               $product  = $item['product'];
               $cartitem = $item['cartitem'];
               $arr[]    = $product['title']."_".$product['id']."_".$cartitem['offer']."_".$cartitem['amount'];
            } 
            sort($arr);
            return md5(serialize($arr));
        }else{
            $cart_data=$this->getCart()->getPriceItemsData();
            $cart_data['checkcount'] = count($cart_data['items']);
            return md5(serialize($cart_data));
        }
    }
    
    function afterWrite($flag)
    {
        $config = \RS\Config\Loader::byModule($this);    
        
        if ($flag == self::INSERT_FLAG) { //При вставке
            if($this->session_cart){
                $this->session_cart->saveOrderData(); //Создаем корзину к заказу
            }
            
            if($this->session_cart){
                //Отмечаем, что скидочный купон использован, если он был привязан к корзине
                $coupons = $this->session_cart->getCouponItems();
                foreach($coupons as $coupon) {
                    $coupon['coupon']->incrementUse();
                }
            }
            
            if (!$config['use_generated_order_num']) {
            //Устнавливаем номер заказа, равный ID заказа, в случае, если выключена опция генерации
                \RS\Orm\Request::make()
                    ->update($this)
                    ->set('order_num = id')
                    ->where(array('id' => $this['id']))
                    ->exec();
                $this['order_num'] = $this['id'];
            }
            
            //Уменьшаем количество товаров, учавствующих в заказе.
            if ($config['check_quantity']) {
                $this->decProductsNum();
            }
            
            if (empty($this['disable_checkout_notice'])) { //Если не стоит запрет на отправку уведомлений
                
                //Отправляем уведомление покупателю
                $notice = new \Shop\Model\Notice\CheckoutUser();
                $notice->init($this);
                \Alerts\Model\Manager::send($notice);  
                
                $site_config = \RS\Config\Loader::getSiteConfig();
                if (!empty($site_config['admin_email']) || !empty($site_config['admin_phone'])){ //Если указан E-mail администратора
                    //Отправляем уведомление администратору
                    $notice = new \Shop\Model\Notice\CheckoutAdmin();
                    $notice->init($this);
                    \Alerts\Model\Manager::send($notice);  
                }
            }         
        }
        
        
        
        
        if ($flag == self::UPDATE_FLAG){
            //Уменьшаем количество товаров, учавствующих в заказе, если сменился склад
            if ($config['check_quantity'] && isset($this['back_warehouse'])) {
                $this->decProductsNum();
            }
        }
        
        if ($flag == self::UPDATE_FLAG && $this['notify_user'] && $this->canUserNotify()) {
            //Отправляем уведомление пользователю об изменении заказа
            $notice = new \Shop\Model\Notice\OrderChange();
            $notice->init($this);
            \Alerts\Model\Manager::send($notice);
        } 
        
        //Выполняем действия с доставками и оплатами, если у этого типа доставок и оплат поддерживаются такие действия и включён флаг на разрешение
        if ($this['delivery_new_query']){ //Если доставке нужно делать запрос при создании или редактировании заказа
            $delivery_type = $this->getDelivery()->getTypeObject();
            $delivery_type->onOrderCreate($this, $this->getAddress());
        }
        
        if ($this['payment_new_query']){ //Если оплате нужно делать запрос при создании или редактировании заказа
            $payment_type = $this->getPayment()->getTypeObject();
            $payment_type->onOrderCreate($this, $this->getAddress()); 
        }    
        
        //Посмотрим, есть ли адреса заказов, которые были не присвоины и новому идентификатору
        if ($this['_tmpid']<0){
            $address_api = new \Shop\Model\AddressApi();
            $address_api->setFilter('order_id', $this['_tmpid']);
            $address_list = $address_api->getList();
            
            if ($address_list){
                foreach ($address_list as $address){
                    $address['order_id'] = $this['id'];
                    $address->update(); 
                }
            }
        }
        
        \RS\Event\Manager::fire('order.change', array('order_before' => $this->this_before_write, 'order' => $this));
    }         
    
    /**
    * Функция срабатывает после загрузки объекта
    * 
    */
    function afterObjectLoad()
    {
        $this['extra'] = @unserialize($this['_serialized']);
        $this['userfields_arr'] = @unserialize($this['userfields']);
    }
    
    /**
    * Возвращает true, если в заказе произошли изменения, о которых следует сообщить пользователю
    * 
    * @return bool
    */
    function canUserNotify()
    {
        $changed = false;
        
        if ($this->before_this !== null) {
            $old_order = $this->before_this;
            $changed =  (float)$this['totalcost'] != (float)$old_order['totalcost']  //Проверяем сумму заказа
                        || ($old_order['status'] != $this['status'])                 //Проверяем статус
                        || ($old_order['cart_md5'] != md5(serialize($this->getCart()->getPriceItemsData()))) //Проверяем состав товаров
                        || ($this->before_address->getLineView() != $this->getAddress()->getLineView()) //Проверяем адрес доставки
                        || ($old_order['delivery'] != $this['delivery'])             //Проверяем способ доставки
                        || ($old_order['contact_person'] != $this['contact_person']) //Проверяем контактное лицо
                        || ($old_order['warehouse'] != $this['warehouse'])           //Проверяем склад
                        || ($old_order['payment'] != $this['payment'])               //Проверяем способ оплаты
                        || ($old_order['user_text'] != $this['user_text'])           //Проверяем текст для пользователя
                        || ($old_order['is_payed'] != $this['is_payed']);            //Проверяем флаг оплаты
        }
        
        return $changed;
    }
    
    /**
    * Привязывает корзину к заказу
    * 
    * @param \Shop\Model\Cart $cart - загруженный объект корзины в режиме PREORDER или EMPTY
    * @return Order
    */
    function linkSessionCart(\Shop\Model\Cart $cart)
    {
        $this['basket'] = serialize($cart);
        return $this;
    }
    
    /**
    * Сохраняет параметры валюты, в которой оформляется заказ
    * 
    * @param \Catalog\Model\Orm\Currency $currency
    * @return Order
    */
    function setCurrency(\Catalog\Model\Orm\Currency $currency)
    {
        $this['currency'] = $currency['title'];
        $this['currency_ratio'] = $currency['ratio'];
        $this['currency_stitle'] = $currency['stitle'];        
        return $this;
    }

    /**
    * Проверяет наличие всех товаров в корзине
    */
    function checkProductsNum()
    {
        $num_error = false;
        if(!$this['basket']) return true;
        $bitems = $this->getCart()->getProductItems(false);
        
        
        foreach ($bitems as $n => $item) {
            $real_num = $item['product']->getNum($item['cartitem']['offer']); 
            if ($item['cartitem']['amount'] > $real_num) {
                $num_error = true;
                $bitems[$n]['product']['num'] = $real_num;
            }
        }
        if ($num_error) {
            return t('Извините, некоторых товаров уже нет в наличии');
        }
        return true;
    }    
    
    
    /**
    * Увеличить количество остатков на величину заказа
    * 
    * @param integer|boolean $warehouse - id склада на который будет возвращены количества товаров
    */
    function incProductsNum($warehouse = false)
    {
        $warehouse = $warehouse ? $warehouse : (int)$this['warehouse']; //Склад для возврата
        $bitems = $this->getCart()->getProductItems();
        foreach ($bitems as $item) {
            $product = new Product($item['product']['id']);
            $product->incrementNum($item['cartitem']['offer'], (int)$item['cartitem']['amount'], $warehouse);
        }
    }
    
    /**
    * Уменьшить количество остатков на величину заказа
    */
    function decProductsNum()
    {
        $bitems = $this->getCart()->getProductItems();
        foreach ($bitems as $item) {
            $product = new Product($item['product']['id']);
            $product->decrementNum($item['cartitem']['offer'], (int)$item['cartitem']['amount'], (int)$this['warehouse']);
        }
    }
    
    
    /**
    * Возвращает экземпляр класса текущей корзины
    * @return Order
    */
    public static function currentOrder()
    {
        $order = new self();
        $order->getFromSession();
        return $order;
    }
    
    /**
    * Загружает объект данными из сессии. 
    * После вызова данного метода, любые изменения в объект будут сохраняться в сессию
    * 
    * @return void
    */
    public function getFromSession() 
    {
        if (!isset($_SESSION[self::ORDER_SESS_VAR])) {
            $_SESSION[self::ORDER_SESS_VAR] = array();
        }
        $this->_values = &$_SESSION[self::ORDER_SESS_VAR];
    }
    
    
    /**
    * Возвращает поля, которые удовлетворяют условиям condition.
    * Условия задают в каком случае поля должны запрашиваться и проверяться из POST
    * 
    * @return array
    */
    function useFields($post)
    {
        $result = array();
        foreach($this->getProperties() as $key=>$property) {
            $rule = isset($property->condition) ? $property->condition : null;
            $include = true;            
            if ($rule) {
                foreach($rule as $field => $need) {
                    if (!isset($post[$field])) {
                        $include = false;
                    } else {
                        if (is_array($need)) {
                            if (!in_array($post[$field], $need)) $include = false;
                        } else {
                            if ($post[$field] != $need) $include = false;
                        }
                    }
                }
            }
            if ($include) $result[] = $key;
        }
        return $result;        
    }    
    
    
    
    /**
    * Возвращает объект, управляющий дополнительными полями, заданными в настройках модуля
    * 
    * @return \RS\Config\UserFieldsManager
    */
    function getFieldsManager()
    {
        $order_fields_manager  = \RS\Config\Loader::byModule($this)->getUserFieldsManager();
        $order_fields_manager->setErrorPrefix('orderfield_');
        $order_fields_manager->setArrayWrapper('userfields_arr');
        
        $data = @unserialize($this['userfields']);
       
        if (!empty($data)) $order_fields_manager->setValues($data);
        
        return $order_fields_manager;
    }    
    
    /**
    * Возвращает список регионов в стране
    * 
    */
    public static function regionList()
    {
        $_this = self::currentOrder();
        $parent = $_this['addr_country_id'];
        $api = new \Shop\Model\RegionApi();
        if ($parent<1) {
            $countries = $api->countryList();
            $array_countries_keys = array_keys($countries);
            if (count($countries)) $parent = reset($array_countries_keys);
        }
        if ($parent>0) {
            $api->setFilter('parent_id', $parent);
            $regions = $api->getAssocList('id', 'title');
        } else {
            $regions = array();
        }
        return $regions;
    }
    
    /**
    * Возвращает объект адреса доставки
    * 
    * @return \Shop\Model\Orm\Address
    */
    function getAddress($cache = true)
    {
        if ($this->address === null || !$cache) {
            $this->address = new Address($this['use_addr']);
        }
        return $this->address;
    }
    
    /**
    * Устанавливает объект адреса доставки, в случае если адрес доставки еще не существует в БД
    * 
    * @param Address $address
    * @return Order
    */
    function setAddress(Address $address)
    {
        $this->address = $address;
        return $this;
    }
    
    
    /**
    * Возращает вес заказа в граммах
    * 
    * @return float
    */
    function getWeight()
    {
        return $this->getCart()->getTotalWeight();
    }
    
    /**
    * Возвращает объект способа доставки
    * 
    * @return Delivery
    */
    function getDelivery()
    {
        return new Delivery($this['delivery']);
    }    
    
    /**
    * Возвращает объект способа оплаты
    * 
    * @return Payment
    */
    function getPayment()
    {
        return new Payment($this['payment'], true, $this);
    }
    
    /**
    * Возвращает объект выбранного склада
    * 
    * @return \Catalog\Model\Orm\WareHouse
    */
    function getWarehouse()
    {
        return new \Catalog\Model\Orm\WareHouse($this['warehouse']);
    }    
    
    /**
    * Возвращает стоимсть доставки для текущего заказа и заданного типа доставки
    * 
    * @param Delivery $delivery
    * @return string
    */
    function getDeliveryCostText(Delivery $delivery)
    {
        return $delivery->getDeliveryCostText($this, $this->getAddress());
    }
    
    function getDeliveryExtraText(Delivery $delivery)
    {
        return $delivery->getDeliveryExtraText($this, $this->getAddress());
    }

    /**
    * Применяет валюту заказа к заданной цене
    * 
    * @param float $price
    * @return double
    */
    function applyMyCurrency($price)
    {
        return round($price * $this['currency_ratio'], 2);
    }
    
    /**
    * Возвращает валюту, в которой был оформлен заказ
    * 
    * @return \Catalog\Model\Orm\Currency
    */
    function getMyCurrency()
    {
        if (self::$currency === null) {
            self::$currency = \Catalog\Model\CurrencyApi::getByUid($this['currency']);
            if (!self::$currency) {
                self::$currency = \Catalog\Model\CurrencyApi::getBaseCurrency();
            }
        }
        return self::$currency;
    }
    
    /**
    * Возвращает пользователя, оформившего заказ
    * 
    * @return \Users\Model\Orm\User
    */
    function getUser()
    {
        if ($this['user_id']>0){
            return new \Users\Model\Orm\User($this['user_id']);
        }
        $user = new \Users\Model\Orm\User();
        $fio = explode(" ", $this['user_fio']);
        if (isset($fio[0])){
            $user['surname'] = $fio[0];
        }
        if (isset($fio[1])){
            $user['name'] = $fio[1];
        }
        if (isset($fio[2])){
            $user['midname'] = $fio[2];
        }
        $user['e_mail'] = $this['user_email'];
        $user['phone']  = $this['user_phone'];
        return $user;
    }
    
    /**
    * Возвращает объект с позициями оформленного заказа
    * 
    * @return \Shop\Model\Cart
    */
    function getCart()
    {
        if ($this['id']) {
            if ($this->order_cart === null) {
                $this->order_cart = \Shop\Model\Cart::orderCart($this);
            }
            return $this->order_cart;            
        } else {
            if ($this->session_cart === null) {
                $this->session_cart = @unserialize($this['basket']);
            }
            if ($this->session_cart){
               $this->session_cart->setOrder($this);    
            }          
            return $this->session_cart;
        }
    }
    
    /**
    * Возможно ли редактирование заказа. 
    * Возвращает false если были удалены налоги либо скидки, идентфикаторы которых присутсвуют в этом заказе
    * 
    * @return bool
    */
    function canEdit()
    {
        // Проверям, не удалены ли налоги
        $items   = $this->getCart()->getCartItemsByType(\Shop\Model\Cart::TYPE_TAX);
        foreach($items as $one){
            $obj = new \Shop\Model\Orm\Tax;
            if(!$obj->exists($one['entity_id'])){
                return false;
            }
        }

        // Проверям, не удалены ли скидочные купоны
        $items   = $this->getCart()->getCartItemsByType(\Shop\Model\Cart::TYPE_COUPON);
        foreach($items as $one){
            $obj = new \Shop\Model\Orm\Discount;
            if(!$obj->exists($one['entity_id'])){
                return false;
            }
        }
        
        return true;
    }
    
    /**
    * Возвращает объект статуса заказа
    * 
    * @return UserStatus
    */
    function getStatus()
    {
        return new UserStatus($this['status']);
    }
    
    /**
    * Возвращает общую стоимость заказа 
    * 
    * @param bool $format - Если true, то стоимость будет отформатирована
    * @param bool $use_currency - Если true, то стоимость будет возвращена, в валюте в которой оформлялся заказ
    * @return float
    */
    function getTotalPrice($format = true, $use_currency = false)
    {
        $price = $this['totalcost'];
        if ($use_currency) {
            $price = $this->applyMyCurrency($price);
        }
        
        if ($format) {
            $currency = $use_currency ? $this['currency_stitle'] : \Catalog\Model\CurrencyApi::getBaseCurrency()->stitle;
            $price = \RS\Helper\CustomView::cost($price, $currency);
        }
        return $price;
    }
    
    /**
    * Возвращает список из базовой валюты и валюты в которой оформлен заказ
    * 
    * @return array
    */
    function getAllowCurrencies()
    {
        $base_currency = \Catalog\Model\CurrencyApi::getBaseCurrency();
        $result = array(
            '0' => $base_currency->title
        );
        
        if ($base_currency->title == $this['currency']) {
            $my = 0;
        } else {
            $result = array('1' => $this['currency']) + $result;
            $my = 1;
        }
        
        $result[$my] .= t(' (заказ оформлен в этой валюте)');
        return $result;
    }
    
    /**
    * Возвращает дополнительные пары ключ => значение для отображения в админ. панели в разделе "Информация о заказе"
    * 
    * @return array
    */
    function getExtraInfo()
    {
        $data = $this['extra'];
        if (isset($data['extrainfo']) && is_array($data['extrainfo'])) {  
            return $data['extrainfo'];
        }
        return array();
    }
    
    /**
    * Добавляет дополнительную информацию к заказу
    * 
    * @param string $title - Название информации
    * @param mixed $value - Значение
    * @param mixed $data - доп. сведения (если есть)
    * @param mixed $key - уникальный идентификатор информации
    * 
    * @return Order
    */
    function addExtraInfoLine($title, $value, $data = null, $key = null)
    {
        $extra = $this['extra'];
        $item = array(
            'title' => $title,
            'value' => $value,
            'data' => $data
        );
        
        if ($key === null) {
            $extra['extrainfo'][] = $item;
        } else {
            $extra['extrainfo'][$key] = $item;
        }
        $this['extra'] = $extra;
        $this['_serialized'] = serialize($extra);
        return $this;
    }
    
    /**
    * Добавляет в скрытую(которая не будет выводится) секцию с данными
    * ваши данные по ключу
    * 
    * @param string $key  - 
    * @param mixed $value - 
    * @return \Shop\Model\Orm\Order
    */
    function addExtraKeyPair($key, $value)
    {
        $extra = $this['extra'];
        $extra['extrakeypair'][$key] = $value;
        
        $this['extra'] = $extra;
        $this['_serialized'] = serialize($extra);        
        return $this;
    }
    
    /**
    * Возвращет данные из секции "extrakeypair"
    * 
    * @param string $key - ключ в секции extrakeypair, если не указан, то возвращает всю секцию
    * @return mixed
    */
    function getExtraKeyPair( $key = null )
    {
       $extra = $this['extra'];
       if (!isset($extra['extrakeypair'])) {
           return array();
       } 
       if ($key === null) {
           return $extra['extrakeypair'];
       }else{
           return $extra['extrakeypair'][$key];
       } 
    }
    
    /**
    * Возвращает список объектов для печати текущего заказа
    * 
    * @return array
    */
    function getPrintForms()
    {
        return \Shop\Model\PrintForm\AbstractPrintForm::getList();
    }
    
    /**
    * Возвращает объект компании(с реквизитами), которая поставляет услуги для данного заказа
    * 
    * @return \Shop\Model
    */
    function getShopCompany()
    {
        if (($company = $this->getPayment()->getTypeObject()->getCompany()) == false) {
            $company = new Company();
            $company->getFromArray( \RS\Config\Loader::getSiteConfig($this['site_id'])->getValues() );            
        }
        return $company;
    }
    
    /**
    * Возвращает true если для этого заказа возможна online-оплата
    * 
    */
    function canOnlinePay()
    {
        if(!$this->getPayment()->getTypeObject()->canOnlinePay()){ 
            return false;
        }
        
        if($this->getStatus()->type != \Shop\Model\Orm\UserStatus::STATUS_WAITFORPAY){
            return false;
        }
        
        if($this->is_payed){
            return false;
        }
        
        return true;
    }
    
    /**
    * Возвращайет URL для оплаты заказа в случае выбора online способа оплаты
    * 
    * @return string
    */
    function getOnlinePayUrl()
    {
        $router = \RS\Router\Manager::obj();
        return $router->getUrl('shop-front-onlinepay', array("Act" => "doPay", "order_id" => $this['order_num']));
    }
    
    /**
    * Возвращает список файлов, прикрепленных к заказу
    * 
    * @param string | array $access - уровень доступа
    * @param bool $include_product_files - если true, то в результатах будет выведены и список файлов товаров, 
    * доступных после оплаты
    * @return \Files\Model\Orm\File[]
    */
    function getFiles($access = array('visible', 'afterpay'), $include_product_files = true)
    {
        $result = array();
        if ($this['id'] && \RS\Module\Manager::staticModuleExists('files')) {
            $file_api = new \Files\Model\FileApi();            
            if (!is_array($access)) {
                $access = (array)$access;
            }
            
            if (!$this['is_payed'] && in_array('afterpay', $access)) {
                $access = array_diff($access, array('afterpay'));
            }
            
            if ($access) {
                //Получаем файлы заказа
                $file_api->setFilter(array(
                    'link_type_class' => 'files-shoporder',
                    'link_id' => $this['id']
                ));
                $file_api->setFilter('access', $access, 'in');
                $result = $file_api->getList();
            }
            
            if ($include_product_files && in_array('afterpay', $access)) {
                //Получаем файлы товаров
                $cartitems = $this->getCart()->getCartItemsByType(\Shop\Model\Cart::TYPE_PRODUCT);
                $ids = array();
                foreach($cartitems as $cartitem) {
                    $ids[] = $cartitem['entity_id'];
                }
                
                if ($ids) {
                    $file_api->clearFilter();
                    $file_api->setFilter(array(
                        'link_type_class' => 'files-catalogproduct',
                        'access' => 'afterpay'
                    ));
                    $file_api->setFilter('link_id', $ids, 'in');
                    $result = array_merge($result, $file_api->getList());
                }
            }
        }
        return $result;
    }

}
