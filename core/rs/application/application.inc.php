<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace RS\Application;

/**
* Singleton
* Класс отвечает за параметры текущей страницы
*/
class Application
{
    const
        JS_POSITION_HEADER = 'header',
        JS_POSITION_FOOTER = 'footer',
        DEFAULT_ERROR_PAGE_TPL = '%THEME%/exception.tpl', //Путь к шаблону страницы ошибок
        DEFAULT_ADMIN_ERROR_PAGE_TPL = '%SYSTEM%/admin/exception.tpl';  //Путь к шаблону страницы ошибок в административной части
    
    public
        /**
        * @var \RS\Application\Title
        */
        $title,
        /**
        * @var \RS\Application\Meta
        */
        $meta,
        /**
        * @var Headers
        */
        $headers,
        /**
        * @var BreadCrumbs Хлебные крошки для текущей страницы
        */
        $breadcrumbs,
        /**
        * @var \RS\Application\Block\Manager
        */
        $blocks;
        
    protected 
        static $instance;
    
    protected
    	$doctype = DOCTYPE, //Значение по умолчанию берем из \Setup
        $is_admin_zone,
        $body_class,
        $js_no_compress = array(),
        $css_no_compress = array(),
        $js_default_position = self::JS_POSITION_HEADER,
        $js = array(
            self::JS_POSITION_HEADER => array(),
            self::JS_POSITION_FOOTER => array()
        ),
        $jscode = array(),
        $css = array(),
        $js_vars = array(),
        $base_js,
        $base_css,
        $any_head_data = '',
        $head_attributes = array();
    
    public static function getInstance() 
    {
        if (!isset(self::$instance)) self::$instance = new self();
        return self::$instance;
    }
    
    protected function __construct() 
    {
        $this->title = new Title();
        $this->meta = new Meta();
        $this->headers = new Headers();
        $this->breadcrumbs = new BreadCrumbs();
        $this->blocks = new Block\Manager();
        
        $this->initThemePath();
        $this->is_admin_zone = \RS\Router\Manager::obj()->isAdminZone();
        $this->js_default_position = (\Setup::$JS_POSITION_FOOTER && !$this->is_admin_zone) ? self::JS_POSITION_FOOTER : self::JS_POSITION_HEADER;
        
        $this->addJsVar(array(
            'folder'   => (string)\Setup::$FOLDER
        ));
    }
    
    /**
    * Инициализирует переменные, зависящие от текущей темы оформления
    * @return Application
    */
    public function initThemePath()
    {
        $theme = \RS\Theme\Manager::getCurrentTheme('theme');
        $this->base_css = \Setup::$SM_RELATIVE_TEMPLATE_PATH.'/'.trim($theme.\Setup::$RES_CSS_FOLDER,'/').'/';
        $this->base_js = \Setup::$SM_RELATIVE_TEMPLATE_PATH.'/'.trim($theme.\Setup::$RES_JS_FOLDER,'/').'/';        
        
        return $this;
    }
    
    /**
    * Добавляет переменную для использования в JavaScript
    * 
    * @param integer | string | array $key
    * @param mixed $value
    * @return Application
    */
    public function addJsVar($key, $value = null)
    {
        if (is_array($key)) {
            $this->js_vars = $key + $this->js_vars;
        } else {
            $this->js_vars[$key] = $value;
        }
        return $this;
    }
    
    /**
    * Возвращает массив переменных, подготовленных для JavaScript
    * 
    * @return string данные в json формате
    */
    public function getJsonJsVars()
    {
        return json_encode($this->js_vars);
    }
    
    /**
    * Устанавливает позицию добавления скриптов по-умолчанию - в Footer
    * 
    * @param bool $bool - если true, то скипты по умолчанию добавляются в Footer, если иного не указано в конструкции добавления скрипта
    * @return void
    */
    public function setJsDefaultFooterPosition($bool)
    {
        $this->js_default_position = $bool ? self::JS_POSITION_FOOTER : self::JS_POSITION_HEADER;
    }
    
    /**
    * Подключить JavaScript к странице
    * 
    * @param string $jspath путь к js - файлу
    * @param string $name внутренне имя
    * @param string $basepath базовый путь к папке BP_ROOT, BP_THEME, BP_COMMON
    * @param string $no_compress - Не сжимать файл, даже если \Setup::$COMPRESS_JS установлено в true
    * @return Application
    */
    public function addJs($jspath, $name = null, $basepath = null , $no_compress = false, $params = array()) 
    {
        $jspath = $this->preparePath($jspath, $basepath, 'js');
        if (!isset($basepath)) $basepath = BP_THEME;
        if (!isset($name)) $name = $jspath;
        if ($no_compress) $this->js_no_compress[] = $name;
        switch($basepath)
        {
            case BP_COMMON:  $jspath = \Setup::$JS_PATH.'/'.$jspath; break;
            case BP_THEME: $jspath = $this->base_js.$jspath; break;
            //default BP_ROOT
        }
        
        $file = array($name => array(
                'file' => $jspath,
                'params' => $params
        ));
        
        $position = $this->js_default_position;
        if (!empty($params['header'])) $position = self::JS_POSITION_HEADER;
        if (!empty($params['footer'])) $position = self::JS_POSITION_FOOTER;
            
        //Удаляем скрипт из противоположной позиции
        $not_position = $position == self::JS_POSITION_FOOTER ? self::JS_POSITION_HEADER : self::JS_POSITION_FOOTER;
        unset($this->js[$not_position][$name]);
        
        //Добавляем скрипт в заданную позицию
        if (isset($params['unshift'])) {            
            $this->js[$position] = $file + $this->js[$position];
        } else {
            $this->js[$position][$name] = $file[$name];
        }
        
        return $this;
    }
    
