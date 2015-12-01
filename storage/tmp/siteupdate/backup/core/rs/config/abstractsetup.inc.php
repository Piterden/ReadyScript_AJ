<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace RS\Config;

/**
* Класс содержит системные настройки, которые можно переназначить в файле setup.php в корневой папке
* Текущий файл будет перезаписан при обновлении ядра.
*/
abstract class AbstractSetup
{
    public static 
        //Системные параметры
        $INSTALLED,
        
        //Общие параметры
        $VERSION          = '2.0.0.179',  //Текущая версия ядра      
        $CLASS_EXT        = 'inc.php',    //Расширение файлов с классами
        $CUSTOM_CLASS_EXT = 'my.inc.php', //Расширение файлов с перегруженными классами
        $PATH,   //Путь к корневому каталогу системы = DOCUMENT_ROOT + FOLDER
        $ROOT,   //Путь к корневому каталогу хоста = DOCUMENT_ROOT
        $FOLDER, //Папка системы (разница между PATH и ROOT) Например: /eshop
        $DOMAIN, //Текущий домен, например: example.com
        $ZONE,   //Текущая зона домена, например: com
        
        //Параметры базы данных
        $DB_HOST         = 'localhost',
        $DB_NAME         = ' ',
        $DB_USER         = ' ',
        $DB_PASS         = ' ',
        $DB_CHARSET      = 'utf8',
        $DB_AUTOINIT     = true,
        $DB_INSTALL_MODE = true,        
        $DB_TABLE_PREFIX = '',
        
        $TIMEZONE = 'Europe/Moscow', //Временная зона по-умолчанию
        $DEFAULT_ROUTE_ENABLE = true, //Включает, выключает маршрут по-умолчанию. /controller/action/
        
        //Smarty - параметры
        $SM_RELATIVE_TEMPLATE_PATH = '/templates', //Папка с шаблонами
        $SM_TEMPLATE_PATH,
        $SM_COMPILE_PATH           = '/cache/smarty/compile', //Папка для компилированных шаблонов
        $SM_CACHE_PATH             = '/cache/smarty/cache',   //Папка для кэш данных шаблонов
        $SM_COMPILE_CHECK          = false, //Если true, то перекомпилировать шаблоны по мере надобности. (Для разработки необходимо указывать true, на продакшн сервере - false)
        
        //Параметры обновления и проверки лицензии
        $CHECK_DOMAIN_TIMEOUT = 4,
        $CHECK_LICENSE_SERVER = 'http://update.readyscript.ru/checklicense/', //URL для запросов проверки лицензии
        $BUY_LICENSE_URL      = 'http://readyscript.ru/product/{script_type}/', //URL страницы для покупки лицензии
        $UPDATE_URL           = 'http://update.readyscript.ru/update/', //URL для запросов на обновление системы
        $UPDATE_CHANNEL       = 'release', //Канал обновления

        //Параметры магазина дополнений
        $MARKETPLACE_DOMAIN = 'marketplace.readyscript.ru', // Домен магазина дополнений
        
        //Настройки локализации
        $INTERNAL_ENCODING = 'UTF-8',
        $LOCALE            = 'ru_RU.UTF-8',
        $NUMERIC_LOCALE    = 'en_US.UTF-8',
        
        //Пути к общим ресурсам
        $RESOURCE_PATH = '/resource',
        $JS_PATH       = '/resource/js',
        $CSS_PATH      = '/resource/css',
        $IMG_PATH      = '/resource/img',        
        
        //Параметры безопасности
        $AUTH_TRY_COUNT   = 50, //Количество неуспешных авторизации с одного IP, до бана функции авторизации
        $AUTH_BAN_SECONDS = 14400, //Количество секунд, на которое блокировать попытки авторизации с IP злоумышленника
        
        $CREATE_DIR_RIGHTS = 0755, //Права на вновь создаваемые папки
        $DEFAULT_THEME     = 'default', //Тема, которая будет установлена сразу после инсталяции системы
        
