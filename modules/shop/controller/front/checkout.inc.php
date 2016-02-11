<?php

/**
 * ReadyScript (http://readyscript.ru)
 * 
 * @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
 * @license http://readyscript.ru/licenseAgreement/
 */
namespace Shop\Controller\Front;

use \RS\Application\Auth as AppAuth;

/**
 * Контроллер Оформление заказа
 */
class Checkout extends \RS\Controller\Front
{
	protected $order_api, /**
	 *
	 * @var \Shop\Model\Orm\Order
	 */
	$order;
	
	/**
	 * Инициализация контроллера
	 */
	function init()
	{
		$this->app->title->addSection ( t ( 'Оформление заказа' ) );
		$this->order = \Shop\Model\Orm\Order::currentOrder ();
		$this->order_api = new \Shop\Model\OrderApi ();
		
		$this->order->clearErrors ();
		$this->view->assign ( 'order', $this->order );
	}
	function actionIndex()
	{
		$this->order->clear ();
		
		// Замораживаем объект "корзина" и привязываем его к заказу
		$frozen_cart = \Shop\Model\Cart::preOrderCart ( null );
		$frozen_cart->splitSubProducts ();
		$frozen_cart->mergeEqual ();
		
		$this->order->linkSessionCart ( $frozen_cart );
		$this->order->setCurrency ( \Catalog\Model\CurrencyApi::getCurrentCurrency () );
		
		$this->order['ip'] = $_SERVER['REMOTE_ADDR'];
		$this->order['warehouse'] = 0;
		
		$this->order['expired'] = false;
		$this->redirect ( $this->router->getUrl ( 'shop-front-checkout', array('Act' => 'address' 
		) ) );
	}
	
