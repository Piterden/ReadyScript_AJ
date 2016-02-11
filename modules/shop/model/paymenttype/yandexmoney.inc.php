<?php

/**
 * ReadyScript (http://readyscript.ru)
 * 
 * @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
 * @license http://readyscript.ru/licenseAgreement/
 */
namespace Shop\Model\PaymentType;

use \RS\Orm\Type;
use \Shop\Model\Orm\Transaction;

/**
 * Способ оплаты - Яндекс.Деньги
 */
class YandexMoney extends AbstractType
{
	protected $my_sign, // Подпись сформированная мной, на основе прищедших данных
$sign_md5; // Подпись md5 транзакции.
	
	/**
	 * Возвращает название расчетного модуля (типа доставки)
	 * 
	 * @return string
	 */
	function getTitle()
	{
		return t ( 'Яндекс.Касса' );
	}
	
	/**
	 * Возвращает описание типа оплаты.
	 * Возможен HTML
	 * 
	 * @return string
	 */
	function getDescription()
	{
		return t ( 'Оплата через агрегатор платежей "Яндекс.Деньги"' );
	}
	
	/**
	 * Возвращает идентификатор данного типа оплаты.
	 * (только англ. буквы)
	 * 
	 * @return string
	 */
	function getShortName()
	{
		return 'yandexmoney';
	}
	
	/**
	 * Возвращает true, если необходимо использовать
	 * POST запрос для открытия страницы платежного сервиса
	 * 
	 * @return bool
	 */
	function isPostQuery()
	{
		return true;
	}
	
	/**
	 * Возвращает ORM объект для генерации формы или null
	 * 
	 * @return \RS\Orm\FormObject | null
	 */
	function getFormObject()
	{
		$properties = new \RS\Orm\PropertyIterator ( array('testmode' => new Type\Integer ( array('maxLength' => 1,'description' => t ( 'Тестовый режим' ),'checkboxview' => array(1,0 
		) 
		) ),'shop_id' => new Type\Integer ( array('description' => t ( 'Id магазина (ShopId)' ) 
		) ),'shop_article_id' => new Type\Integer ( array('description' => t ( 'Id товара' ),'hint' => t ( 'shopArticleId необходимо задать тогда, когда используется несколько идентификаторов товара
                <br/>Выдаётся оператором Yandex.
                <br/>По умолчанию - пустое поле' ) 
		) ),'scid' => new Type\Integer ( array('description' => t ( 'Номер витрины магазина(scid)' ) 
		) ),'password' => new Type\String ( array('description' => t ( 'Пароль магазина' ),'length' => 500 
		) ),'payment_type' => new Type\String ( array('description' => t ( 'Тип метода оплаты' ),'length' => 10,'listFromArray' => array(array('PC' => 'Яндекс.Деньги','AC' => 'Банковская карта','GP' => 'Терминал приёма платежей','MC' => 'Мобильный телефон','WM' => 'WebMoney','SB' => 'Оплата через Сбербанк','AB' => 'Оплата через Альфа-Клик','MA' => 'Оплата через MasterPass','PB' => 'Оплата через интернет-банк Промсвязьбанка','QW' => 'Оплата через QIWI Wallet','KV' => 'Оплата через КупиВкредит (Тинькофф Банк)','QP' => 'Оплата через сервис Доверительный платеж (Куппи.ру)' 
		) 
		),'template' => '%shop%/form/payment/yandexmoney/payment_type.tpl' 
		) ),'category_code' => new Type\Integer ( array('description' => t ( 'Характеристика отвечающая за категорию товаров по версии Яндекс для банков<br/>
                Смотрите <a href="https://money.yandex.ru/i/forms/types_of_products.xls"></a>' ),'maxLength' => 11,'visible' => false,'list' => array(array('\Catalog\Model\PropertyApi','staticSelectList' 
		),true 
		) 
		) ),'cps_provider' => new Type\String ( array('description' => t ( 'Провайдер для терминалов' ),'length' => 10,'listFromArray' => array(array(0 => 'Не выбрано','SVZNY' => 'Связной','EURST' => 'Евросеть','OTHER' => 'Остальные сети' 
		) 
		),'template' => '%shop%/form/payment/yandexmoney/cps_provider.tpl' 
		) ),

		'__help__' => new Type\Mixed ( array('description' => t ( '' ),'visible' => true,'template' => '%shop%/form/payment/yandexmoney/help.tpl' 
		) ) 
		) );
		
		return new \RS\Orm\FormObject ( $properties );
	}
	