    /**
    * Возвращает список JavaScript файлов, которые необходимо подключить
    * 
    * @return array
    */
    public function getJs($position = self::JS_POSITION_HEADER) 
    {
        if (!isset($this->js[$position])) return array();

        $js_array = $this->js[$position];

        if (\Setup::$COMPRESS_JS && !$this->is_admin_zone) {
            $compressJs = new Compress\Js($this->js[$position]);
            $js_array   = $compressJs->getCompressed($this->js_no_compress);
        }

        $result = \RS\Event\Manager::fire('getjs', array(
            'js_array' => $js_array
        ));

        $result = $result->getResult();

        return $result['js_array'];
    }
    
    /**
    * Устанавливает базовый путь к папке со скриптам
    * 
    * @param string $folder
    * @return string
    */
    public function setBaseJs($folder)
    {
        $this->base_js = $folder.'/';
    }
    
    /**
    * Добавить на страницу JavaScript код
    * 
    * @param mixed $name внутреннее название кода
    * @param mixed $jscode JavaScript код
    * @return \RS\Application
    */
    public function addJsCode($name, $jscode) 
    {
        $this->jscode[$name] = $jscode;
        return $this;
    }
    
    /**
    * Возвращает JavaScript код, который нужно добавить в HEAD секцию HTML
    * 
    * @return string
    */
    public function getJsCode() {
        return implode("\n",$this->jscode);
    }
    
    
    /**
    * Устанавливает путь к CSS по-умолчанию
    * 
    * @param string $folder
    * @return void
    */
    public function setBaseCss($folder)
    {
        $this->base_css = $folder.'/';
    }

    /**
    * Подключить CSS к странице
    * 
    * @param string $csspath путь к css - файлу
    * @param string $name внутренне имя
    * @param string $basepath базовый путь к папке BP_ROOT, BP_THEME, BP_COMMON
    * @param string $no_compress - Не сжимать файл, даже если \Setup::$COMPRESS_CSS установлено в true
    * @return Application
    */
    public function addCss($csspath, $name = null, $basepath = null, $no_compress = false, $params = array()) 
    {
        $csspath = $this->preparePath($csspath, $basepath, 'css');
        if (!isset($basepath)) $basepath = BP_THEME;
        if (!isset($name)) $name = $csspath;
        if ($no_compress) $this->css_no_compress[] = $name;
        
        switch($basepath)
        {
            case BP_COMMON:  $csspath = \Setup::$CSS_PATH.'/'.$csspath; break;
            case BP_THEME: $csspath = $this->base_css.$csspath; break;
            //default BP_ROOT
        }
        
        $file = array(
            $name => array(
                'file' => $csspath,
                'params' => $params
            )
        );

        if (isset($params['unshift'])) {            
            $this->css = $file + $this->css;
        } else {
            $this->css[$name] = $file[$name];
        }        
        
        return $this;
    }
    
    /**
    * Заменяет конструкцию %ИМЯ_МОДУЛЯ% на путь к папке CSS и JS соответствующего модуля.
    * basepath принудительно устанавливается в root
    * 
    * @param string $filepath - ссылка на CSS или JS файл
    * @param bool $basepath - базовый путь к папке
    * @param string $type - js или CSS
    * @return string
    */
    private function preparePath($filepath, &$basepath, $type)
    {
        if (preg_match('/^%(.*?)%(.+)$/u', $filepath, $match)) {
            $folders = \RS\Module\Item::getResourceFolders($match[1]);
            $basepath = BP_ROOT;
            $filepath = rtrim($folders['mod_'.$type], '/').$match[2];
        }
        return $filepath;
    }
    
    /**
    * Удаляет js файл по ключу, если он задан, или удаляет все вскрипты
    * 
    * @param mixed $name
    * @return Application
    */
    function removeJs($name = null)
    {
        if ($name === null) {
            $this->js = array();
        } else {
            unset($this->js[$name]);
        }
        return $this;
    }
    
    /**
    * Удаляет css файл по ключу, если он задан, или удаляет все вскрипты
    * 
    * @param mixed $name
    * @return Application
    */
    function removeCss($name = null)
    {
        if ($name === null) {
            $this->css = array();
        } else {
            unset($this->css[$name]);
        }
        return $this;
    }    
    
