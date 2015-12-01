<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace RS\Event;
use RS\Cache\Manager as CacheManager;
use RS\Module\Manager as ModuleManager;

/**
* Класс обработки событий. Во время инициализации системы событий (выполняется в \Setup::init),
* происходит попытка запуска метода \ИмяМодуля\Config\MyHandlers::init 
* или \ИмяМодуля\Config\Handlers::init у каждого модуля с целью собрать подписчиков на обработку событий. 
* Позже, при генерации события, подписчики в порядке очереди получают управление для обработки события
*/
class Manager 
{
    const
        SYSTEM_MODULE = 'main',
        USER_CALLBACK_CLASS = '\Config\MyHandlers',
        DEFAULT_CALLBACK_CLASS = '\Config\Handlers';
    
    protected static 
        $initialized = false,
        $closure = 0,
        $base = array();
    
    protected function __construct() {}
    
    /**
    * Инициализирует класс событий.
    */
    public static function init()
    {
        $is_admin_zone = \RS\Router\Manager::obj()->isAdminZone();
        if (\RS\Site\Manager::getSite() !== false) {
            self::$base = CacheManager::obj()
                            ->expire(0)
                            ->request(array(__CLASS__, 'loadBase'), $is_admin_zone);
        
            //Сбрасываем описание класса пользователя
            \Users\Model\Orm\User::destroyClass();                            
        }
    }
    
    /**
    * Обходит модули и загружает базу слушателей событий.
    */
    public static function loadBase()
    {
        $module_manager = new ModuleManager();
        $active_modules = $module_manager->getActiveList();
        
        foreach($active_modules as $module) {
            $module->initHandlers();
        }
        
        self::sortByPriority();
        self::$initialized = true;     
        return self::$base;
    }
    
    /**
    * Возвращает true, если все подписчики подписались на события
    * 
    * @return bool
    */
    public static function isInitialized()
    {
        return self::$initialized;
    }
    
    /**
    * Нормализует название события
    * 
    * @param string $event - Имя события
    * @return string
    */
    protected static function prepare($event)
    {   
        return strtolower($event);
    }
    
    /**
    * Устанавливает слушателя на событие
    * 
    * @param string | object $module Название модуля. Например: MSystem
    * @param string $event Событие
    * @param callback | object | string $callback_class - Имя класса обработчика события или callback для вызова
    * @param string $callback_method Имя статического метода класса обработчика события
    * @return bool
    */
    public static function bind($event, $callback_class, $callback_method = null, $priority = 10)
    {
        $event = self::prepare($event);

        if (is_object($callback_class)) {
            if ($callback_class instanceof Closure) {
                $callback = $callback_class;
                $callback_class = 'closure_'.self::$closure++;
            } else {
                if ($callback_class instanceof \RS\Event\HandlerAbstract) {
                    $callback_class = get_class($callback_class);
                } else {
                    $module = \RS\Module\Item::nameByObject($callback_class);
                    $callback_class = class_exists($module.self::USER_CALLBACK_CLASS) ? $module.self::USER_CALLBACK_CLASS : $module.self::DEFAULT_CALLBACK_CLASS;
                }
                
                if ($callback_method === null) $callback_method = str_replace(array('.','-'), '', $event);
                $callback = array($callback_class, $callback_method);
            }
        } elseif (is_array($callback_class)) {
            $callback = $callback_class;
            $callback_class = is_object($callback_class[0]) ? get_class($callback_class[0]) : $callback_class;
            $callback_method = $callback_class[1];
        } else {
            if ($callback_method === null) $callback_method = str_replace(array('.','-'), '', $event);
            $callback = array($callback_class, $callback_method);
        }

        self::$base[$event][$callback_class.'.'.$callback_method] 
            = array('callback' => array($callback_class, $callback_method), 'priority' => $priority );
        return true;
    }
    
    /**
    * Сортирует обработчики событий по приоритетам
    * 
    * @return void
    */
    public static function sortByPriority()
    {
        foreach(self::$base as &$one) {
            uasort($one, array(__CLASS__, 'cmpItems'));
        }
    }
    
    /**
    * Сравнивает два приоритета и возвращает, который из них больше
    * 
    * @param array $a
    * @param array $b
    * @return integer
    */
    public static function cmpItems($a, $b)
    {
        if ($a['priority'] == $b['priority']) {
            return 0;
        }
        return ($a['priority'] > $b['priority']) ? -1 : 1;
    }
    
    
    /**
    * Удаляет слушателя у события
    * 
    * @param string $event Событие
    * @param string $callback_class Имя класса обработчика события
    * @param string $callback_method Имя статического метода класса обработчика события
    */
    public static function unbind($event = null, $callback_class = null, $callback_method = null)
    {
        $event = self::prepare($event);
        
        if ($callback_method && $callback_class) {
            unset(self::$base[$event][$callback_class.'.'.$callback_method]);
        } elseif ($event) {
            unset(self::$base[$event]);
        }
    }
    
    /**
    * Возвращает список слушателей события
    * 
    * @param string $event Событие
    * @return array
    */
    public static function getListeners($event = null)
    {
        if ($event === null) {
            return self::$base;
        }
        return isset(self::$base[$event]) ? self::$base[$event] : array();
    }    
    
    /**
    * Вызывает событие. Сообщает об этом слушателям, передает каждому слушателю результат выполнения предыдущего в виде параметров
    * 
    * @param string $event Имя события
    * @param mixed $params Параметры, которые будут переданы слушателям события в качестве аргументов.
    * @return Result
    */
    public static function fire($event, $params = null)
    {
        $event = self::prepare($event);

        $original_params = $params;
        $this_event = new Event($event, null);
            if (isset(self::$base[$event])) 
            {
                $params_type = gettype($params);            
                foreach(self::$base[$event] as $event_params) 
                {
                    $callback = $event_params['callback'];
                    if (is_callable($callback)) 
                    {
                        $new_params = call_user_func($callback, $params, $this_event);
                        if ($new_params !== null) {
                            $params = $new_params;
                            if (gettype($params) != $params_type) {
                                throw new Exception('Обработчик '.$callback[0].'::'.$callback[1]." события $event должен вернуть значение того же типа, что и 'params' ($params_type) или NULL");
                            }
                        }
                        if ($this_event->isStopped()) break;
                    } else {
                        throw new Exception(t("Не найден обработчик '%0' события '%1'", array(implode('::', $callback), $event)));
                    }
                }
            }
        return new Result($original_params, $params, $this_event);
    }
    
    /**
    * Возвращает true, если имеются подписчики на событие $event
    * 
    * @param string $event
    * @return bool
    */
    public static function issetHandlers($event)
    {
        return isset(self::$base[$event]);
    }
}
