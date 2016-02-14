<?php
/**
 * Класс доставки компании IMLogistic.
 *
 * @copyright Copyright (c) YellowMarkerWS. Ефремов Денис <efremov.a.denis@gmail.com> 2015.
 */

namespace Imldelivery\Model\DeliveryType;

use \RS\Orm\Type;

class Iml extends \Shop\Model\DeliveryType\AbstractType
{

    const
    // Used API Urls
            URL_API_GETPRICE = 'http://api.iml.ru/json/GetPrice',
            URL_HELP_SD_LIST = 'http://api.iml.ru/list/sd',
            URL_HELP_SD_REGIONS = 'http://list.iml.ru/SelfDeliveryRegions',
            URL_HELP_SERVICES = 'http://list.iml.ru/service',
            // Учетные данные
            // API_LOGIN = '07308',
            // API_PASS = 'TAaB4myF',
            // Основное API
            URL_API_STATUS = 'http://api.iml.ru/json/GetStatuses',
            URL_API_ORDERSLIST = 'http://api.iml.ru/json/GetOrders',
            URL_API_CREATEORDER = 'http://api.iml.ru/json/CreateOrder',
            // Справочные таблицы для API
            URL_HELP_DELIVERYSTATUS = 'http://api.iml.ru/list/deliverystatus?type=json',
            URL_HELP_ORDERSTATUS = 'http://api.iml.ru/list/orderstatus?type=json',
            URL_HELP_REGION = 'http://api.iml.ru/list/region?type=json',
            // URL_HELP_SERVICES           =   'http://api.iml.ru/list/service?type=json',
            // Остальные справочники
            URL_LOCATION = 'http://list.iml.ru/Location?type=json',
            URL_ZONE = 'http://list.iml.ru/zone?type=json',
            URL_EXCEPTIONSERVICEREGION = 'http://list.iml.ru/ExceptionServiceRegion',
            URL_POSTDELIVERYLIMIT = 'http://list.iml.ru/PostDeliveryLimit?type=json',
            URL_REGION = 'http://list.iml.ru/region?type=json',
            URL_SELFDELIVERY = 'http://list.iml.ru/sd?type=json',
            URL_STATUS = 'http://list.iml.ru/status?type=json',
            URL_POSTRATEZONE = 'http://list.iml.ru/PostRateZone?type=json',
            URL_POSTCODE = 'http://list.iml.ru/PostCode?type=json',
            URL_CALENDAR = 'http://list.iml.ru/calendar?type=json',
            URL_SERVICE = 'http://list.iml.ru/service?type=json',
            SD_SERVICE = 'С24,С24КО';

    //protected
    //$service_id            = null, // Текущая услуга
    //$region_id_from        = null; // Услуги доставки IML
    //$carries          = null; // Агрегаторы доставки существующие в sheepla

    private $cache_api_requests = array(); // Кэш запросов к серверу IML

    /**
     * Возвращает название расчетного модуля (типа доставки).
     *
     * @return string
     */

    public function getTitle()
    {
        return t('IML');
    }

    /**
     * Возвращает описание типа доставки.
     *
     * @return string
     */
    public function getDescription()
    {
        return t('IML агрегатор доставок');
    }

    /**
     * Возвращает идентификатор данного типа доставки. (только англ. буквы).
     *
     * @return string
     */
    public function getShortName()
    {
        return t('iml');
    }

