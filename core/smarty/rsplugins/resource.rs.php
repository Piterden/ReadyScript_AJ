<?php
/**
* ReadyScript Resource File Extension
* Расширение стандартного типа ресурсов "file".
* Добавляем поддержку в пути к шаблону включений %SYSTEM%, %THEME%, %ИМЯ_МОДУЛЯ%
* 
*/
class Smarty_Resource_RS extends Smarty_Internal_Resource_File {

    protected function buildFilepath(Smarty_Template_Source $source, Smarty_Internal_Template $_template=null)
    {
        //Если в пути к шаблону присутствует %имя модуля%, то заменяем на пути к шаблонам модуля
        if (preg_match('/^%(.*?)%(.+)$/u',$source->name, $match))
        {
            $theme = \RS\Theme\Manager::getCurrentTheme('theme');
            $match[2] = ltrim($match[2], '/'); //Удаляем первый слеш из имени шаблона, чтобы можно было использовать '%ИМЯ_МОДУЛЯ%/ИМЯ_ШАБЛОНА'
            
            if (strtoupper($match[1]) == 'SYSTEM') {
                $in_custom_path = \Setup::$SM_TEMPLATE_PATH.'mysystem/'.$match[2];
                if (file_exists($in_custom_path)) {
                    $source->name = $in_custom_path;
                } else {
                    $source->name = \Setup::$SM_TEMPLATE_PATH.'system/'.$match[2];
                }
            }
            elseif (strtoupper($match[1]) == 'THEME') {
                //Меняем %THEME% на папку с текущей темой
                $source->name = \Setup::$SM_TEMPLATE_PATH.$theme.'/'.$match[2];
            } else {
                //Если указано %ИМЯ_МОДУЛЯ%
                $in_theme_path = \Setup::$SM_TEMPLATE_PATH.$theme.\Setup::$MODULE_WATCH_TPL.'/'.strtolower($match[1]).'/'.$match[2];
                if (file_exists($in_theme_path)) {
                    $source->name = $in_theme_path;
                } else {
                    $source->name = \Setup::$PATH.\Setup::$MODULE_FOLDER.'/'.$match[1].\Setup::$MODULE_TPL_FOLDER.'/'.$match[2];
                }
            }
        }
        
        return parent::buildFilepath($source, $_template);
    }
    
    /**
     * Сделаем читаемые имена компилированных шаблонов.
     *
     * @param Smarty_Template_Compiled $compiled  compiled object
     * @param Smarty_Internal_Template $_template template object
     */
    public function populateCompiledFilepath(Smarty_Template_Compiled $compiled, Smarty_Internal_Template $_template)
    {
        if (DS != '/') {
            //windows
            $_path = str_replace('/', '\\', \Setup::$PATH);
        } else {
            //other
            $_path = str_replace('\\', '/', \Setup::$PATH);
        }
        
        $_path = mb_strtolower($_path);
        $uniq_filename = str_replace($_path, '', mb_strtolower($_template->source->filepath));
        $uniq_filename = trim(preg_replace('![^\w\|]+!', '_', $uniq_filename),'_');
        
        $_compile_dir = $_template->smarty->getCompileDir();
        
        // caching token
        $_cache = ($_template->caching) ? '.cache' : '';
        $compiled->filepath = $_compile_dir . $uniq_filename . '.' . $compiled->source->type . $_cache . '.tpl.php';
    }
    
    
    
}