	/**
	 * Шаг 1.
	 * Установка адреса и контактов
	 */
	function actionAddress()
	{
		if (! $this->order->getCart ()) $this->redirect ();
		$this->app->title->addSection ( t ( 'Адрес и контакты' ) );
		$captcha_config = \RS\Config\Loader::byModule ( 'kaptcha' );
		// Добавим хлебные крошки
		$this->app->breadcrumbs->addBreadCrumb ( t ( 'Корзина' ), $this->router->getUrl ( 'shop-front-cartpage' ) )->addBreadCrumb ( t ( 'Адрес и контакты' ) );
		
		$logout = $this->url->request ( 'logout', TYPE_BOOLEAN );
		$login = $this->url->request ( 'ologin', TYPE_BOOLEAN ); // Предварительная авторизация
		
		if ($logout) {
			AppAuth::logout ();
			$this->redirect ( $this->router->getUrl ( 'shop-front-checkout', array('Act' => 'address' 
			) ) );
		}
		
		$this->order['__code']->setEnable ( false );
		if (AppAuth::isAuthorize ()) {
			$this->order['user_type'] = null;
			$this->order['__code']->setEnable ( false );
		} else {
			if ($captcha_config['enabled']) {
				$this->order['__code']->setEnable ( true );
			}
			
			if (empty ( $this->order['user_type'] )) {
				$this->order['user_type'] = 'person';
				$this->order['reg_autologin'] = 1;
			}
		}
		
		$cart_data = $this->order['basket'] ? $this->order->getCart ()->getCartData () : null;
		if ($cart_data === null || ! count ( $cart_data['items'] ) || $cart_data['has_error'] || $this->order['expired']) {
			// Если корзина пуста или заказ уже оформлен или имеются ошибки в корзине, то выполняем redirect на главную сайта
			$this->redirect ();
		}
		
		// Запрашиваем дополнительные поля формы заказа, если они определены в конфиге модуля
		$order_fields_manager = $this->order->getFieldsManager ();
		$order_fields_manager->setValues ( $this->order['userfields_arr'] );
		
		// Запрашиваем дополнительные поля формы регистрации, если они определены
		$reg_fields_manager = \RS\Config\Loader::byModule ( 'users' )->getUserFieldsManager ();
		$reg_fields_manager->setErrorPrefix ( 'regfield_' );
		$reg_fields_manager->setArrayWrapper ( 'regfields' );
		if (! empty ( $this->order['regfields'] )) $reg_fields_manager->setValues ( $this->order['regfields'] );
		
		if ($this->url->isPost ()) {
			$this->order_api->addOrderExtraDataByStep ( $this->order, 'address', $this->url->request ( 'order_extra', TYPE_ARRAY, array() ) ); // Заносим дополнительные данные
			$sysdata = array('step' => 'address' 
			);
			$work_fields = $this->order->useFields ( $sysdata + $_POST );
			
			$this->order->setCheckFields ( $work_fields );
			$this->order->checkData ( $sysdata, null, null, $work_fields );
			$this->order['userfields'] = serialize ( $this->order['userfields_arr'] );
			
			// Авторизовываемся
			if ($this->order['user_type'] == 'user' && ! $logout) {
				if (! \RS\Application\Auth::login ( $this->order['login'], $this->order['password'] )) {
					$this->order->addError ( 'Неверный логин или пароль', 'login' );
				} else {
					$this->order['user_type'] = '';
					$this->order['__code']->setEnable ( false );
				}
			}
			
			if (! $logout && ! $login) {
				
				// Проверяем пароль, если пользователь решил задать его вручную. (при регистрации)
				if (in_array ( $this->order['user_type'], array('person','company' 
				) ) && ! $this->order['reg_autologin']) {
					if (($pass_err = \Users\Model\Orm\User::checkPassword ( $this->order['reg_openpass'] )) !== true) {
						$this->order->addError ( $pass_err, 'reg_openpass' );
					}
					
					if (strcmp ( $this->order['reg_openpass'], $this->order['reg_pass2'] )) {
						$this->order->addError ( 'Пароли не совпадают', 'reg_openpass' );
					}
					
					// Сохраняем дополнительные сведения о пользователе
					$uf_err = $reg_fields_manager->check ( $this->order['regfields'] );
					if (! $uf_err) {
						// Переносим ошибки в объект order
						foreach($reg_fields_manager->getErrors () as $form => $errortext) {
							$this->order->addError ( $errortext, $form );
						}
					}
				}
				
				// Регистрируем пользователя, если нет ошибок
				if (in_array ( $this->order['user_type'], array('person','company' 
				) )) {
					
					$new_user = new \Users\Model\Orm\User ();
					$allow_fields = array('reg_name','reg_surname','reg_midname','reg_phone','reg_e_mail','reg_openpass','reg_company','reg_company_inn' 
					);
					$reg_fields = array_intersect_key ( $this->order->getValues (), array_flip ( $allow_fields ) );
					
					$new_user->getFromArray ( $reg_fields, 'reg_' );
					$new_user['data'] = $this->order['regfields'];
					$new_user['is_company'] = ( int ) ($this->order['user_type'] == 'company');
					
					if (! $new_user->validate ()) {
						foreach($new_user->getErrorsByForm () as $form => $errors) {
							$this->order->addErrors ( $errors, 'reg_' . $form );
						}
					}
					
					if (! $this->order->hasError ()) {
						if ($this->order['reg_autologin']) {
							$new_user['openpass'] = \RS\Helper\Tools::generatePassword ( 6 );
						}
						
						if ($new_user->create ()) {
							if (AppAuth::login ( $new_user['login'], $new_user['pass'], true, true )) {
								$this->order['user_type'] = ''; // Тип регитрации - не актуален после авторизации
								$this->order['__code']->setEnable ( false );
							} else {
								throw new \RS\Exception ( 'Не удалось авторизоваться под созданным пользователем.' );
							}
						} else {
							$this->order->addErrors ( $new_user->getErrorsByForm ( 'e_mail' ), 'reg_e_mail' );
							$this->order->addErrors ( $new_user->getErrorsByForm ( 'login' ), 'reg_login' );
						}
					}
				}
				
				// Если заказ без регистрации пользователя
				if ($this->order['user_type'] == 'noregister') {
					// Получим данные
					$this->order['user_fio'] = $this->request ( 'user_fio', TYPE_STRING );
					$this->order['user_email'] = $this->request ( 'user_email', TYPE_STRING );
					$this->order['user_phone'] = $this->request ( 'user_phone', TYPE_STRING );
					
					// Проверим данные
					if (empty ( $this->order['user_fio'] )) {
						$this->order->addError ( t ( 'Укажите, пожалуйста, Ф.И.О.' ), 'user_fio' );
					}
					if (! filter_var ( $this->order['user_email'], FILTER_VALIDATE_EMAIL )) {
						$this->order->addError ( t ( 'Укажите, пожалуйста, E-mail' ), 'user_email' );
					}
				}
				
				// Сохраняем дополнительные сведения
				$uf_err = $order_fields_manager->check ( $this->order['userfields_arr'] );
				if (! $uf_err) {
					// Переносим ошибки в объект order
					foreach($order_fields_manager->getErrors () as $form => $errortext) {
						$this->order->addError ( $errortext, $form );
					}
				}
				
				// Сохраняем адрес
				if (! $this->order->hasError () && $this->order['use_addr'] == 0) {
					$address = new \Shop\Model\Orm\Address ();
					$address->getFromArray ( $this->order->getValues (), 'addr_' );
					$address['user_id'] = AppAuth::getCurrentUser ()->id;
					if ($address->insert ()) {
						$this->order['use_addr'] = $address['id'];
					}
				}
				
				// Все успешно
				if (! $this->order->hasError ()) {
					$this->order['user_id'] = AppAuth::getCurrentUser ()->id;
					$this->redirect ( $this->router->getUrl ( 'shop-front-checkout', array('Act' => 'delivery' 
					) ) );
				}
			} // !logout && !login
		} // POST
		
		$user = AppAuth::getCurrentUser ();
		if (AppAuth::isAuthorize ()) {
			// Получаем список адресов пользователя
			$address_api = new \Shop\Model\AddressApi ();
			$address_api->setFilter ( 'user_id', $user['id'] );
			$address_api->setFilter ( 'deleted', 0 );
			$addr_list = $address_api->getList ();
			if (count ( $addr_list ) > 0 && $this->order['use_addr'] === null) {
				$this->order['use_addr'] = $addr_list[0]['id'];
			}
			$this->view->assign ( 'address_list', $addr_list );
		}
		
		if ($logout) {
			$this->order->clearErrors ();
		}
		
		if ($login) { // Покажем только ошибки авторизации, остальные скроем
			$login_err = $this->order->getErrorsByForm ( 'login' );
			$this->order->clearErrors ();
			if (! empty ( $login_err )) $this->order->addErrors ( $login_err, 'login' );
		}
		
		$this->order['password'] = '';
		$this->order['reg_openpass'] = '';
		$this->order['reg_pass2'] = '';
		
		$this->view->assign ( array('is_auth' => AppAuth::isAuthorize (),'order' => $this->order,'order_extra' => ! empty ( $this->order['order_extra'] ) ? $this->order['order_extra'] : array(),'user' => $user,'conf_userfields' => $order_fields_manager,'reg_userfields' => $reg_fields_manager 
		) );
		
		return $this->result->setTemplate ( 'checkout/address.tpl' );
	}
	
