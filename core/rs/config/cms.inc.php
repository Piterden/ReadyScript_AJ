<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace RS\Config;
use \RS\Orm\Type;

/**
* Общая конфигурация CMS
*/
class Cms extends \RS\Orm\AbstractObject
{
    function _init()
    {
        $this->getPropertyIterator()->append(array(
            t('Основные'),
                    //Параметры, которые будут перезаписывать значения в \Setup
                    'INSTALLED' => new Type\Integer(array(
                        'visible' => false
                    )),
                    'FOLDER' => new Type\Varchar(array(
                        'visible' => false
                    )),                    
                    'DB_INSTALL_MODE' => new Type\Integer(array(
                        'visible' => false
                    )),
                    'DB_HOST' => new Type\Varchar(array(
                        'visible' => false
                    )),
                    'DB_NAME' => new Type\Varchar(array(
                        'visible' => false
                    )),
                    'DB_USER' => new Type\Varchar(array(
                        'visible' => false
                    )),
                    'DB_PASS' => new Type\Varchar(array(
                        'visible' => false
                    )),
                    'DB_TABLE_PREFIX' => new Type\Varchar(array(
                        'visible' => false
                    )),
                    'SECRET_KEY' => new Type\Varchar(array(
                        'visible' => false
                    )),
                    'SECRET_SALT' => new Type\Varchar(array(
                        'visible' => false
                    )),
                    'ADMIN_SECTION' => new Type\Varchar(array(
                        'description' => t('Адрес панели администрирования'),
                        'maxLength' => 100,
                        'hint' => t('Например, если значение этого поля равно "admin", то адрес админ. панели будет http://<адрес_сайта>/admin/'),
                        'visible' => !defined('CLOUD_UNIQ')
                    )),
                    'DEFAULT_THEME' => new Type\Varchar(array(
                        'visible' => false
                    )),
                    'SM_COMPILE_CHECK' => new Type\Integer(array(
                        'description' => t('Всегда проверять шаблоны на предмет модификации'),
                        'hint' => t('Включение данной опции замедлит скорость работы сайта'),
                        'checkboxView' => array(1,0)
                    )),
                    'DETAILED_EXCEPTION' => new Type\Integer(array(
                        'description' => t('Подробно отображать информацию об исключениях'),
                        'hint' => t('Отображение ошибок значительно снижает уровень безопасности. Используйте эту опцию, только во время разработки и настройки сайта'),
                        'checkboxView' => array(1,0)
                    )),
                    'CACHE_ENABLED' => new Type\Integer(array(
                        'description' => t('Включить кэширование данных'),
                        'hint' => t('Кэширование данных значительно ускоряет работу сайта и снижает нагрузку на сервер'),
                        'checkboxView' => array(1,0)
                    )),
                    'CACHE_BLOCK_ENABLED' => new Type\Integer(array(
                        'description' => t('Включить кэширование Smarty'),
                        'hint' => t('Кэширование готового HTML поддерживают некоторые блоки. Информация в таких блоках будет обновляться только после принудительного сброса кэша или истечении срока жизни КЭШа'),
                        'checkboxView' => array(1,0)
                    )),
                    'CACHE_TIME' => new Type\Integer(array(
                        'description' => t('Время жизни КЭШ`а, в сек.'),
                        'hint' => t('Стандартное значение - 300 сек')
                    )),
                    'COMPRESS_CSS' => new Type\Integer(array(
                        'description' => t('Подключаемые CSS файлы'),
                        'hint' => t('<b>Объединение CSS файлов</b> увеличивает скорость загрузки страницы в браузере<br><b>Объединение и оптимизация</b> значительно замедляет первичную генерацию объединенного CSS файла, но ускоряет его последующую загрузку'),
                        'listFromArray' => array(array(
                            0 => t('Оставлять как есть'),
                            1 => t('Объединять'),
                            2 => t('Объединять и оптимизировать'),
                        ))
                    )),                    
                    'COMPRESS_JS' => new Type\Integer(array(
                        'description' => t('Подключаемые JavaScript файлы'),
                        'hint' => t('<b>Объединение JavaScript файлов</b> увеличивает скорость загрузки страниц в браузере<br><b>Объединение и оптимизация</b> значительно замедляет первичную генерацию объединенного JS файла, но ускоряет его последующую загрузку'),
                        'listFromArray' => array(array(
                            0 => t('Оставлять как есть'),
                            1 => t('Объединять'),
                            2 => t('Объединять и оптимизировать'),
                        ))
                        
                    )),
                    'JS_POSITION_FOOTER' => new Type\Integer(array(
                        'description' => t('Размещать по-умолчанию инструкции подключения скриптов внизу страницы'),
                        'hint' => t('Опцию следует использовать, если тема оформления создана с поддержкой подключения скриптов внизу страницы'),
                        'checkboxView' => array(1,0)
                    )),                                                           
                    'show_debug_header' => new Type\Integer(array(
                        'description' => t('Отображать администратору спецблок на сайте в режиме отладки'),
                        'checkboxView' => array(1,0)
                    )),
                    'CRON_ENABLE' => new Type\Integer(array(
                        'description' => t('Разрешить выполнение заданий cron<br>(последний запуск был: '.\RS\Cron\Manager::obj()->getLastExecTimeStr().')'),
                        'default' => 1,
                        'hint' => t('Для работы планировщика необходимо настроить на хостинге ежеминутный запуск файла /core/cron/cron.php'),
                        'checkboxView' => array(1,0)
                    )),

            t('Кэш'),
                '__cache__' => new Type\UserTemplate('%main%/form/sysoptions/cache.tpl'),
            
            t('Уведомления'),
                    'notice_from' => new Type\Varchar(array(
                        'maxLength' => 255,
                        'description' => t("Будет указано в письме в поле  'От'"),
                        'hint' => t('Например: "Магазин ReadyScript&lt;robot@ваш-магазин.ru&gt;" или просто "robot@ваш-магазин.ru". Перекрывается настройками сайта')
                        
                    )),
                    'notice_reply' => new Type\Varchar(array(
                        'maxLength' => 255,
                        'description' => t("Куда присылать ответные письма? (поле Reply)"),
                        'hint' => t('Например: "Магазин ReadyScript&lt;support@ваш-магазин.ru&gt;" или просто "support@ваш-магазин.ru". Перекрывается настройками сайта')
                    )),
                    'smtp_is_use' => new Type\Integer(array(
                        'description' => t('Использовать SMTP для отправки писем'),
                        'checkboxView' => array(1,0),
                        'template' => '%main%/form/sysoptions/smtp_is_use.tpl'
                    )),
                    'smtp_host' => new Type\Varchar(array(
                        'description' => t('SMTP сервер')
                    )),
                    'smtp_port' => new Type\Varchar(array(
                        'description' => t('SMTP порт'),
                        'maxLength' => 10
                    )),
                    'smtp_secure' => new Type\Varchar(array(
                        'description' => t('Тип шифрования'),
                        'listFromArray' => array(array(
                            '' => 'Нет шифрования',
                            'ssl' => 'SSL',
                            'tls' => 'TLS'
                        ))
                    )),
                    'smtp_auth' => new Type\Integer(array(
                        'description' => t('Требуется авторизация на SMTP сервере'),
                        'checkboxView' => array(1,0)
                    )),                    
                    'smtp_username' => new Type\Varchar(array(
                        'description' => t('Имя пользователя SMTP'),
                        'maxLength' => 100
                    )),
                    'smtp_password' => new Type\Varchar(array(
                        'description' => t('Пароль SMTP'),
                        'maxLength' => 100
                    ))
        ));
    }
    
