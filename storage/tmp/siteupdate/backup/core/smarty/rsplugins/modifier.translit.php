<?php
/**
* Модификатор транслит
*/
function smarty_modifier_translit($text)
{
    $result = call_user_func_array(['\RS\Helper\Transliteration', 'str2url'], [$text]);

    return $result;
}  