	/**
	 * Шаг 2.
	 * Выбор доставки
	 */
	function actionDelivery()
	{
		if ($this->getModuleConfig ()->hide_delivery) { // Если нужно проскочить шаг доставка
			$this->redirect ( $this->router->getUrl ( 'shop-front-checkout', array('Act' => 'payment' 
			) ) );
		}
		
		$this->app->title->addSection ( t ( 'Выбор доставки' ) );
		
		// Добавим хлебные крошки
		$this->app->breadcrumbs->addBreadCrumb ( t ( 'Корзина' ), $this->router->getUrl ( 'shop-front-cartpage' ) )->addBreadCrumb ( t ( 'Адрес и контакты' ), $this->router->getUrl ( 'shop-front-checkout', array('Act' => 'address' 
		) ) )->addBreadCrumb ( t ( 'Выбор доставки' ) );
		
		if ($this->order['expired'] || ! $this->order->getCart ()) $this->redirect ();
		$my_type = $this->user['is_company'] ? 'company' : 'user';
		$delivery_api = new \Shop\Model\DeliveryApi ();
		
		// Получим все зоны
		$zone_api = new \Shop\Model\ZoneApi ();
		$zones = $zone_api->getZonesByRegionId ( $this->order->getAddress ()->region_id, $this->order->getAddress ()->country_id );
		
		$delivery_api->setFilter ( 'public', 1 );
		$delivery_api->setFilter ( 'user_type', array('all',$my_type 
		), 'in' );
		$delivery_api->setZoneFilter ( $zones );
		
		$cartdata = $this->order->getCart ()->getCartData ( false );
		// Проверим условие минимальной цены
		$delivery_api->setFilter ( array(array('min_price' => 0,'|min_price:<=' => $cartdata['total'] 
		) 
		) );
		// Проверим условие максимальной цены
		$delivery_api->setFilter ( array(array('max_price' => 0,'|max_price:>' => $cartdata['total'] 
		) 
		) );
		// Проверим условие минимального количества товаров
		$delivery_api->setFilter ( array(array('min_cnt' => 0,'|min_cnt:<=' => $cartdata['items_count'] 
		) 
		) );
		$delivery_list = $delivery_api->getList ();
		
		$this->view->assign ( array('delivery_list' => $delivery_list 
		) );
		
		if ($this->url->isPost ()) {
			$this->order_api->addOrderExtraDataByStep ( $this->order, 'delivery', $this->url->request ( 'order_extra', TYPE_ARRAY, array() ) ); // Заносим дополнительные данные
			                                                                                                                              
			// Проверим параметры выбора доставки
			$sysdata = array('step' => 'delivery' 
			);
			$work_fields = $this->order->useFields ( $sysdata + $this->url->getSource ( POST ) );
			$this->order->setCheckFields ( $work_fields );
			if ($this->order->checkData ( $sysdata, null, null, $work_fields )) {
				$delivery = $this->order->getDelivery (); // Выбранная доставка
				$delivery_extra = $this->request ( 'delivery_extra', TYPE_ARRAY, false );
				if ($delivery_extra) {
					$this->order->addExtraKeyPair ( 'delivery_extra', $delivery_extra );
				}
				
				if ($delivery['class'] == 'myself') { // Если самовывоз и складов больше одного
					$this->redirect ( $this->router->getUrl ( 'shop-front-checkout', array('Act' => 'warehouses' 
					) ) );
				} else {
					$this->redirect ( $this->router->getUrl ( 'shop-front-checkout', array('Act' => 'payment' 
					) ) );
				}
			}
		}
		
		$this->view->assign ( array('order_extra' => ! empty ( $this->order['order_extra'] ) ? $this->order['order_extra'] : array() 
		) );
		
		return $this->result->setTemplate ( 'checkout/delivery.tpl' );
	}
	