    function _initDefaults()
    {
        $this['DB_INSTALL_MODE'] = 1;
        $this['show_debug_header'] = 1;
        $this['notice_from'] = 'robot@'.\Setup::$DOMAIN;
        $this['notice_reply'] = 'no-reply@'.\Setup::$DOMAIN;
    }
    
    protected function getStorageInstance()
    {
        return new \RS\Orm\Storage\Arrayfile($this, array('store_file' => \Setup::$PATH.'/config.auto.php'));
    }
    
    /**
    * Возвращает данные дл поля From писем
    * 
    * @param bool $return_email - Если true, то вернет email, если false - то вернет надпись, если null - то вернет массив
    * @return mixed
    */
    function getNoticeFrom($return_email = null)
    {
        $parsed = $this->getNoticeParsed('notice_from');
        
        if ($return_email === null) return $parsed;
        return $return_email ? $parsed['email'] : $parsed['string'];
    }
    
    /**
    * Возвращает данные дл поля Reply писем
    * 
    * @param $return_email - Если true, то вернет email, если false - то вернет надпись, если null - то вернет массив
    * @return mixed
    */    
    function getNoticeReply($return_email = null)
    {
        $parsed = $this->getNoticeParsed('notice_reply');
        
        if ($return_email === null) return $parsed;
        return $return_email ? $parsed['email'] : $parsed['string'];        
    }        

    
    /**
    * Возвращает разобранные данные для полей From или Reply уведомлений
    * Возьмет свойство из настроек сайта, если таковой существует
    * 
    * @param string $property - имя свойства, котороенужно разобрать
    * @return array
    */
    protected function getNoticeParsed($property)
    {
        $site = \RS\Config\Loader::getSiteConfig();
        $result = array( 'line' => ($site && !empty($site[$property])) ? $site[$property] : $this[$property] );
        if (preg_match('/^(.*?)<(.*)>$/', html_entity_decode($result['line']), $match)) {
            $result['string'] = $match[1];
            $result['email'] = $match[2];
        } else {
            $result['string'] = '';
            $result['email'] = $result['line'];
        }
        return $result;
    }
    
}

