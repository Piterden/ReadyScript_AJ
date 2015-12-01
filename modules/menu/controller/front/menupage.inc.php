<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Menu\Controller\Front;
use \Menu\Model\Orm\Menu;

/**
* Фронт контроллер страницы-статьи, которая добавлена через меню
*/
class MenuPage extends \RS\Controller\Front
{
    function actionIndex()
    {
        //Передаем статью в шаблон, если нужно
        $menu_item = $this->url->parameters('menu_object');

        //Наполняем Хлебные крошки
        $api = new \Menu\Model\Api();
        $path = $api->queryParents($menu_item['id']);
        foreach($path as $item) {
            if ($item['public']) {
                $this->app->breadcrumbs->addBreadCrumb($item['title'], $item->getHref());
            }
        }        
        
        if ( $menu_item['typelink'] ==  Menu::TYPELINK_ARTICLE ) {  
            $article = $menu_item->getLinkedArticle();            
            if ($article) {
                $this->router->getCurrentRoute()->article_id = $article['id'];
                if ($debug_group = $this->getDebugGroup()) {
                    $create_href = $this->router->getAdminUrl('edit', array('id' => $article['id']), 'article-ctrl');
                    $debug_group->addDebugAction(new \RS\Debug\Action\Edit($create_href));
                    $debug_group->addTool('edit', new \RS\Debug\Tool\Edit($create_href));
                }
                $this->view->assign('article', $article);
                return $this->result->setTemplate('front_article.tpl');
            }
        }
        if ($menu_item['typelink'] == Menu::TYPELINK_EMPTY) {
            if ($menu_item['link_template']) {
                return $this->view->fetch($menu_item['link_template']);
            } else {
                return;
            }
        }
        
        $this->e404(t('Статья не найдена'));
    }
    
}

