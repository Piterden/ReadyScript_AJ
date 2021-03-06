<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Article\Controller\Block;
use \RS\Orm\Type;

/**
* Блок-контроллер Вывод товаров прикреплённых к статье
*/
class ArticleProducts extends \RS\Controller\StandartBlock
{
    protected static
        $controller_title = 'Список товаров прикреплённых к статье',
        $controller_description = 'Отображает N товаров прикреплённых к статье';

    protected
        $default_params = array(
            'indexTemplate' => 'blocks/article/products.tpl',
        );   
    public 
        $api;

    function actionIndex()
    {
        $article_id = $this->router->getCurrentRoute()->article_id; //Получаем id товаров
        $this->api  = new \Catalog\Model\Api(); //Апи товаров
        
        if (!$this->view->cacheOn($article_id)->isCached($this->getParam('indexTemplate'))) 
        {
            $api     = new \Article\Model\Api();
            /**
            * @var \Article\Model\Orm\Article
            */
            $article = $api->getById($article_id);   
            if ($article) {
                $products = $article->getAttachedProducts();
                $this->view->assign(array(
                    'products' => $products,
                ));
            }
        }
        return $this->result->setTemplate( $this->getParam('indexTemplate') );
    }
}