	/**
	 * Шаг 2.2 Страница выбора склада откуда забирать
	 * Используется только когда складов более одного
	 * и выбран способ доставки "Самовывоз"
	 */
	function actionWarehouses()
	{
		$this->app->title->addSection ( t ( 'Выбор склада для забора товара' ) );
		
		$warehouse_api = new \Catalog\Model\WareHouseApi ();
		\RS\Event\Manager::fire ( 'order.getwarehouses', array('warehouse_api' => $warehouse_api 
		) );
		$warehouses = $warehouse_api->setFilter ( 'checkout_public', 1 )->getList ();
		
		if (count ( $warehouses ) < 2) {
			if (count ( $warehouses ) == 1) {
				// Если склад только один, то пропускаем выбор склада
				$this->order['warehouse'] = $warehouses[0]['id'];
			}
			$this->redirect ( $this->router->getUrl ( 'shop-front-checkout', array('Act' => 'payment' 
			) ) );
		}
		
		// Добавим хлебные крошки
		$this->app->breadcrumbs->addBreadCrumb ( t ( 'Корзина' ), $this->router->getUrl ( 'shop-front-cartpage' ) )->addBreadCrumb ( t ( 'Адрес и контакты' ), $this->router->getUrl ( 'shop-front-checkout', array('Act' => 'address' 
		) ) )->addBreadCrumb ( t ( 'Выбор доставки' ), $this->router->getUrl ( 'shop-front-checkout', array('Act' => 'delivery' 
		) ) )->addBreadCrumb ( t ( 'Выбор склада' ) );
		
		if ($this->order['expired'] || ! $this->order->getCart ()) $this->redirect ();
		
		$this->view->assign ( array('warehouses_list' => $warehouses 
		) );
		
		if ($this->url->isPost ()) {
			$this->order_api->addOrderExtraDataByStep ( $this->order, 'warehouses', $this->url->request ( 'order_extra', TYPE_ARRAY, array() ) ); // Заносим дополнительные данные
			$sysdata = array('step' => 'warehouses' 
			);
			$work_fields = $this->order->useFields ( $sysdata + $this->url->getSource ( POST ) );
			$this->order->setCheckFields ( $work_fields );
			if ($this->order->checkData ( $sysdata, null, null, $work_fields )) {
				$this->redirect ( $this->router->getUrl ( 'shop-front-checkout', array('Act' => 'payment' 
				) ) );
			}
		}
		
		$this->view->assign ( array('order_extra' => ! empty ( $this->order['order_extra'] ) ? $this->order['order_extra'] : array() 
		) );
		
		return $this->result->setTemplate ( 'checkout/warehouse.tpl' );
	}
	
