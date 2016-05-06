<?php
namespace SeoControl\Model\Orm;
use \RS\Orm\Type;

/**
* Класс объектов одного правила переназначения SEO параметров
*/
class SeoRule extends \RS\Orm\OrmObject
{
    protected static
        $table = 'seocontrol';
    
    function _init()
    {
        parent::_init()->append(array(
            'site_id' => new Type\CurrentSite(),
            'url_pattern' => new Type\Varchar(array(
                'description' => t('Маска URL'),
                'hint' => t('Регулярное выражение (PCRE) которое сравнивается с текущим URI. Слеш экранируется автоматически, остальные символы(согласно правилам PCRE) необходимо экранировать обратным слешем вручную. Например:<br>'.
                    '<strong>/catalog/holodilnik/</strong> - маска страницы, URI которой содержит этот текст<br>'.
                    '<strong>^/$</strong> - маска главной страницы<br>'.
                    '<strong>^/catalog/klyuchi/</strong> - маска страницы категории товара "Ключи"<br>'.
                    '<strong>^/catalog/.*?/\?p=2</strong> - маска второй страницы любой категории')
            )),
            'meta_title' => new Type\Varchar(array(
                'maxLength' => 2000,
                'description' => t('Заголовок')
            )),
            'meta_keywords' => new Type\Varchar(array(
                'maxLength' => 1000,
                'description' => t('Ключевые слова')
            )),
            'meta_description' => new Type\Varchar(array(
                'maxLength' => 1000,
                'viewAsTextarea' => true,
                'description' => t('Описание')
            )),
            'seo_text' => new Type\Richtext(array(
                'description' => t('SEO текст (допустим HTML)')
            ))
        ));
    }
    
    /**
    * Возвращает true, если маска текущего правила соответствует заданному uri
    * 
    * @param string $uri - URI
    */
    function match($uri)
    {
        $pattern = htmlspecialchars_decode($this['url_pattern']);
        $pattern = str_replace('/', '\/', $pattern);
        return preg_match('/'.$pattern.'/u', $uri);
    }
    
    /**
    * Возвращает HTML SEO текст
    * 
    * @return string
    */
    function getHtmlSeoText()
    {
        return htmlspecialchars_decode($this['seo_text']);
    }
}
?>