<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Article\Model\CommentType;

/**
* Тип комментария - коментарий к статье
* @ingroup Article
*/
class Article extends \Comments\Model\Abstracttype
{
    /**
    * Возвращает тип комментария
    */
    function getTitle()
    {
        return t('Комментарий к статье');
    }
    
    /**
    * Возвращает ссылку на объект в административной части
    * 
    * @return string
    */
    function getAdminUrl(\Comments\Model\Orm\Comment $comment)
    {
        return \RS\Router\Manager::obj()->getAdminUrl('edit', array('id' => $comment['aid']), 'article-ctrl');
    }    
    
    /**
    * Возвращает id товара, к которому необходимо привязать комментарий
    * 
    * @return integer
    */
    function getLinkId()
    {
        $route = \RS\Router\Manager::obj()->getCurrentRoute();
        if ($route->getId() == 'article-front-view') {
            if (isset($route->article_id)) {
                return $route->article_id;
            }
        }
        return false;
    }
    
    /**
    * Обновляет поле "рейтинг" у статьи
    * Вызывается при добавлении комментария
    */
    function onAdd(\Comments\Model\Orm\Comment $comment)
    {
        if ($comment['rate']) {
            $api    = new \Comments\Model\Api(); 
            $api->recountItemRatingByComment(new \Article\Model\Orm\Article(), $comment);
        }
        return true;
    }
    
    /**
    * Действие при удалении комментария
    */
    function onDelete(\Comments\Model\Orm\Comment $comment)
    {
        $api = new \Comments\Model\Api(); 
        $api->recountItemRatingByComment(new \Article\Model\Orm\Article(), $comment);
    }
}

