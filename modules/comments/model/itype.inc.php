<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace Comments\Model;

/**
* Интерфейс классов описывающих тип комментариев
* @ingroup Comments
*/
interface IType
{
    /**
    * Возвращает тип комментария в читаемом виде, например: "комментарий к товарам"
    * 
    * @return string
    */    
    function getTitle();
    
    /**
    * Возвращает id объекта на текущей странице, к которому необходимо привязать комментарии
    * 
    * @return mixed
    */
    function getLinkId();
    
    /**
    * Возвращает путь к редактированию элемента в административной панели
    * 
    * @return string | bool(false)
    */
    function getAdminUrl(\Comments\Model\Orm\Comment $comment);
}