	/**
	 * Шаг 3.
	 * Выбор оплаты
	 */
	function actionPayment()
	{
		$this->app->title->addSection ( t ( 'Выбор оплаты' ) );
		
		// Добавим хлебные крошки
		$this->app->breadcrumbs->addBreadCrumb ( t ( 'Корзина' ), $this->router->getUrl ( 'shop-front-cartpage' ) )->addBreadCrumb ( t ( 'Адрес и контакты' ), $this->router->getUrl ( 'shop-front-checkout', array('Act' => 'address' 
		) ) );
		if (! $this->getModuleConfig ()->hide_delivery) {
			$this->app->breadcrumbs->addBreadCrumb ( t ( 'Выбор доставки' ), $this->router->getUrl ( 'shop-front-checkout', array('Act' => 'delivery' 
			) ) );
		}
		$this->app->breadcrumbs->addBreadCrumb ( t ( 'Выбор оплаты' ) );
		
		if ($this->order['expired'] || ! $this->order->getCart ()) $this->redirect ();
		$my_type = $this->user['is_company'] ? 'company' : 'user';
		$pay_api = new \Shop\Model\PaymentApi ();
		$pay_api->setFilter ( 'public', 1 );
		$pay_api->setFilter ( 'user_type', array('all',$my_type 
		), 'in' );
		$pay_api->setFilter ( 'target', array('all','orders' 
		), 'in' );
		
		$this->view->assign ( array('pay_list' => $pay_api->getPaymentsList ( $this->order ) 
		) );
		
		// Найдём оплату по умолчанию, если оплата не была задана раннее
		if (! $this->order['payment']) {
			$pay_api->setFilter ( 'default_payment', 1 );
			$default_payment = $pay_api->getFirst ( $this->order );
			if ($default_payment) {
				$this->order['payment'] = $default_payment['id'];
			}
		}
		
		if ($this->getModuleConfig ()->hide_payment) { // Если нужно проскочить шаг оплата
			$this->redirect ( $this->router->getUrl ( 'shop-front-checkout', array('Act' => 'confirm' 
			) ) );
		}
		
		if ($this->url->isPost ()) {
			$this->order_api->addOrderExtraDataByStep ( $this->order, 'pay', $this->url->request ( 'order_extra', TYPE_ARRAY, array() ) ); // Заносим дополнительные данные
			$sysdata = array('step' => 'pay' 
			);
			$work_fields = $this->order->useFields ( $sysdata + $_POST );
			$this->order->setCheckFields ( $work_fields );
			if ($this->order->checkData ( $sysdata, null, null, $work_fields )) {
				$this->redirect ( $this->router->getUrl ( 'shop-front-checkout', array('Act' => 'confirm' 
				) ) );
			}
		}
		
		$this->view->assign ( array('order_extra' => ! empty ( $this->order['order_extra'] ) ? $this->order['order_extra'] : array() 
		) );
		
		return $this->result->setTemplate ( 'checkout/payment.tpl' );
	}
	
	/**
	 * Шаг 4.
	 * Подтверждение заказа
	 */
	function actionConfirm()
	{
		$this->app->title->addSection ( t ( 'Подтверждение заказа' ) );
		
		if ($this->order['expired'] || ! $this->order->getCart ()) $this->redirect ();
		
		$basket = $this->order->getCart ();
		
		// Добавим хлебные крошки
		$this->app->breadcrumbs->addBreadCrumb ( t ( 'Корзина' ), $this->router->getUrl ( 'shop-front-cartpage' ) )->addBreadCrumb ( t ( 'Адрес и контакты' ), $this->router->getUrl ( 'shop-front-checkout', array('Act' => 'address' 
		) ) );
		if (! $this->getModuleConfig ()->hide_delivery) {
			$this->app->breadcrumbs->addBreadCrumb ( t ( 'Выбор доставки' ), $this->router->getUrl ( 'shop-front-checkout', array('Act' => 'delivery' 
			) ) );
		}
		if (! $this->getModuleConfig ()->hide_payment) {
			$this->app->breadcrumbs->addBreadCrumb ( t ( 'Выбор оплаты' ), $this->router->getUrl ( 'shop-front-checkout', array('Act' => 'payment' 
			) ) );
		}
		$this->app->breadcrumbs->addBreadCrumb ( t ( 'Подтверждение заказа' ) );
		
		$this->view->assign ( array('cart' => $basket 
		) );
		
		if ($this->url->isPost ()) {
			$this->order_api->addOrderExtraDataByStep ( $this->order, 'confirm', $this->url->request ( 'order_extra', TYPE_ARRAY, array() ) ); // Заносим дополнительные данные
			
			$this->order->clearErrors ();
			if ($this->getModuleConfig ()->require_license_agree && ! $this->url->post ( 'iagree', TYPE_INTEGER )) {
				$this->order->addError ( t ( 'Подтвердите согласие с условиями предоставления услуг' ) );
			}
			
			$sysdata = array('step' => 'confirm' 
			);
			$work_fields = $this->order->useFields ( $sysdata + $_POST );
			
			$this->order->setCheckFields ( $work_fields );
			if (! $this->order->hasError () && $this->order->checkData ( $sysdata, null, null, $work_fields )) {
				$this->order['is_payed'] = 0;
				$this->order['delivery_new_query'] = 1;
				$this->order['payment_new_query'] = 1;
				
				echo '<pre>';
				var_dump ( $this->order->insert () );
				die ();
				
				// Создаем заказ в БД
				if ($this->order->insert ()) {
					$this->order['expired'] = true; // заказ уже оформлен. больше нельзя возвращаться к шагам.
					\Shop\Model\Cart::currentCart ()->clean (); // Очищаем корзиу
					$this->redirect ( $this->router->getUrl ( 'shop-front-checkout', array('Act' => 'finish' 
					) ) );
				}
			}
		}
		
		$this->view->assign ( array('order_extra' => ! empty ( $this->order['order_extra'] ) ? $this->order['order_extra'] : array() 
		) );
		
		return $this->result->setTemplate ( 'checkout/confirm.tpl' );
	}
	
