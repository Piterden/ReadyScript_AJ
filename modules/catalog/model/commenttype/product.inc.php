<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace Catalog\Model\CommentType;

/**
* Тип комментария - коментарий к товару
* @ingroup Catalog
*/
class Product extends \Comments\Model\Abstracttype
{
    /**
    * Возвращает тип комментария
    */
    function getTitle()
    {
        return t('Комментарий к товарам');
    }
    
    /**
    * Возвращает id товара, к которому необходимо привязать комментарий
    * 
    * @return integer
    */
    function getLinkId()
    {
        $route = \RS\Router\Manager::obj()->getCurrentRoute();
        if ($route->getId() == 'catalog-front-product') {
            if (isset($route->product)) {
                return $route->product['id'];
            }
        }
        return false;
    }
    
    /**
    * Возвращает ссылку на объект в административной части
    * 
    * @return string
    */
    function getAdminUrl(\Comments\Model\Orm\Comment $comment)
    {
        return \RS\Router\Manager::obj()->getAdminUrl('edit', array('id' => $comment['aid']), 'catalog-ctrl');
    }
    
    /**
    * Обновляет поле "рейтинг" у товара
    * Вызывается при добавлении комментария
    * 
    * @param \Comments\Model\Orm\Comment $comment - объект комментария
    */
    function onAdd(\Comments\Model\Orm\Comment $comment)
    {
        if ($comment['rate']) {  
            $api = new \Comments\Model\Api(); 
            $api->recountItemRatingByComment(new \Catalog\Model\Orm\Product(), $comment);
        }
        return true;
    }
    
    /**
    * Действие при удалении комментария
    */
    function onDelete(\Comments\Model\Orm\Comment $comment)
    {
        $api = new \Comments\Model\Api(); 
        $api->recountItemRatingByComment(new \Catalog\Model\Orm\Product(), $comment);
    }
}