        //Параметры для шаблонов
        $SCRIPT_TYPE,
        $MODULE_WATCH_TPL = '/moduleview',   //путь к альтернативной папке шаблонов модулей
        $THEME_XML        = 'theme.xml',     //Файл с информацией о теме оформления
        $RES_CSS_FOLDER   = '/resource/css', //Путь к CSS относительно папки темы
        $RES_JS_FOLDER    = '/resource/js',  //Путь к JS относительно папки темы
        $RES_IMG_FOLDER   = '/resource/img', //Путь к изображениям относительно папки темы
        $DEFAULT_LAYOUT   = '%THEME%/layout.tpl', //Шаблон с которого начинается рендеринг любой страницы
        
        //Параметры кэша
        $CACHE_MAIN_FOLDER  = '/cache',          //Корневая папка для всех кэш файлов
        $CACHE_FOLDER       = '/cache/engine',   //Папка для общего кэша системы
        $CACHE_TABLE_FOLDER = '/cache/tableact', //Папка с информацией об актуальности таблиц
        $CACHE_LANG_FOLDER  = '/cache/lang',     //Папка для кэширования языковых фраз
        $CACHE_USE_WATCHING_TABLE = true, //Если true, то будет делаться отметка об изменении таблицы, при запросах INSERT, UPDATE, DELETE. (можно использовать в \RS\Cache для определения неактуальности кэша)
        $CACHE_LANG_JS_FILE       = true, //Если false, то при каждом запуске скрипта будет проверяться необходимость создания языкового файла для JS
        $CACHE_TIME               = 300,  //Время жизни кэша
        $CACHE_ENABLED            = true, //Данное значение переназначается в config.auto.php
        
        //Параметры модулей
        $MODULE_FOLDER     = '/modules',  //Папка для модулей
        $MODULE_TPL_FOLDER = '/view',     //Папка для шаблонов относительно папки модулей
        $CONFIG_FOLDER     = '/config',   //Относительно $module_folder
        $CONFIG_CLASS      = 'file',      //Класс конфигурации модулей
        $CONFIG_XML        = 'module.xml',//Файл настроек модуля
        $HANDLERS_CLASS    = 'handlers',  //Класс обработчиков собития модулей
        $MY_HANDLERS_CLASS = 'myhandlers',//Приоритетный класс обработчиков событий
        
        //Параметры сессии
        $SESSION_TIME = 10800,  //Срок жизни сессии в секундах
        
        //Параметры административной панели
        $ADMIN_SECTION = 'admin', //Секция URL для административной панели
        
        //Отладка
        $DETAILED_EXCEPTION       = false, //Подробно отображать информацию об исключениях. На production серверах должно быть - false
        $WRITE_EXCEPTIONS_TO_FILE = false, //Писать лог исключений в файл
        $EXCEPTIONS_FILE          = '/exceptions.auto.txt', //Имя файла лога исключений, относительно корня
        $LOG_EXECUTE_TIME         = false, //Записывать время выполнения скриптов. false - не записывать. Рекомендуется включать только на время отладки
        $LOG_EXECUTE_FILE         = '/logs/exectime.log',
        $LOG_SQLQUERY_TIME        = false, //Записывает все запросы к базе и время их выполнения. false - не записывать. Рекомендуется включать исключительно на время отладки
        $LOG_SQLQUERY_FILE        = '/logs/sqlquery.log',
        
        //Параметры оптимизатора CSS, JS
        $COMPRESS_CSS      = 0, //0 - выключено, 1 - автоматически объединять CSS в один файл, 2 - объединять и оптимизировать
        $COMPRESS_CSS_PATH = '/cache/resource/min_css', //Путь, где будут храниться кэшированные файлы оптимизированных CSS.
        
        $COMPRESS_JS       = 0, //0 - выключено, 1 - автоматически объединять JS в один файл, 2 - объединять и оптимизировать
        $COMPRESS_JS_PATH  = '/cache/resource/min_js', //Путь к сжатым js файлам
        
        $JS_POSITION_FOOTER = false, //true - позиция скриптов по-умолчанию - низ HTML страницы
        
        $STORAGE_DIR = '/storage',     //Папка для пользовательских данных
        $TMP_REL_DIR = '/storage/tmp', //Директория для временных файлов
        $TMP_DIR,
        $DOCTYPE     = 'HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"',
        
