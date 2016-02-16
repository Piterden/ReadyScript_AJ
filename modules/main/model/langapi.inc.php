<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Main\Model;

class LangApi extends \RS\Module\AbstractModel\BaseModel
{
    
    /**
    * Метод, создающий файлы локализации по всему проекту
    * 
    * @param mixed $lang
    */
    static public function createLangFiles($lang)
    {
        // Создаем файлы локализациия для всех модулей
        foreach(glob(\Setup::$PATH.'/modules/*', GLOB_ONLYDIR) as $one){
            self::createLangFilesForDirectory($one, $one.'/view/lang/'.$lang.'/messages.lng.php', $one.'/view/lang/'.$lang.'/messages.js.php');
        }

        // Создаем файлы локализациия для всех тем оформления
        foreach(glob(\Setup::$PATH.'/templates/*', GLOB_ONLYDIR) as $one){
            self::createLangFilesForDirectory($one, $one.'/resource/lang/'.$lang.'/messages.lng.php', $one.'/lang/'.$lang.'/messages.js.php');
        }
        
        $sys_lang_dir = \Setup::$PATH.'/resource/lang';
        // Создаем файлы локализации для кода, находящегося в папке [core]
        self::createLangFilesForDirectory(\Setup::$PATH.'/core', $sys_lang_dir.'/'.$lang.'/messages.lng.php', $sys_lang_dir.'/'.$lang.'/messages.js.php');
        // Создаем файлы локализации для кода, находящегося в папке [resources]
        self::createLangFilesForDirectory(\Setup::$PATH.'/resource', $sys_lang_dir.'/'.$lang.'/messages.lng.php', $sys_lang_dir.'/'.$lang.'/messages.js.php');
        // Создаем файлы локализации для кода, находящегося в папке [templates/system]
        self::createLangFilesForDirectory(\Setup::$PATH.'/templates/system', $sys_lang_dir.'/'.$lang.'/messages.lng.php', $sys_lang_dir.'/'.$lang.'/messages.js.php');
    }
    
    static public function createLangFilesForDirectory($directory_path, $php_strings_output_file, $js_strings_output_file)
    {
        // Регулярное выражение для поиска PHP функции t()
        $patterns = array(
            '/\Wt\s*?\(\s*?\'(.*?)\'\s*?[,\)]/s',
            '/\Wt\s*?\(\s*?\"(.*?)\"\s*?[,\)]/s',
        );
        $php_strings = \Main\Model\LangApi::getStringsFromDir($directory_path, array('php'), $patterns);

        // Регулярное выражение для поиска tpl тэга {t} {/t}
        $patterns = array(
            '/\{t(?:\s+?.*?)?}(.+?)\{\/t\}/s',
        );
        
        $tpl_strings = \Main\Model\LangApi::getStringsFromDir($directory_path, array('tpl'), $patterns);
        
        $php_strings = array_merge($php_strings, $tpl_strings);

        // Регулярное выражение для поиска JS функции lang.t()
        $patterns = array(
            '/\Wlang\.t\s*?\(\s*?\'(.*?)\'\s*?[,\)]/s',
            '/\Wlang\.t\s*?\(\s*?\"(.*?)\"\s*?[,\)]/s',
        );
        $js_strings = \Main\Model\LangApi::getStringsFromDir($directory_path, array('js', 'tpl'), $patterns);
        
        @mkdir(dirname($php_strings_output_file), 0777, true);
        @mkdir(dirname($js_strings_output_file), 0777, true);
        array_unique($php_strings);
        array_unique($js_strings);
        if(!empty($php_strings)) $php_strings = array_combine($php_strings, $php_strings);
        if(!empty($js_strings)) $js_strings = array_combine($js_strings, $js_strings);
        
        // Сохраняем старые значения переведенных строк, котрые уже были сделаны в файле перевода
        if(file_exists($php_strings_output_file)){
            $old_strings = include($php_strings_output_file);
            if(is_array($old_strings)){
                $php_strings = array_merge($php_strings, $old_strings);
            }
        }

        // Сохраняем старые значения переведенных строк, котрые уже были сделаны в файле перевода
        if(file_exists($js_strings_output_file)){
            $old_strings = include($js_strings_output_file);
            if(is_array($old_strings)){
                $js_strings = array_merge($js_strings, $old_strings);
            }
        }
        
        file_put_contents($php_strings_output_file, '<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/ return '.var_export($php_strings, true).';');
        file_put_contents($js_strings_output_file, '<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/ return '.var_export($js_strings, true).';');
        
    }



    /**
    * Получить все строки из директории
    * 
    * @param mixed $directory_path путь к папке
    * @param mixed $extensions
    * @param mixed $patterns
    */
    static public function getStringsFromDir($directory_path, array $extensions, array $patterns)
    {
        $ret = array();
        
        foreach($extensions as $ex){
            
            foreach(glob($directory_path.'/*.'.$ex) as $one){
                if(preg_match('/tpl\.php$/', $one)) continue;
                $ret = array_merge($ret, self::getStringsFromFile($one, $patterns));
            }
        }
        
        $dirs = glob($directory_path.'/*', GLOB_ONLYDIR);
        foreach($dirs as $one){
            if(is_dir($one)){
                $substrings = self::getStringsFromDir($one, $extensions, $patterns);
                $ret = array_merge($ret, $substrings);
            }
        }
        
        return $ret;
    }
    
    static public function getStringsFromFile($file_path, array $patterns)
    {
        $file_content = file_get_contents($file_path);
        $ret = array();
        foreach($patterns as $one){
            preg_match_all($one, $file_content, $matches);
            $ret = array_merge($ret, $matches[1]);
        }
        return $ret;
    }
    
}
?>
