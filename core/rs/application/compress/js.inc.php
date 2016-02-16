<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace RS\Application\Compress;

class Js extends AbstractCompress
{
    const
        COMPRESS_TIDY = 2;
        
    function __construct(array $js_list)
    {
        parent::__construct($js_list, \Setup::$COMPRESS_JS_PATH, '.js');
    }

    function compress($js_source, $output_file)
    {
        $output = false;
        if (\Setup::$COMPRESS_JS == self::COMPRESS_TIDY) {
            try {
                $output = JsShrinkMinifer::minify($js_source, array('flaggedComments' => false));
            } catch (Exception $e) {}
        }
        
        if ($output === false) $output = $js_source;
        
        return file_put_contents($output_file, $output);
    }
}