        $SECRET_KEY = 'A6k3a4leohg7b', //Секретный ключ для формирования путей к изображениям, закрытым файлам, и.т.д. Переопределяется в config.auto.php
        $SECRET_SALT = 'B6&3mkseoiwmd',//Соль безопасности. Переопределяется в config.auto.php
        
        $NOPHOTO_IMAGE = '/resource/img/photostub/nophoto.jpg', //Путь к заглушке фотографий по умолчанию
        $NOPHOTO_THEME_PATH = '/photostub', //Путь к загрушке фотографий относительно картинок темы
        $NOPHOTO_THEME_FILE = 'nophoto.jpg'; //Имя файла заглушки
        
    protected static 
        //Подключаемые модули
        $include_list = array(
            '/core/system/autoload.inc.php',
            '/core/system/constants.inc.php',
            '/core/system/exceptions.inc.php',
            '/core/smarty/Smarty.class.php',
            '/core/csstidy/class.csstidy.php',
            );
    
    /**
    * Инициализирует основные настройки системы
    * 
    * @return void
    */
    public static function init()
    {
        @ini_set("mbstring.internal_encoding", self::$INTERNAL_ENCODING);
        setLocale(LC_ALL, self::$LOCALE);
        setlocale(LC_NUMERIC, self::$NUMERIC_LOCALE); //Разделителем между целой и дробной частью должна быть "точка"
        
        date_default_timezone_set(self::$TIMEZONE);
        mb_internal_encoding(self::$INTERNAL_ENCODING);
        
        self::initVars();
        self::checkPhpModules();
        error_reporting(self::$DETAILED_EXCEPTION ? E_ALL & ~E_STRICT : 0);
        
        self::fixMagicQuotes();
        self::SendHeader();
        self::defineVars();
                
        foreach (self::$include_list as $inc_file)
            include(self::$PATH.$inc_file);
            
        \RS\Language\Core::init(); //Активируем языковые функции, объявляем функцию t

        if (!empty($_SERVER["REMOTE_ADDR"])) {
            include(self::$PATH.'/core/system/sessions.inc.php'); //Если запуск не из коммандной строки, то активируем сессию            
        }
        include(self::$PATH.'/core/system/licenser.inc.php');        
        \RS\Event\Manager::init(); //Инициализируем события        
        \RS\Language\Core::initThemeLang(); //Подключаем языковые файлы темы
    }
    
    /**
    * Инициализирует составные переменные
    * 
    * @return void
    */
    protected static function initVars()
    {
        if (self::$PATH === null && self::$FOLDER === null) {
            self::$PATH = str_replace('\\', '/', realpath(__DIR__.'/../../../'));
            self::$FOLDER = @str_replace(rtrim(str_replace('\\', '/',$_SERVER['DOCUMENT_ROOT']),'/'), '', self::$PATH); 
            if (self::$FOLDER == self::$PATH) {
                //В случае, если папку не удается определить, считаем что система установлена в корне домена. 
                //Если это не так, то необходимо определить в файле /setup.inc.php свойства $FOLDER и $PATH
                self::$FOLDER = ''; 
            }
        }
        
        self::$ROOT = str_replace(self::$FOLDER.'@@', '', self::$PATH.'@@');
        self::$ZONE                      = self::getZone();
        self::$DOMAIN                    = @$_SERVER['HTTP_HOST'];
        
        self::loadPackageConfig();  // Подключение настроек комплектации
        self::loadConfig();         // Подключение настроек от инсталятора и панели администратора.
        self::loadLocalConfig();    // Подключение настроек для разработки                
        
        self::$SM_RELATIVE_TEMPLATE_PATH = self::$FOLDER.self::$SM_RELATIVE_TEMPLATE_PATH;
        self::$SM_TEMPLATE_PATH          = self::$ROOT.self::$SM_RELATIVE_TEMPLATE_PATH.'/';
        self::$SM_COMPILE_PATH           = self::$PATH.self::$SM_COMPILE_PATH.'/';
        self::$SM_CACHE_PATH             = self::$PATH.self::$SM_CACHE_PATH.'/';
        
        self::$RESOURCE_PATH             = self::$FOLDER.self::$RESOURCE_PATH; //Нужен в модуле шаблонов
        self::$JS_PATH                   = self::$FOLDER.self::$JS_PATH;
        self::$CSS_PATH                  = self::$FOLDER.self::$CSS_PATH;
        self::$IMG_PATH                  = self::$FOLDER.self::$IMG_PATH;
        
        self::$CACHE_FOLDER              = self::$PATH.self::$CACHE_FOLDER;
        self::$CACHE_TABLE_FOLDER        = self::$PATH.self::$CACHE_TABLE_FOLDER;
        
        self::$STORAGE_DIR               = self::$FOLDER.self::$STORAGE_DIR;
        self::$TMP_REL_DIR               = self::$FOLDER.self::$TMP_REL_DIR;
        self::$TMP_DIR                   = self::$ROOT.self::$TMP_REL_DIR;
    }
    
