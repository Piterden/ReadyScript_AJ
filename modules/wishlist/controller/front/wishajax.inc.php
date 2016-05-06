<?php
namespace Wishlist\Controller\Front;

/**
* Фронт контроллер желаемых покупок
*/
class Wishajax extends \RS\Controller\AuthorizedFront
{
    public
        $api;

    // private
    //     $pageSize = 20;

    function init()
    {
        $this->api = new \Wishlist\Model\WishApi();
    }

    /**
     * Действие загрузки страницы
     * @return \RS\Controller\Result\Standart
     */
    function actionIndex()
    {
        if ($this->url->isPost()) {

        }

        $this->app->title->addSection(t('Список желаемых покупок'));
        $this->app->breadcrumbs->addBreadCrumb(t('Список желаний'));

        $config = $this->getModuleConfig();
        $page = $this->url->get('p', TYPE_INTEGER, 1);

        $user = \RS\Application\Auth::getCurrentUser();

        $this->api->setFilter('user_id', $this->user['id']);
        $wishes = $this->api->getList();
        $products = $this->api->getWishedProductsList($wishes);
        $added_ids = $this->api->getWishedProductIds($user['id']);

        //Передаем переменные в шаблон
        $this->view->assign(array(
            'products' => $products,
            'added_ids' => $added_ids,
            'total' => count($products),
        ));

        return $this->result->setTemplate('wishlist.tpl');
    }

}
