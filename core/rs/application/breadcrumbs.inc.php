<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace RS\Application;

/**
* Класс отвечает за хранение хлемных крошек для текущей страницы
*/
class BreadCrumbs
{
    protected
        $add_main = true,
        $breadcrumbs = array();

    /**
    * Устанавливает, добавлять ли ссылку на главную страницу сайта
    * 
    * @param bool $bool - если true, то ссылка будет добавлена в начале массива
    * @return BreadCrumbs
    */
    function mainLink($bool)
    {
        $this->add_main = $bool;
        return $this;
    }
        
    /**
    * Добавляет секцию в хлебные крошки
    *         
    * @param string $title Название секции
    * @param string $href Ссылка
    * @param integer|null $position Порядковый номер крошки в списке. Если Null, то добавляется в конец
    * @return BreadCrumbs
    */
    function addBreadCrumb($title, $href = null, $position = null)
    {
        $item = array(
            'title' => $title,
            'href' => $href
        );
        
        if ($position !== null) {
            $this->breadcrumbs = array_merge(array_slice($this->breadcrumbs, 0, $position), array($item), array_slice($this->breadcrumbs, $position));
        } else {
            $this->breadcrumbs[] = $item;
        }
        return $this;
    }
    
    /**
    * Устанавливает все секции хлебныйх крошек 
    * 
    * @param array $breadcrumbs массив с хлебными 
    * @return BreadCrumbs
    */
    function setBreadCrumbs(array $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
        return $this;
    }
    
    /**
    * Возвращает массив с секциями хлебных крошек для текущей страницы
    * 
    * @param mixed $add_main
    * @return BreadCrumbs
    */
    function getBreadCrumbs()
    {
        $main = array(array(
            'title' => t('Главная'),
            'href' => \RS\Site\Manager::getSite()->getRootUrl()
        ));
        if (empty($this->breadcrumbs)) return array();        
        return $this->add_main ? array_merge($main, $this->breadcrumbs) : $this->breadcrumbs;
    }
}

?>
