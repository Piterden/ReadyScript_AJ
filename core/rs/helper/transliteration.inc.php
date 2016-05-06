<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace RS\Helper;

class Transliteration
{
    /**
    * Функция транслитерации русских символов
    * 
    * @param string $string - строка из которой будем преобразовывать 
    * @param boolean $to_lower_case - флаг перевода в нижний регистр. По умолчанию true. 
    * @return string
    */
    public static function rus2translit($string, $to_lower_case = true)
    {

        $converter = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v',
            'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
            'ь' => '', 'ы' => 'y', 'ъ' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
            'А' => 'A', 'Б' => 'B', 'В' => 'V',
            'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
            'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
            'И' => 'I', 'Й' => 'Y', 'К' => 'K',
            'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U',
            'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
            'Ь' => '', 'Ы' => 'Y', 'Ъ' => '',
            'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
        );
        
        if ($to_lower_case){
            $string = mb_strtolower($string);
        }

        return strtr($string, $converter);
    }

    /**
    * Переводит строку в url вид, встречая русские буквы переводит в английские
    * 
    * @param string $str            - строка из которой будем преобразовывать 
    * @param boolean $to_lower_case - флаг перевода в нижний регистр. По умолчанию true. 
    * @param integer|boolean $max_length - false - длинна не обрезается, либо значение количества символов, до которого обрезать  
    * @return string
    */
    public static function str2url($str, $to_lower_case = true, $max_length = false)
    {

        // переводим в транслит
        // и в нижний регистр, если нужно
        $str = Transliteration::rus2translit($str,$to_lower_case);
      
        // заменям все ненужное нам на "-"

        $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);

        // удаляем начальные и конечные '-'

        $str = trim($str, "-");
        
        if ($max_length && mb_strlen($str)>$max_length){
           $str = mb_substr($str, 0, $max_length); 
        }

        return $str;
    }

}