	/**
	 * Возвращает true, если данный тип поддерживает проведение платежа через интернет
	 * 
	 * @return bool
	 */
	function canOnlinePay()
	{
		return true;
	}
	
	/**
	 * Возвращает URL для перехода на сайт сервиса оплаты
	 * 
	 * @param Transaction $transaction
	 * @return string
	 */
	function getPayUrl(Transaction $transaction)
	{
		$shp_item = 0;
		$inv_id = $transaction->id;
		$out_summ = round ( $transaction->cost, 2 );
		$inv_desc = $transaction->reason;
		
		// Данные магазина
		$mrh_shop_id = $this->getOption ( 'shop_id' ); // Id магазина
		$mrh_shopart_id = $this->getOption ( 'shop_article_id' ); // Id товара
		$mrh_scid = $this->getOption ( 'scid' ); // Номер витрины магазина
		$payment_type = $this->getOption ( 'payment_type' ); // Вид оплаты
		$cps_provider = $this->getOption ( 'cps_provider' ); // Провайдер терминалов
		                                                    
		// Данные плательщика
		$user_id = $transaction->user_id;
		$user = new \Users\Model\Orm\User ();
		
		$user->load ( $user_id );
		$cps_email = $user['e_mail'];
		
		// Сведения о маршрутах приёма информации
		$router = \RS\Router\Manager::obj ();
		
		$result = $router->getUrl ( 'shop-front-onlinepay', array('Act' => 'result','PaymentType' => $this->getShortName () 
		), true );
		
		$success = $router->getUrl ( 'shop-front-onlinepay', array('Act' => 'success','inv_id' => $inv_id,'PaymentType' => $this->getShortName () 
		), true );
		
		$fail = $router->getUrl ( 'shop-front-onlinepay', array('Act' => 'fail','inv_id' => $inv_id,'PaymentType' => $this->getShortName () 
		), true );
		
		$url = "https://money.yandex.ru/eshop.xml";
		
		if ($this->getOption ( 'testmode' )) {
			$url = "https://demomoney.yandex.ru/eshop.xml";
		}
		
		$params = array();
		$params['shopId'] = $mrh_shop_id;
		if ($mrh_shopart_id) $params['shopArticleId'] = $mrh_shopart_id; // Id товара
		$params['scid'] = $mrh_scid;
		
		$params['sum'] = $out_summ;
		$params['customerNumber'] = $user_id;
		if ($transaction->order_id) {
			$params['orderNumber'] = $transaction->getOrder ()->order_num; // Получаем номер заказа
		}
		
		$params['shopSuccessURL'] = $success;
		$params['shopFailURL'] = $fail;
		$params['paymentType'] = $payment_type;
		if ($cps_provider) {
			$params['cps_provider'] = $cps_provider;
		}
		$params['cps_email'] = $cps_email;
		
		$params['inv_id'] = $inv_id;
		
		// Если это купи в кредит, то надо дописать некоторые дополнительные параметры
		if ($this->getOption ( 'payment_type' ) == 'KV') {
			// мы платим не как пополнение лицевого счёт
			if ($transaction->order_id) {
				$params = $this->addKVAdditionalOptions ( $transaction, $params );
			} else { // Если всё же поставили оплату, то выведем ошибку
				echo t ( "Вид оплаты КупиВКредит от Yandex невозможно использовать для пополнения счёта" );
				exit ();
			}
		}
		
		$this->addPostParams ( $params ); // Добавим пост параметры
		
		return $url;
	}
	