    /**
    * Возвращает массив CSS файлов, которые необходимо подключить на странице
    * Если включена опция \Setup::$COMPRESS_CSS, то CSS файлы будут объеденены и оптимизированы
    * 
    * @return array
    */
    public function getCss() 
    {
        $css_array = $this->css;

        if (\Setup::$COMPRESS_CSS && !$this->is_admin_zone) {
            $compressCss = new Compress\Css($this->css);
            $css_array = $compressCss->getCompressed($this->css_no_compress);
        }

        $result = \RS\Event\Manager::fire('getcss', array(
            'css_array' => $css_array
        ));

        $result = $result->getResult();

        return $result['css_array'];
    }
    
    /**
    * Задает DOCTYPE, который должен быть установлен
    * 
    * @param string $doctype
    * @return void
    */
    function setDoctype($doctype)
    {
    	$this->doctype = $doctype;
	}
	
    /**
    * Возвращает DOCTYPE, который должен быть установлен
    * 
    * @return string
    */
    function getDoctype()
    {
    	return $this->doctype;
	}	
    
    /**
    * Возвращает текстовую информацию, установленную с помощью addAnyHeadData
    */
    function getAnyHeadData()
    {
        return $this->any_head_data;
    }
    
    /**
    * Устанавливает произвольный текст в секцию HEAD
    * 
    * @param string $data
    * @param bool $append
    * @return Application
    */
    function setAnyHeadData($data, $append = true)
    {
        $this->any_head_data = (($append) ? $this->any_head_data : '').$data;
        return $this;
    }
    
    /**
    * Добавляет атрибут к секции head
    * 
    * @param string|array $key атрибут
    * @param string $value значение атрибута
    * @return Application
    */
    function addHeadAttribute($key, $value = null)
    {
        if (is_array($key)) {
            $this->head_attributes = $key;
        } else {
            $this->head_attributes[$key] = $value;
        }
        return $this;
    }
    
    /**
    * Возвращает атрибуты для тега head
    * 
    * @param bool $inline - Если true, возвращает строку, иначе массив
    * @return 
    */
    function getHeadAttributes($inline = false)
    {
        if ($inline) {
            $str = '';
            foreach($this->head_attributes as $key => $value) {
                $str .= $key.'="'.\RS\Helper\Tools::toEntityString($value).'"';
            }
            return $str;
        }
        return $this->head_attributes;
    }
    
    /**
    * Возвращает HTML код, который приостанавливает выполнение JavaScript кода до полной загрузки скриптов
    * @return string
    */
    function autoloadScripsAjaxBefore()
    {
        if (\RS\Http\Request::commonInstance()->isAjax()) {
            $tpl = new \RS\View\Engine();
            $tpl->assign('app', $this);
            return $tpl->fetch('%system%/ajaxscriptload_before.tpl');
        }
    }    
    
    /**
    * Возвращает HTML код ajax-загрузки JS и CSS файлов 
    * @return string
    */
    function autoloadScripsAjaxAfter()
    {
        if (\RS\Http\Request::commonInstance()->isAjax()) {
            $tpl = new \RS\View\Engine();
            $tpl->assign('app', $this);
            return $tpl->fetch('%system%/ajaxscriptload_after.tpl');
        }
    }
    
    /**
    * Устанавливает класс для тега body
    * 
    * @param mixed $class
    * @param bool $append - Если true, то класс будет добавлен, иначе заменен
    * @return void
    */
    function setBodyClass($class, $append = false)
    {
        if ($append) {
            $this->body_class .= ' '.$class;
        } else {
            $this->body_class = $class;
        }
    }
    
    /**
    * Возвращает класс для тега body
    * @return string
    */
    function getBodyClass()
    {
        return $this->body_class;
    }
    
    /**
    * Отображает страницу с ошибкой. Прекращает выполение скрипта
    * 
    * @param integer $status_code
    * @param string | null $comment
    * @param string | null $http_code_phrase
    * @return void
    */
    public function showException($status_code, $comment = null, $http_code_phrase = null)
    {
        $this->cleanOutput();
        $this->headers
            ->cleanHeaders()
            ->setStatusCode($status_code, $http_code_phrase)
            ->sendHeaders();
        
        $template = $this->is_admin_zone ? self::DEFAULT_ADMIN_ERROR_PAGE_TPL : self::DEFAULT_ERROR_PAGE_TPL;
        
        $tpl = new \RS\View\Engine();
        $tpl->assign(array(
            'error' => array(
                'code' => $status_code,
                'comment' => $comment
            ),
            'site' => \RS\Site\Manager::getSite(),
            'site_config' => \RS\Config\Loader::getSiteConfig()
        ));
        $tpl->display($template);
        exit;
    }
    
    /**
    * Очищает буфер вывода
    * @return void
    */
    public function cleanOutput()
    {
        for($i=0; $i<ob_get_level(); $i++) {
            ob_end_clean(); //Очищаем все что было подготовлено на вывод
        }
        header('Content-Encoding: no'); //Отменяем сообщение о том, что вывод сжимается gzip'ом (посланное ob_start("ob_gzhandler"))
    }    
    
    
}