    /**
    * Возвращает текущий домен первого уровня
    * 
    * @return string
    */
    private static function getZone()
    {
        if (preg_match('/.*\.([^.]+?)$/', @$_SERVER['HTTP_HOST'], $match)) {
            return $match[1];
        }
    }
    
    
    /**
    * Загружает настройки из файла конфигурации
    * @return void
    */
    public static function loadConfig()
    {
        self::loadExternalFile(self::$PATH.'/config.auto.php');
    }
    
    /**
    * Загружает настройки характерные для комплектации CMS
    * 
    * @return void
    */
    public static function loadPackageConfig()
    {
        self::loadExternalFile(self::$PATH.'/package.inc.php');
    }
    
    /**
    * Загружает внешний конфигурационный файл php
    * 
    * @param string $file - php file для подключения
    * @return mixed
    */
    public static function loadExternalFile($file)
    {
        if (file_exists($file)) {
            if (function_exists('opcache_invalidate')) {
                opcache_invalidate($file, true);
            }
            $data = include($file);
            foreach($data as $key => $value) {
                if ($value !== null && property_exists(get_called_class(), $key)) {
                    self::$$key = $value;
                }
            }
        }
    }
    
    /**
    * Загружает локальные настройки конфигурации
    * @return void
    */
    public static function loadLocalConfig()
    {
        if (file_exists(self::$PATH.'/_local_settings.php')) {
            require(self::$PATH.'/_local_settings.php');
        }
    }
    
    /**
    * Возвращает все определенные свойства в виде массива
    * 
    * @return array
    */
    public static function varsAsArray()
    {
        return get_class_vars(get_called_class());
    }    
    
    /**
    * Регистрирует переменные в качестве констант, чтобы их можно было использовать в объявлении переменных других классов
    * 
    * @return void
    */
    protected static function defineVars()
    {
        $arr = self::varsAsArray();
        foreach ($arr as $key=>$val) {
            if (is_int($val) || is_string($val) || is_bool($val) || is_null($val)) {
                define($key, $val);
            }
        }
    }
    
    /**
    * Отравляет базовые заголовки
    * 
    * @return void
    */
    protected static function SendHeader()
    {
        header("Content-type: text/html; charset=utf-8");
    }    
    
    /**
    * Убираем magic quotes если эта опция включена
    * 
    * @return void
    */
    protected static function fixMagicQuotes()
    {
        if (get_magic_quotes_runtime()) {
            set_magic_quotes_runtime(false);
        }
    }
    
    /**
    * Проверяет наличие необходимых для запуска скрипта модулей
    * 
    * @return void
    */
    protected static function checkPhpModules()
    {
        if (self::$INSTALLED) return;

        $need_modules = array();        
        if (!function_exists('mcrypt_module_open')) {
            $need_modules[] = 'mcrypt';
        }
        if (!function_exists('filter_var')) {
            $need_modules[] = 'filter';
        }        
        if (!class_exists('\XMLReader', false)) {
            $need_modules[] = 'xmlreader';
        }
        if (!class_exists('\SimpleXMLElement', false)) {
            $need_modules[] = 'simplexml';
        }        
        
        if ($need_modules) {
            die('Some PHP modules are not installed: '.implode(',', $need_modules));
        }
    }
}
?>