	/**
	 * Добавление дополнительных сведений для КупиВКредит
	 * 
	 * @param Transaction $transaction - текущая транзакция
	 * @param array $params
	 * @return array
	 */
	function addKVAdditionalOptions(Transaction $transaction, $params = array())
	{
		$params['seller_id'] = $params['shopId']; // seller_id Равен ShopId
		$order = $transaction->getOrder ();
		// А также добавим сведения о товарах
		$cart = $order->getCart ();
		$products = $cart->getProductItems ();
		$cartdata = $cart->getPriceItemsData ();
		$i = 0;
		foreach($products as $n => $item) {
			/**
			 *
			 * @var \Catalog\Model\Orm\Product
			 */
			$product = $item['product'];
			$product->fillProperty (); // Заполним характеристики
			$offer_title = $product->getOfferTitle ( $item['cartitem']['offer'] );
			$title = $offer_title ? $offer_title : $product['title'];
			if (mb_strlen ( $title ) > 256) { // Если превышает лимит, то обрежем
				$title = mb_substr ( $title, 0, 255 );
			}
			
			$params['category_code_' . $i] = $product->getPropertyValueById ( $this->getOption ( 'category_code' ), null, false );
			$params['goods_name_' . $i] = $title;
			$params['goods_description_' . $i] = $title;
			$params['goods_quantity_' . $i] = $item['cartitem']['amount'];
			$params['goods_cost_' . $i] = $cartdata['items'][$n]['single_cost'];
			$i ++;
		}
		
		// Добавим сумму доставки если есть
		$delivery_items = $cart->getCartItemsByType ( 'delivery' );
		if (! empty ( $delivery_items )) {
			foreach($delivery_items as $n => $item) {
				if ($item['price'] > 0) {
					$params['category_code_' . $i] = "11111";
					$params['goods_name_' . $i] = t ( 'Доставка' );
					$params['goods_description_' . $i] = t ( 'Цена за доставку' );
					$params['goods_quantity_' . $i] = 1;
					$params['goods_cost_' . $i] = $item['price'];
				}
			}
		}
		
		return $params;
	}
	
	/**
	 * Возвращает ID заказа исходя из REQUEST-параметров соотвествующего типа оплаты
	 * Используется только для Online-платежей
	 * 
	 * @return mixed
	 */
	function getTransactionIdFromRequest(\RS\Http\Request $request)
	{
		return $request->request ( 'inv_id', TYPE_INTEGER, false );
	}
	
	/**
	 * Возвращет ответ в XML Версии 1.0
	 * 
	 * @param string $action - команда от сервера
	 * @param array $params - параметры для XML
	 */
	private function sendXMLAnswer($action, $params)
	{
		$dom = new \DOMDocument ( '1.0', 'utf-8' );
		$element = $dom->createElement ( $action . "Response" );
		
		foreach($params as $key => $value) {
			$element->setAttribute ( $key, $value );
		}
		
		$dom->appendChild ( $element );
		$dom->formatOutput = true; // Для сохранения в XML
		\RS\Application\Application::getInstance ()->headers->addHeader ( 'Content-type', 'application/xml' );
		
		return $dom->saveXML ();
	}
	
