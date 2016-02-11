<?php

namespace DaData\Config;

use \RS\Orm\Type, \RS\Html\Toolbar\Button;

/**
 * Класс предназначен для объявления событий, которые будет прослушивать данный модуль и обработчиков этих событий.
 */
class Handlers extends \RS\Event\HandlerAbstract
{
	function init()
	{
		$this->bind ( 'controller.afterexec.shop-front-checkout' )->bind ( 'controller.afterexec.onepageorder-front-checkout' );
	}
	
	/**
	 * Подвешивает на фронт контроллер добавление необходимых скриптов на адрес
	 * 
	 * @param \RS\Controller\Result\Standart $controller - Объект результата контроллера
	 */
	public static function controllerAfterExecShopFrontCheckout(\RS\Controller\Result\Standart $controller)
	{
		self::addDaDataInit ( $controller );
	}
	
	/**
	 * Подвешивает на фронт контроллер добавление необходимых скриптов на оформление заказа на одной странице
	 * 
	 * @param \RS\Controller\Result\Standart $controller - Объект результата контроллера
	 */
	public static function controllerAfterExecOnePageOrderFrontCheckout(\RS\Controller\Result\Standart $controller)
	{
		self::addDaDataInit ( $controller );
	}
	
	/**
	 * Подвешивает на фронт контроллер добавление необходимых скриптов
	 * 
	 * @param \RS\Controller\Result\Standart $controller - Объект результата контроллера
	 */
	public static function addDaDataInit(\RS\Controller\Result\Standart $controller)
	{
		// Получим конфиг модуля
		$config = \RS\Config\Loader::byModule ( 'dadata' );
		
		// Если API ключ не введён, то ничего не добавляем
		if (empty ( $config['api_key'] )) {
			return;
		}
		
		// Получим переменные и добавим JS и CSS API DaData
		$vars = $controller->getTemplateVars ();
		/**
		 *
		 * @var \RS\Application\Application
		 */
		$app = $vars['app'];
		$app->addCss ( '%dadata%/dadata.css', 'dadatacss' );
		$app->addJs ( 'http://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxtransport-xdomainrequest/1.0.1/jquery.xdomainrequest.min.js', 'dadatajstransport', BP_ROOT );
		$app->addJs ( ! empty ( $config['dadata_api_js'] ) ? $config['dadata_api_js'] : 'https://dadata.ru/static/js/lib/jquery.suggestions-15.10.min.js', 'dadatajs', BP_ROOT );
		
		// Добавим JS с общими функциями
		$app->addJs ( '%dadata%/dadata.js', 'dadatamainjs' );
		
		// Добавим переменные в глобальный массив
		$app->addJsVar ( 'dadata_config', $config->getValues () );
		
		// Если включена опция получить город по IP, сделаем запрос и добавим переменные
		if ($config['ip_check_city']) {
			$ip = $_SERVER['REMOTE_ADDR'];
			$app->addJsVar ( 'dadata_ip', $ip ); // Добавим текущий IP для проверки
			if ($ip != '127.0.0.1' && ! empty ( $config['api_key'] )) {
				$api = new \DaData\Model\Api ();
				$result = $api->requestCityByIp ( $ip, $config['api_key'] );
				if (isset ( $result['location']['data']['city'] )) {
					$app->addJsVar ( 'dadata_ip_city', $result['location']['data']['city'] );
				}
				if (isset ( $result['location']['data']['region'] ) && $result['location']['data']['region_type_full']) {
					$app->addJsVar ( 'dadata_ip_region', $result['location']['data']['region'] . " " . $result['location']['data']['region_type_full'] );
					$app->addJsVar ( 'dadata_ip_region_text', $result['location']['data']['region'] );
				}
			}
		}
	}
}