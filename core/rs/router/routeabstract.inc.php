<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace RS\Router;
use \RS\Http;

abstract class RouteAbstract
{
    const
        CONTROLLER_PARAM = 'controller', //GET параметр, который задает имя контроллера
        ACTION_PARAM = 'Act', //GET параметр, который задает имя действия контроллера
        DEFAULT_ACTION = 'index'; //Действие контроллера по-умолчанию
    
    protected
        $id,
        $patterns = array(),
        $match,
        $defaults = array(),
        $is_admin = false,
        $hide = false,
        $description;
    
    protected static
        $http_request;
    
    /**
    * Конструктор абстрактного класса для маршрутов (тип: регулярные выражения)
    * 
    * @param string $id - идентификатор URI
    * @param string |array $pattern - Регулярное выражение для URI
    * @param array | null $defaults - значения по умолчанию для переменных из URI
    * @param string $description - Текстовое описание страницы по данному URI
    * @param bool $hide - скрывать маршрут в списках в административной панели. Рекомендуется для системных маршрутов.
    */
    function __construct($id, $patterns, $defaults, $description, $hide = false)
    {
        $this->id = $id;
        foreach((array)$patterns as $key=>$pattern) {
            $this->patterns[$key] = '/'.$pattern.'/i';
        }
        if ($defaults !== null) {
            $this->defaults = $defaults;
        }
        $this->description = $description;
        if (!self::$http_request) {
            self::$http_request = Http\Request::commonInstance();
        }
        $this->hide = $hide;
    }
    
    /**
    * Возвращает идентификатор маршрута
    */
    public function getId()
    {
        return $this->id;
    }
    
    /**
    * Возвращает true в случае если маршрут соответствует текущему URL
    * 
    * @param mixed $host
    * @param mixed $uri
    */
    public function match($host, $uri, $autoset = true)
    {
        //Исключаем каталог, в которой установлена система
        $folder = str_replace('/','\\/', \Setup::$FOLDER);
        $uri = preg_replace('/^'.$folder.'/', '', $uri);
        
        foreach($this->patterns as $pattern) {
            if (preg_match($pattern, $uri, $match)) {
                foreach($match as $key => $value) {
                    if (is_numeric($key)) {
                        unset($match[$key]);
                    }
                }
                $this->match = $match;
                if ($autoset) {
                    self::$http_request->setFromRouter($this->match, $this->defaults);
                }
                return true;
            }
        }
        return false;
    }
    
    /**
    * Возвращает объект URL
    * 
    * @return RS_Http_Request
    */
    public function getHttpRequest()
    {
        return self::$http_request;
    }
    
    /**
    * Возвращает имя класса контроллера, который соответствует данному URI
    */
    public function getController()
    {
        $minifed_str = $this->getHttpRequest()->get(self::CONTROLLER_PARAM, TYPE_STRING);
        //Генерируем имя класса контроллера
        if (preg_match('/^([^\-]+?)\-(.*)$/', $minifed_str, $match)) {
            return str_replace('-','\\', "-{$match[1]}-controller-{$match[2]}");
        }
    }
    
    /**
    * Возвращает имя метода контроллера, который соответствует данному URI
    */
    public function getAction()
    {
        return $this->getHttpRequest()->get(self::ACTION_PARAM, TYPE_STRING, self::DEFAULT_ACTION);
    }
    
    /**
    * Возвращает описание страницы для данного маршрута
    */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
    * Возвращает построенный URL по данному маршруту
    * 
    * @param mixed $params
    */
    public function buildUrl($params, $absolute = false)
    {
        $uri = '?'.http_build_query($params);
        
        if ($absolute) {
            $current_site = \RS\Site\Manager::getSite();
            $uri = $current_site ? $current_site->getAbsoluteUrl($uri) : \RS\Http\Request::commonInstance()->getSelfAbsoluteHost().$uri;
        }        
        return $uri;
    }
    
    /**
    * Возвращает регулярные выражения, заданные для маршрута
    * @return array
    */
    public function getPatterns()
    {
        return $this->patterns;
    }
    
    /**
    * Возвращает регулярные выражения, заданные для маршрута в читаемом виде
    * @return array
    */
    public function getPatternsView()
    {
        return $this->patterns;
    }    
    
    /**
    * Возвращает true, если данный url принадлежит административной панели 
    * 
    * @param mixed $bool
    */
    public function isAdmin($bool = null)
    {
        if ($bool !== null) $this->is_admin = $bool;
        return $this->is_admin;
    }    
    
    /**
    * Возвращает true, если этот маршрут - заглушка, иначе - false
    */
    public function isUnknown()
    {
        return false;
    }
    
    /**
    * Возвращает true, если маршрут скрытый
    */
    public function isHidden()
    {
        return $this->hide;
    }
        
}