    /**
     * Возвращает ORM объект для генерации формы или null.
     *
     * @return \RS\Orm\FormObject | null
     */
    public function getFormObject()
    {
        return new \RS\Orm\FormObject(new \RS\Orm\PropertyIterator(array(
            'secret_login'   => new Type\String(array(
                'description' => t('Логин API'),
                'hint'        => t('Логин для доступа к IML API. Выдается компанией IML.'),
                'maxLength'   => 150,
                    )),
            'secret_pass'    => new Type\String(array(
                'description' => t('Пароль API'),
                'hint'        => t('Пароль для доступа к IML API. Выдается компанией IML.'),
                'maxLength'   => 150,
                    )),
            'region_id_from' => new Type\String(array(
                'description' => t('Регион, откуда осуществляется отправка'),
                'hint'        => t('Все регионы, с которыми работает IML'),
                'list'        => array(array('\Imldelivery\Model\DeliveryType\Iml',
                        'staticGetRegions'), $this),
                    )),
            'service_id_all' => new Type\ArrayList(array(
                'description'       => t('Услуги этого способа доставки'),
                'maxLength'         => 11,
                'visible'           => false,
                'list'              => array(array('\Imldelivery\Model\DeliveryType\Iml',
                        'staticGetServices'), $this),
                'ChangeSizeForList' => false,
                'attr'              => array(array(
                        'size'     => 16,
                        'multiple' => true,
                    )),
                    )),
            'service_id'     => new Type\ArrayList(array(
                'description'   => t('Список всех услуг'),
                'maxLength'     => 1000,
                'runtime'       => false,
                'attr'          => array(array(
                        'multiple' => true,
                    )),
                'template'      => '%imldelivery%/form/delivery/iml/services_list.tpl',
                'listFromArray' => array(array()),
                    )),
            'show_map'       => new Type\Integer(array(
                'description'  => t('Показывать карту'),
                'maxLength'    => 1,
                'default'      => 0,
                'CheckboxView' => array(1, 0),
                    )),
                // 'timeout' => new Type\Integer(array(
                //     'description' => t('Время ожидания ответа IML, сек'),
                //     'hint' => t('Иногда запросы к IML идут очень долго,<br/> чтобы не дожидатся ответа используется это значение.<br/>Рекоммендуемое значение 2 сек.'),
                //     'default' => 2,
                // )),
        )));
    }

    /**
     * Справочник услуг.
     *
     * @return array [id]|string => [name]|string
     */
    public static function staticGetServices($iml = false)
    {
        if (!$iml) {
            $iml = new self();
        }
        $list = $iml->getApiRequest(self::URL_HELP_SERVICES);
        return $iml->prepareArray($list);
    }

    /**
     * Справочник регионов.
     *
     * @return array [id]|string => [name]|string
     */
    public static function staticGetRegions($iml = false)
    {
        if (!$iml) {
            $iml = new self();
        }
        $list = $iml->getApiRequest(self::URL_HELP_SD_REGIONS);
        return $iml->prepareArray($list);
    }

    /**
     * Получает массив регионов, в которых есть пункты самовывоза.
     *
     * @return array [code] => [name]
     */
    public function getSdRegions(\Shop\Model\Orm\Order $order, $delivery_id = null, $params = array())
    {
        return self::staticGetRegions($order->getDelivery()->getTypeObject());
    }

    /**
     * Получает пункты самовывоза одного региона.
     *
     * @param string $params код региона из справочника
     *
     * @return array
     */
    public function getSdByRegion(\Shop\Model\Orm\Order $order, $delivery_id, $params)
    {
        $iml = $order->getDelivery()->getTypeObject();
        $sd_all = $iml->getApiRequest(self::URL_HELP_SD_LIST);
        $region = $params['region_id'];
        $arr = array();
        foreach ($sd_all as $sd_item) {
            if ($sd_item['RegionCode'] == $region) {
                $arr[] = $sd_item;
            }
        }
        return $arr;
    }

    /**
     * Получает цену на доставку до пунктов самовывоза.
     *
     * @param array $params
     *
     * @return string|number
     */
    public function getDeliveryCostAjax(\Shop\Model\Orm\Order $order, $delivery_id, $params)
    {
        if (!$iml = $order->getDelivery()->getTypeObject()) {
            $iml = new self();
        }
        $active_services = $params['service_id'];

        $out = array();
        foreach ($active_services as $code => $description) {
            $content = array(
                'Job'         => $code, // услуга
                'RegionFrom'  => $params['region_id_from'], // регион отправки
                'RegionTo'    => $params['region_id_to'], // регион получения
                'Volume'      => '1', // кол-во мест
                'Weigth'      => $order->getWeight() ? $order->getWeight() : '1', // вес(кг)
                'SpecialCode' => $params['request_code'], // код пункта самовывоза
            );
            $out[$code] = self::postApiRequest(self::URL_API_GETPRICE, $content);
        }
        return $out;
    }

