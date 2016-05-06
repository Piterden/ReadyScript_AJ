<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace Site\Model\Orm;
use \RS\Orm\Type;

/**
* Конфигурационный файл одного сайта.
* @ingroup Site
*/
class Config extends \RS\Orm\AbstractObject
{
    protected static 
        $table = 'site_options',
        $activeSiteInstance;
    
    function _init()
    {
        $this->getPropertyIterator()->append(array(
            'site_id' => new Type\CurrentSite(array(
                'primaryKey' => true
            )),
            t('Основные'),
                    'admin_email' => new Type\Varchar(array(
                        'maxLength' => '150',
                        'description' => 'E-mail администратора(ов)',
                        'hint' => 'На этот ящик будут приходить уведомления о событиях в системе(заказы, покупки в 1 клик, обращениях пользователей, и т.д.). <br>Допустимо указывать несколько E-mail адресов через запятую.<br> Например: admin@example.com или admin@example.com,manager@example.com',
                    )),
                    'admin_phone' => new Type\Varchar(array(
                        'maxLength' => '150',
                        'description' => t('Телефон администратора'),
                        'hint' => t('На этот телефон будут приходить SMS уведомления(опционально). <br>Допустимо указывать несколько телефонов через запятую.<br> Например: +79112223334 или +79112223334,+79222333411'),
                    )),
                    'theme' => new Type\Varchar(array(
                        'Attr' => array(array('readonly' => 'readonly')),
                        'maxLength' => '150',
                        'description' => 'Тема',
                        'template' => '%site%/form/options/theme.tpl'
                    )),
                    'favicon' => new Type\File(array(
                        'description' => t('Иконка сайта 16x16 (PNG, ICO)'),
                        'hint' => t('Отображается возле заголовка страницы в браузерах'),
                        'max_file_size' => 100000, //100 Кб
                        'allow_file_types' => array('image/png', 'image/x-icon'),
                        'storage' => array(\Setup::$ROOT, \Setup::$FOLDER.'/storage/favicon/')
                    )),
            t('Организация'),
                    'logo' => new Type\Image(array(
                        'maxLength' => '200',
                        'description' => 'Логотип',
                        'max_file_size' => 10000000,
                        'allow_file_types' => array('image/pjpeg', 'image/jpeg', 'image/png', 'image/gif'),
                        'PreviewSize' => array(275,80),
                    )),
                    'slogan' => new Type\Varchar(array(
                        'description' => t('Лозунг')
                    )),
                    'firm_name' => new Type\Varchar(array(
                        'maxLength' => '255',
                        'description' => 'Наименование организации',
                    )),
                    'firm_inn' => new Type\Varchar(array(
                        'maxLength' => '12',
                        'description' => 'ИНН организации',
                        'Attr' => array(array('size' => 20)),
                    )),
                    'firm_kpp' => new Type\Varchar(array(
                        'maxLength' => '12',
                        'description' => 'КПП организации',
                        'Attr' => array(array('size' => 20)),
                    )),
                    'firm_bank' => new Type\Varchar(array(
                        'maxLength' => '255',
                        'description' => 'Наименование банка',
                    )),
                    'firm_bik' => new Type\Varchar(array(
                        'maxLength' => '10',
                        'description' => 'БИК',
                    )),
                    'firm_rs' => new Type\Varchar(array(
                        'maxLength' => '20',
                        'description' => 'Расчетный счет',
                        'Attr' => array(array('size' => 25)),
                    )),
                    'firm_ks' => new Type\Varchar(array(
                        'maxLength' => '20',
                        'description' => 'Корреспондентский счет',
                        'Attr' => array(array('size' => 25)),
                    )),
                    'firm_director' => new Type\Varchar(array(
                        'maxLength' => '70',
                        'description' => 'Фамилия, инициалы руководителя',
                    )),
                    'firm_accountant' => new Type\Varchar(array(
                        'maxLength' => '70',
                        'description' => 'Фамилия, инициалы главного бухгалтера',
                    )),
            
            t('Уведомления'),
                    'notice_from' => new Type\Varchar(array(
                        'maxLength' => 255,
                        'description' => t("Будет указано в письме в поле  'От'"),
                        'hint' => t('Например: "Магазин ReadyScript&lt;robot@ваш-магазин.ru&gt;" или просто "robot@ваш-магазин.ru"')
                        
                    )),
                    'notice_reply' => new Type\Varchar(array(
                        'maxLength' => 255,
                        'description' => t("Куда присылать ответные письма? (поле Reply)"),
                        'hint' => t('Например: "Магазин ReadyScript&lt;support@ваш-магазин.ru&gt;" или просто "support@ваш-магазин.ru"')
                    )),
            t('Социальные ссылки'),
                    'facebook_group' => new Type\Varchar(array(
                        'description' => t('Ссылка на группу в Facebook')
                    )),
                    'vkontakte_group' => new Type\Varchar(array(
                        'description' => t('Ссылка на группу ВКонтакте')
                    )),
                    'twitter_group' => new Type\Varchar(array(
                        'description' => t('Ссылка на страницу в Twitter')
                    ))
        ));
    }
    
    function getPrimaryKeyProperty()
    {
        return 'site_id';
    }
    
    function _initDefaults()
    {
        $this['theme'] = 'default';        
    }
        
    public static function getActualInstance()
    {
        if (!isset(self::$activeSiteInstance)) {
            self::$activeSiteInstance = new self();
            self::$activeSiteInstance->load(\RS\Site\Manager::getSiteId());            
        }
        return self::$activeSiteInstance;
    }
    
    function getThemeName()
    {
        $theme_data = \RS\Theme\Manager::parseThemeValue($this['theme']);
        return $theme_data['theme'];
    }
}