	/**
	 * Шаг 5.
	 * Создание заказа
	 */
	function actionFinish()
	{
		$this->app->title->addSection ( t ( 'Заказ №%0 успешно оформлен', array($this->order->order_num 
		) ) );
		
		// Добавим хлебные крошки
		$this->app->breadcrumbs->addBreadCrumb ( t ( 'Корзина' ) )->addBreadCrumb ( t ( 'Адрес и контакты' ) )->addBreadCrumb ( t ( 'Выбор доставки' ) )->addBreadCrumb ( t ( 'Выбор оплаты' ) )->addBreadCrumb ( t ( 'Завершение заказа' ) );
		
		$this->view->assign ( array('cart' => $this->order->getCart (),'alt' => 'alt','statuses' => \Shop\Model\UserStatusApi::getStatusIdByType () 
		) );
		
		return $this->result->setTemplate ( 'checkout/finish.tpl' );
	}
	
	/**
	 * Выполняет пользовательский статический метод у типа оплаты или доставки,
	 * если таковой есть у типа доставки
	 */
	function actionUserAct()
	{
		$module = $this->request ( 'module', TYPE_STRING, 'Shop' ); // Имя модуля
		$type_obj = $this->request ( 'typeObj', TYPE_INTEGER, 0 ); // 0 - доставка (DeliveryType), 1 - оплата (PaymentType)
		$type_id = $this->request ( 'typeId', TYPE_INTEGER, 0 ); // id доставки или оплаты
		$class = $this->request ( 'class', TYPE_STRING, false ); // Класс для обращения
		$act = $this->request ( 'userAct', TYPE_STRING, false ); // Статический метод который нужно вызвать
		$params = $this->request ( 'params', TYPE_ARRAY, array() ); // Дополнительные параметры для передачи в метод
		
		if ($module && $act && $class) {
			$typeobj = "DeliveryType";
			if ($type_obj == 1) {
				$typeobj = "PaymentType";
			}
			
			$delivery = '\\' . $module . '\Model\\' . $typeobj . '\\' . $class;
			$data = $delivery::$act ( $this->order, $type_id, $params );
			
			if (! $this->order->hasError ()) {
				return $this->result->setSuccess ( true )->addSection ( 'data', $data );
			} else {
				return $this->result->setSuccess ( false )->addEMessage ( $this->order->getErrorsStr () );
			}
		} else {
			return $this->result->setSuccess ( false )->addEMessage ( 'Не установлен метод или объект доставки или оплаты' );
		}
	}
	
	/**
	 * Удаление адреса при оформлении заказа
	 */
	function actionDeleteAddress()
	{
		$id = $this->url->request ( 'id', TYPE_INTEGER, 0 ); // id адреса доставки
		if ($id) {
			$address = new \Shop\Model\Orm\Address ( $id );
			if ($address['user_id'] == $this->user['id']) {
				$address['deleted'] = 1;
				$address->update ();
				return $this->result->setSuccess ( true );
			}
		}
		return $this->result->setSuccess ( false );
	}
}