    /**
     * Добавляет доп данные в объект заказа.
     *
     * @param array $params
     */
    public function updateExtraData(\Shop\Model\Orm\Order $order, $delivery_id, $params)
    {
        $extra = $order['order_extra'];
        foreach ($params as $key => $value) {
            $extra['delivery'][$key] = $value;
        }

        return $order['order_extra'] = array_merge($order['order_extra'], $extra);
    }

    /**
     * Возвращает стоимость доставки для заданного заказа. Только число.
     *
     * @param \Shop\Model\Orm\Order   $order
     * @param \Shop\Model\Orm\Address $address - Адрес доставки
     *
     * @return float
     */
    public function getDeliveryCost(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null,
            $use_currency = true)
    {
        $extra = $order['order_extra'];
        if (!isset($extra['delivery'])) {
            $params = $extra['address'];
        }
        $code = $this->getOption('service_id')[0];
        if (!in_array($code, explode(',', self::SD_SERVICE))) {
            $params['request_code'] = '';
        }
        $content = array(
            'Job'         => $code, // услуга
            'RegionFrom'  => $this->getOption('region_id_from'), // регион отправки
            'RegionTo'    => $params['region_id_to'], // регион получения
            'Volume'      => '1', // кол-во мест
            'Weigth'      => $order->getWeight() ? $order->getWeight() : '1', // вес(кг)
            'SpecialCode' => isset($params['request_code']) ? : '', // код пункта самовывоза
        );
        $data = $this->postApiRequest(self::URL_API_GETPRICE, $content);
        return $cost = isset($data['Price']) ? $data['Price'] : 0;
    }

    /**
     * Переделывает структуру массива для формы в админке.
     *
     * @return array [id] => [name]
     */
    public function prepareArray($incomingData)
    {
        $arr = array();
        foreach ($incomingData as $data) {
            $arr[$data['Code']] = (!empty($data['Description'])) ? $data['Description'] : $data['Code'];
        }
        return $arr;
    }

