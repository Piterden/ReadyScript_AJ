<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace Catalog\Model;


/**
 * Класс содержит API функции для работы с формой купить в один клик
 * @ingroup Catalog
 */
class OneClickApi extends \RS\Module\AbstractModel\EntityList
{
    
    
    function __construct()
    {
        parent::__construct(new \Catalog\Model\Orm\Product());
    }
    
     /**
     * Отсылает форму купить в один клик и предварительно проверяет её
     * 
     * @param \Catalog\Model\Orm\Product $product                - объект товара который хотим покупка  
     * @param \RS\Http\Request $url                              - объект запроса
     * @param \RS\Config\UserFieldsManager $click_fields_manager - менеджер дополнительных полей 
     * @param array $offer_fields                                - массив полей с комплектациями
     * 
     * @return boolean
     */
     function send(\Catalog\Model\Orm\Product $product,\RS\Http\Request $url, $click_fields_manager, $offer_fields = array())
     {                  
         //Проверим и получим обязательные переменные из запроса
         $name         = $url->request('name',TYPE_STRING);       //Имя 
         $phone        = $url->request('phone',TYPE_STRING);      //Телефон
         $kaptcha_key  = $url->request('kaptcha',TYPE_STRING);    //Каптча
         $click_fields = $url->request('clickfields',TYPE_ARRAY); //Доп. поля
         
         if (empty($name)){  //Если пустые поля
            $this->addError(t("Поле 'Имя' является обязательным.")); 
         }
         
         if (empty($phone)){  //Если пустые поля
            $this->addError(t("Поле 'Телефон' является обязательным.")); 
         }
         
         //Проверим дополнительные поля
         if (!$click_fields_manager->check($click_fields)){
            $this->addError($click_fields_manager->getErrors());  
         }
         $fields = $click_fields_manager->getStructure(); //Получим значения доп. полей
         
         //Проверим каптчу, если не залогинен
         if (!\RS\Application\Auth::isAuthorize() && \RS\Module\Manager::staticModuleEnabled('kaptcha')){
            if (!\Kaptcha\Model\Img::checkKeyString($kaptcha_key)) {
                 $this->addError(t('Код защиты введён неправильно'));  
            } 
         }
         
         //Если ошибка то выходим
         if ($this->hasError())return false;
         
         $click_info = array(
            'name'         => $name,
            'phone'        => $phone,
            'product'      => $product, 
            'ext_fields'   => $fields,
            'offer_fields' => $offer_fields
         );
         
         
         $notice = new \Catalog\Model\Notice\OneClickAdmin();
         $notice->init($click_info);
         //Отсылаем письмо администратору
         \Alerts\Model\Manager::send($notice); 
         
         $notice = new \Catalog\Model\Notice\OneClickUser();
         $notice->init($click_info);
         //Отсылаем sms пользователю
         \Alerts\Model\Manager::send($notice); 
         
         //Добавим в БД сведения
         $click_item = new \Catalog\Model\Orm\OneClickItem();
         $click_item['dateof'] = date("Y.m.d H:i:s");
         $click_item['stext']  = serialize($click_info);
         $click_item['ip']     = $_SERVER['REMOTE_ADDR'] ? $_SERVER['REMOTE_ADDR'] : $_SERVER['HTTP_X_FORWARDED_FOR'];
         $click_item->insert();
         return true;
     }       
}