	/**
	 * Проверяет подпись запроса
	 * 
	 * @param string $action - команда от сервера
	 * @param \Shop\Model\Orm\Transaction $transaction - объект транзакции
	 * @param \RS\Http\Request $request - объект запроса с значениями
	 */
	private function checkSign($action, \Shop\Model\Orm\Transaction $transaction, \RS\Http\Request $request)
	{
		$this->sign_md5 = strtoupper ( $request->request ( "md5", TYPE_STRING ) ); // md5 сервера
		
		$orderSumAmount = $request->request ( "orderSumAmount", TYPE_STRING ); // сумма заказа за вычетом комиссии Оператора
		$orderSumCPay = $request->request ( "orderSumCurrencyPaycash", TYPE_STRING ); // Код валюты для суммы, получаемой Контрагентом на р/с
		$orderSumBPay = $request->request ( "orderSumBankPaycash", TYPE_STRING ); // Код процессингового центра Оператора для суммы
		$shopId = $this->getOption ( 'shop_id' ); // Идентификатор Контрагента(Магазина)
		$invoiceId = $request->request ( "invoiceId", TYPE_STRING ); // Уникальный номер транзакции в программно-аппаратном комплексе Оператора
		$customerNumber = $request->request ( "customerNumber", TYPE_STRING ); // Идентификатор плательщика
		$shopPassword = $this->getOption ( 'password' ); // Пароль магазина
		                                                
		// Вычисление подписи
		$this->my_sign = strtoupper ( md5 ( "$action;$orderSumAmount;$orderSumCPay;$orderSumBPay;$shopId;$invoiceId;$customerNumber;$shopPassword" ) );
		
		// Проверка корректности подписи
		return $this->my_sign === $this->sign_md5;
	}
	function onResult(\Shop\Model\Orm\Transaction $transaction, \RS\Http\Request $request)
	{
		$action = $request->request ( 'action', TYPE_STRING );
		
		// Проверка подписи запроса
		if (! $this->checkSign ( $action, $transaction, $request )) {
			$exception = new ResultException ( t ( 'Не правильная подпись ответа. Невозможно идентифицировать хэш.' ), 1 );
			$exception->setResponse ( $this->sendXMLAnswer ( $action, array('performedDatetime' => date ( 'c' ),'code' => 1,'invoiceId' => $request->request ( 'invoceId', TYPE_STRING ),'shopId' => $this->getOption ( 'shop_id' ),'message' => t ( 'Не правильная подпись ответа.' ),'techMessage' => t ( 'Невозможно идентифицировать хэш.' ) 
			) ) );
			
			throw $exception;
		}
		
		if ($action == 'cancelOrder') { // Если пришёл запрос на отмену оплаты заказа
			$exception = new ResultException ( t ( 'Платёж по транзакции был отменён' ), 1 );
			$exception->setResponse ( $this->sendXMLAnswer ( $action, array('performedDatetime' => date ( 'c' ),'code' => 0,'invoiceId' => $request->request ( 'invoiceId', TYPE_STRING ),'shopId' => $this->getOption ( 'shop_id' ),'message' => t ( 'Пришлё запрос на отмену заказа.' ),'techMessage' => t ( 'Платёж по транзакции был отменён.' ) 
			) ) );
			
			throw $exception;
		}
		
		// Проверка, соответсвует ли сумма платежа сумме, сохраненной в транзакции
		if ($request->request ( 'orderSumAmount', TYPE_FLOAT ) != floatval ( $transaction->cost )) {
			$exception = new ResultException ( t ( 'Не правильная сумма платежа %0. Сумма платежа не совпадает с запрошенной изначально.', array($request->request ( 'orderSumAmount', TYPE_FLOAT ) 
			) ), 1 );
			$exception->setResponse ( $this->sendXMLAnswer ( $action, array('performedDatetime' => date ( 'c' ),'code' => 1,'invoiceId' => $request->request ( 'invoiceId', TYPE_STRING ),'shopId' => $this->getOption ( 'shop_id' ),'message' => t ( 'Не правильная сумма платежа.' ),'techMessage' => t ( 'Сумма платежа не совпадает с запрошенной изначально.' ) 
			) ) );
			
			throw $exception;
		}
		
		$result = $this->sendXMLAnswer ( $action, array('performedDatetime' => date ( 'c' ),'code' => 0,'invoiceId' => $request->request ( 'invoiceId', TYPE_STRING ),'shopId' => $this->getOption ( 'shop_id' ) 
		) );
		
		if ($action != 'paymentAviso') {
			$exception = new ResultException ( t ( 'Успешный ответ для checkOrder' ) );
			$exception->setUpdateTransaction ( false );
			$exception->setResponse ( $result );
			throw $exception; // Ответ для CheckOrder (не будет завершать транзакцию)
		} else {
			return $result; // Успешный ответ для Aviso
		}
	}
}