    /**
     * Основной GET запрос к API. Вернет массив объектов.
     *
     * @param string $url
     * @return array
     */
    private function getApiRequest($url)
    {
        $cache_key = md5($url);
        if (!isset($this->cache_api_requests[$cache_key])) {
            $login = $this->getOption('secret_login', '');
            $pass = $this->getOption('secret_pass', '');

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_USERPWD, $login.':'.$pass);
            curl_setopt($curl, CURLOPT_SSLVERSION, 3);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($curl);

            $this->cache_api_requests[$cache_key] = json_decode($response, true);
        }
        return $this->cache_api_requests[$cache_key];
    }

    /**
     * Основной POST запрос к API. Вернет массив объектов.
     *
     * @param string $url
     * @param array  $params
     *
     * @return array
     */
    private function postApiRequest($url, array $params = array())
    {
        $cache_key = md5($url.(serialize($params)));
        if (!isset($this->cache_api_requests[$cache_key])) {
            $login = $this->getOption('secret_login', '');
            $pass = $this->getOption('secret_pass', '');

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
            curl_setopt($curl, CURLOPT_USERPWD, $login.':'.$pass);
            curl_setopt($curl, CURLOPT_SSLVERSION, 3);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($curl);

            $this->cache_api_requests[$cache_key] = json_decode($response, true);
        }
        return $this->cache_api_requests[$cache_key];
    }

    /**
     * Получает услуги выбранные для способа доставки.
     *
     * @return array ['code'=>'name']
     */
    public function getActiveServices()
    {
        $services = $this->getOption('service_id');
        $servicesList = self::staticGetServices($this);
        $arr = array();
        if (is_array($services)) {
            foreach ($services as $service) {
                $arr[$service] = $servicesList[$service];
            }
        }
        return $arr;
    }

    /**
     * Возвращает дополнительный HTML плагина карты для публичной части.
     *
     * @return string
     */
    public function getAddittionalHtml(\Shop\Model\Orm\Delivery $delivery, \Shop\Model\Orm\Order $order = null)
    {
        if ($delivery->getTypeObject()->getOption('show_map') != 1) {
            return '';
        }

        if ($order === null) {
            $order = \Shop\Model\Orm\Order::currentOrder();
        }

        $currency = $order->getMyCurrency();
        if (!$extra = $order['order_extra']['delivery']) {
            $extra = $order['order_extra']['address'];
        }
        $active_services = $this->getActiveServices();
        $region_id_from = $this->getOption('region_id_from');
        $region_id_to = $extra['region_id_to'];

        $req_params = array(
            'region_id_from' => $region_id_from,
            'region_id_to'   => $region_id_to,
            'request_code'   => isset($extra['request_code']) ? $extra['request_code'] : '',
            'service_id'     => $active_services,
        );
        $cost_array = $this->getDeliveryCostAjax($order, $delivery->id, $req_params);

        $view = new \RS\View\Engine();
        $view->assign(array(
            'region_id_to'       => $region_id_to, //Регион куда
            'region_id_from'     => $region_id_from, //Регион откуда
            'service_ids'        => json_encode($active_services), //Услуги этогй доставки
            'delivery_cost_json' => json_encode($cost_array), //Текущий объект цены доставки
            'delivery_cost'      => $cost_array, //Текущий объект цены доставки
            'order'              => $order, //Текущий недоофрмленный заказ
            'currency'           => json_encode($currency->getValues()), //Текущий объект валюты
            'delivery'           => $delivery, //Текущий объект доставки
            'user'               => \RS\Application\Auth::getCurrentUser(), //Текущий объект пользователя
                ) + \RS\Module\Item::getResourceFolders($this));

        return $view->fetch('%imldelivery%/delivery/iml/widget.tpl');
    }

    /**
     * [[AjaxUserAct]] Возвращает адреса пунктов самовывоза для карты.
     *
     * @param \Shop\Model\Orm\Order $order
     * @param int                   $delivery_id
     * @param array                 $params
     *
     * @return [type] [description]
     */
    public function loadMap(\Shop\Model\Orm\Order $order, $delivery_id, $params)
    {
        $extra = $order['order_extra'];
        $order['delivery'] = $delivery_id;
        $delivery = $order->getDelivery();
        $iml = $delivery->getTypeObject();

        $region_id_from = $iml->getOption('region_id_from');
        $region_id_to = isset($extra['delivery']['region_id_to']) ? $extra['delivery']['region_id_to'] : $extra['address']['region_id_to'];

        $req_params = array(
            'region_id_from' => $region_id_from,
            'region_id_to'   => $region_id_to,
            'request_code'   => 1,
            'service_id'     => $iml->getOption('service_id'),
        );
        $cost_array = $iml->getDeliveryCostAjax($order, $delivery_id, $req_params);

        $view = new \RS\View\Engine();
        $view->assign(array(
            'region_id_to'       => $extra['address']['region_id_to'], //Регион куда
            'service_ids'        => json_encode($iml->getOption('service_id')), //Услуги этой доставки
            'delivery_cost_json' => json_encode($cost_array), //Текущий объект цены доставки
            'delivery_cost'      => $cost_array, //Текущий объект цены доставки
            'order'              => $order, //Текущий недоофрмленный заказ
            'delivery'           => $delivery, //Текущий объект доставки
            'delivery_type'      => $iml, //Текущий объект типа доставки
            'user'               => \RS\Application\Auth::getCurrentUser(), //Текущий объект пользователя
                ) + \RS\Module\Item::getResourceFolders($iml));

        return $view->fetch('%imldelivery%/delivery/iml/map.tpl');
    }

    // /**
    //  * Справочник состояний заказа
    //  *
    //  * @return array [id]|string => [name]|string
    //  */
    // static function staticGetDeliveryStatuses()
    // {
    //     $iml = new Iml;
    //     $list = $iml->getApiRequest(self::URL_HELP_DELIVERYSTATUS, self::API_LOGIN, self::API_PASS);
    //     return $iml->prepareArray($list);
    // }
    // /**
    //  * Справочник статусов заказа
    //  *
    //  * @return array [id]|string => [name]|string
    //  */
    // static function staticGetOrderStatus()
    // {
    //     $iml = new Iml;
    //     $list = $iml->getApiRequest(self::URL_HELP_ORDERSTATUS, self::API_LOGIN, self::API_PASS);
    //     return $iml->prepareArray($list);
    // }
    // public function getDeliveryCostText(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null)
    // {
    //     $cost = $this->getDeliveryCost($order, $address);
    //     if ($cost < 0) {
    //         return '';
    //     } else {
    //         return ($cost) ? \RS\Helper\CustomView::cost($cost) . ' ' . $order->getMyCurrency()->stitle : 'бесплатно';
    //     }
    // }
    // /**
    //  * Возвращает дополнительный HTML для административной части с выбором опций доставки в заказе
    //  *
    //  * @param  \Shop\Model\Orm\Order $order - заказ доставки
    //  * @return string
    //  */
    // public function getAdminAddittionalHtml(\Shop\Model\Orm\Order $order = null)
    // {
    //     //Получим данные по товарам
    //     $products = $order->getCart()->getProductItems();
    //     if (empty($products)) {
    //         $this->addError(t('В заказ не добавлено ни одного товара'));
    //     }
    //     //Получим цену с параметрами по умолчанию
    //     $cost       = $this->getDeliveryCostText($order);
    //     $delivery   = $order->getDelivery();
    //     $service_id = $this->getOption('service_id');
    //     $view = new \RS\View\Engine();
    //     $view->assign(array(
    //         'errors'     => $this->getErrors(),
    //         'order'      => $order,
    //         'cost'       => $cost,
    //         'extra_info' => $order->getExtraKeyPair(),
    //         'delivery'   => $delivery,
    //         'service_id' => $service_id
    //     )+\RS\Module\Item::getResourceFolders($this));
    //     return $view->fetch('%imldelivery%/form/delivery/iml/iml_admin.tpl');
    // }
    // /**
    //  * Возвращает HTML виджет с краткой информацией заказа для админки
    //  *
    //  * @param \Shop\Model\Orm\Order $order - объект заказа
    //  */
    // private function getHtmlShortInfo(\Shop\Model\Orm\Order $order)
    // {
    //     $view = new \RS\View\Engine();
    //     $view->assign(array(
    //         'type'          => 'short',
    //         'title'         => 'Краткие сведения заказа',
    //         'order'         => $order,
    //         'delivery_type' => $this
    //     ));
    //     return $view->fetch('%imldelivery%/form/delivery/iml/iml_get_status.tpl');
    // }
    // /**
    //  * Возвращает HTML виджет с информацией заказа для админки
    //  *
    //  * @param \Shop\Model\Orm\Order $order - объект заказа
    //  */
    // private function getHtmlInfo(\Shop\Model\Orm\Order $order)
    // {
    //     $view = new \RS\View\Engine();
    //     $view->assign(array(
    //         'public_api_js_url'  => self::PUBLIC_API_URL_JS,
    //         'public_api_css_url' => self::PUBLIC_API_URL_CSS,
    //         'api_key'            => $this->getOption('admin_api', 0),
    //         'cultureId'          => $this->getOption('language', self::DEFAULT_LANGUAGE_ID),
    //         'type'               => 'full',
    //         'title'              => 'Сведения заказа',
    //         'order'              => $order,
    //         'delivery_type'      => $this
    //     ));
    //     return $view->fetch('%imldelivery%/form/delivery/iml/iml_get_status.tpl');
    // }
    // /**
    //  * Возвращает HTML виджет с историей заказа для админки
    //  *
    //  * @param \Shop\Model\Orm\Order $order - объект заказа
    //  */
    // private function getHtmlHistory(\Shop\Model\Orm\Order $order)
    // {
    //     $view = new \RS\View\Engine();
    //     $view->assign(array(
    //         'public_api_js_url'  => self::PUBLIC_API_URL_JS,
    //         'public_api_css_url' => self::PUBLIC_API_URL_CSS,
    //         'api_key'            => $this->getOption('admin_api', 0),
    //         'cultureId'          => $this->getOption('language', self::DEFAULT_LANGUAGE_ID),
    //         'type'               => 'history',
    //         'title'              => 'История заказа',
    //         'order'              => $order,
    //         'delivery_type'      => $this
    //     ));
    //     return $view->fetch('%imldelivery%/form/delivery/iml/iml_get_status.tpl');
    // }
    // /**
    //  * Возвращает HTML виджет со статусом заказа для админки
    //  *
    //  * @param \Shop\Model\Orm\Order $order - объект заказа
    //  */
    // private function getHtmlStatus(\Shop\Model\Orm\Order $order)
    // {
    //     $view = new \RS\View\Engine();
    //     $view->assign(array(
    //         'public_api_js_url'  => self::PUBLIC_API_URL_JS,
    //         'public_api_css_url' => self::PUBLIC_API_URL_CSS,
    //         'api_key'            => $this->getOption('admin_api', 0),
    //         'cultureId'          => $this->getOption('language', self::DEFAULT_LANGUAGE_ID),
    //         'type'               => 'standard',
    //         'title'              => 'Статус заказа',
    //         'order'              => $order,
    //         'delivery_type'      => $this
    //     ));
    //     return $view->fetch('%imldelivery%/form/delivery/iml/iml_get_status.tpl');
    // }
    // /**
    //  * Действие с запросами к заказу для получения дополнительной информации от доставки
    //  *
    //  * @param \Shop\Model\Orm\Order $order - объект заказа
    //  */
    // public function actionOrderQuery(\Shop\Model\Orm\Order $order)
    // {
    //     $url    = new \RS\Http\Request();
    //     $method = $url->request('method', TYPE_STRING, false);
    //     switch ($method) {
    //         case 'getInfo': //Получение статуса заказа
    //             return $this->getHtmlInfo($order);
    //             break;
    //         case 'getShortInfo': //Получение статуса заказа
    //             return $this->getHtmlShortInfo($order);
    //             break;
    //         case 'getHistory': //Получение статуса заказа
    //             return $this->getHtmlHistory($order);
    //             break;
    //         case 'getStatus': //Получение статуса заказа
    //         default:
    //             return $this->getHtmlStatus($order);
    //             break;
    //     }
    // }
    // /**
    //  * Возвращает дополнительный HTML для админ части в заказе
    //  *
    //  * @param  \Shop\Model\Orm\Order $order - заказ доставки
    //  * @return string
    //  */
    // public function getAdminHTML(\Shop\Model\Orm\Order $order)
    // {
    //     $view = new \RS\View\Engine();
    //     $view->assign(array(
    //         'order' => $order
    //     ));
    //     return $view->fetch('%imldelivery%/form/delivery/iml/iml_additional_html.tpl');
    // }

    /**
     * Запрос получает статус и состояние заказа(ов).
     *
     * @param array|null $filters
     *
     * @return array
     */
    public function getImlStatuses(array $filters = null)
    {
        // $content = array(
        //     'Test' => 'True',                       // для тестового режима, иначе не указывайте
        //     'CustomerOrder' => '',                 // номер заказа
        //     'BarCode' => '',                        // штрих код
        //     'DeliveryDateStart' => '',               // фильтр по дате доставки, с указанной даты и позднее
        //     'DeliveryDateEnd' => '',                // фильтр по дате доставки, до указанной даты
        //     'State' => 999,                         // из справочника
        //     'OrderStatus' => 0,                    // из справочника
        //     'Job' => '',                          // из справочника услуг
        //     'RegionFrom' => '',                   // фильтр по региону отправки
        //     'RegionTo' => '',                       // фильтр по региону получения
        //     'CreationDateStart' => '',              // фильтр по дате доставки, с указанной даты и позднее
        //     'CreationDateEnd' => ''                 // фильтр по дате доставки, до указанной даты
        // );
        // return $this->postApiRequest(self::URL_API_STATUS, self::API_LOGIN, self::API_PASS, array_merge($content, $filters));
    }

    /**
     * Запрос позволяет получить список заказов по параметрам
     *
     * @param array|null $filters
     *
     * @return array
     */
    public function getImlOrders(array $filters = null)
    {
        // $content =array(
        //     'Test' => 'True', // для тестового режима, иначе не указывайте
        //     //'CustomerOrder' => '',                 // номер заказа
        //     //'BarCode' => '2624028597816',          // штрих код
        //     //'DeliveryDateStart' => '2014-01-15',   // с указанной даты и позднее
        //     //'DeliveryDateEnd' => '2014-07-15',     // до указанной даты
        //     //'State' => 3                           // из справочника
        //     //'OrderStatus' => 0,                    // из справочника
        //     'Job' => 'С24КО', // из справочника услуг
        //     //'RegionFrom' => 'МОСКВА',              // из справочника регионов
        //     //'RegionTo' => 'МОСКВА',                // из справочника регионов
        //     'CreationDateStart' => '2014-01-15' // с указанной даты и позднее
        //     //'CreationDateEnd' => '2014-07-15'      // до указанной даты
        // );
        // return $this->postApiRequest(self::URL_API_ORDERSLIST, self::API_LOGIN, self::API_PASS, array_merge($content, ilters));
    }

    /**
     * Запрос позволяет создать заказ в системе IML.
     *
     * @param array|null $filters
     *
     * @return array
     */
    public function createImlOrder(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null)
    {
        // if(!$address) $address = $order->getAddress();
        // $catalog_config = \RS\Config\Loader::byModule('catalog');
        // $currency      = \Catalog\Model\CurrencyApi::getBaseCurrency(); //Базовая валюта
        // $date          = date('c',strtotime($order['dateof'])); //Дата заказа
        // $delivery      = $order->getDelivery(); //объект доставки
        // $delivery_cost = $delivery->getDeliveryCost($order);
        // $payment       = $order->getPayment();
        // $user          = $order->getUser();
        // $extra         = $order->getExtraInfo();
        // $cartItemsArray    = array();
        // $cart     = $order->getCart();
        // $products = $cart->getProductItems();
        // $cartdata = $cart->getPriceItemsData();
        // $services = self::staticGetServices();
        // // Заполняем продукты
        // foreach ($products as $n => $item) {
        //     $product           = $item['product'];
        //     $barcode           = $product->getBarCode($item['cartitem']['offer']);
        //     $offer_title       = $product->getOfferTitle($item['cartitem']['offer']);
        //     $cartItemsArray[] = array(
        //         'productNo'=> $barcode,
        //         'productName' => $product->title,
        //         'productVariant' => $offer_title,
        //         'productBarCode' => '',
        //         'couponCode' => '',
        //         'discount' => $item->discount,
        //         'weightLine' => $item->single_weight,
        //         'amountLine' => $item->single_cost,
        //         'statisticalValueLine' => '',
        //         'itemQuantity' => $item['cartitem']['amount'],
        //         'deliveryService' => FALSE
        //     );
        // }
        // // Заполняем доставку
        // if ($delivery_cost > 0) {
        //     $cartItemsArray[] = array(
        //         'productNo'=> $extra['iml_service_id']['value'],
        //         'productName' => $services[$extra['iml_service_id']['value']],
        //         'productVariant' => '',
        //         'productBarCode' => '',
        //         'couponCode' => '',
        //         'discount' => '',
        //         'weightLine' => '',
        //         'amountLine' => '',
        //         'statisticalValueLine' => '',
        //         'itemQuantity' => '',
        //         'deliveryService' => TRUE
        //     );
        // }
        // // Заполняем инфо о заказе
        // $content = array(
        //     'Test' => 'True', // для тестового режима, иначе не указывайте
        //     'CustomerOrder' => $user['id'] ? $user['id'] : $order['id'],
        //     'Job' => $extra['iml_service_id']['value'], // из справочника услуг
        //     'Contact' => $user['name'],
        //     'RegionCodeFrom' => $this->getOption('region_id_from',0), // из справочника регионов
        //     'RegionCodeTo' => $extra['iml_region_to']['value'], // из справочника регионов
        //     'Address' => $address['address'],
        //     'DeliveryPoint' => $extra['iml_request_code']['value'], // из справочника пунктов самовывоза
        //     'Phone' => $user['phone'],
        //     'Amount' => $order['totalcost'], // разделитель '.' (точка)
        //     'ValuatedAmount' => '14.2', // разделитель '.' (точка)
        //     'Weight' => $order->getWeight(), // разделитель '.' (точка)
        //     'Comment' => $order['comments'],
        //     'GoodItems' => $cartItemsArray      //не обязательно, если есть позиции заказа
        // );
        // return $this->postApiRequest(self::URL_API_CREATEORDER, self::API_LOGIN, self::API_PASS, $content);
    }

    //  Test – тестовый режим, 'True' для тестового режима, иначе не указывайте
    //  Job – услуга доставки, Code из справочника услуг
    //  CustomerOrder – номер заказа
    //  DeliveryDate – дата доставки в строковом представлении, формат dd.MM.yyyy
    //  Volume – количество мест
    //  Weight – вес
    //  BarCode – штрих код заказа в формате EAN-13
    //  DeliveryPoint – пункта самовывоза, RequestCode из таблицы пунктов самовывоза
    //  Phone – телефон
    //  Contact – контактное лицо
    //  RegionCodeFrom – региона отправления, Code из таблицы регионов
    //  RegionCodeTo – код региона получения, Code из таблицы регионов
    //  Address – адрес доставки
    //  TimeFrom – начало временного периода доставки
    //  TimeTo – конец временного периода доставки
    //  Amount – сумма заказа
    //  ValuatedAmount – оценочная стоимость заказа
    //  Comment – комментарий
    //  City – город доставки, для отправки почтой России
    //  PostCode – индекс, для отправки почтой России
    //  PostRegion – регион, для отправки почтой России
    //  PostArea – район, для отправки почтой России
    //  PostContentType – тип вложения (0 - Печатная, 1 - Разное, 2 - 1 Класс), для отправки почтой России

    /**
     * Функция срабатывает после создания заказа.
     *
     * @param \Shop\Model\Orm\Order   $order   - объект заказа
     * @param \Shop\Model\Orm\Address $address - Объект адреса
     */
    public function onOrderCreate(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null)
    {
        //     $extra = $order->getExtraInfo();
        //     if (!isset($extra['iml_order_response'])){ // Если заказ не создан
        //         //Создадим заказ
        //         //$created_order = $this->createImlOrder($order,$address);
        //         //Если ответа дождались, то запишем номер заказа
        //         if ($created_order){
        //             if ($created_order['Result'] == 'OK') {
        //                 $order->addExtraInfoLine(
        //                     'IML ответ на создание заказа',
        //                     $created_order['Result'],
        //                     $created_order['Order'],
        //                     'iml_order_response'
        //                 );
        //             } elseif ($created_order['Result'] == 'Error') {
        //                 $order->addExtraInfoLine(
        //                     'IML ошибка создания заказа',
        //                     $created_order['Result'],
        //                     $created_order['Errors'],
        //                     'iml_order_error'
        //                 );
        //             }
        //         } else { //Иначе
        //             $order->addExtraInfoLine(
        //                 'IML API не отвечает',
        //                 'Error',
        //                 array(
        //                     0 => 'Что-то с сетью'
        //                 ),
        //                 'iml_api_error'
        //             );
        //         }
        //         //$extra = $order->getExtraInfo();
        //     }
        //     //Запишем данные в таблицу, чтобы не вызывать повторного сохранения
        //     \RS\Orm\Request::make()
        //         ->update()
        //         ->from(new \Shop\Model\Orm\Order())
        //         ->set(array(
        //             '_serialized' => serialize($order['extra'])
        //         ))
        //         ->where(array(
        //             'id' => $order['id']
        //     ))->exec();
    }

}
