<?php
namespace Wishlist\Controller\Block;
use \RS\Orm\Type;
 
/**
* Класс блочного контроллера "Список желаний". 
* Предназначен для вывода формы добавления или
* удаления желания в списках товаров.
*/
class WishActions extends \RS\Controller\StandartBlock
{
    protected static
        $controller_title = 'Блок действий желаний',
        $controller_description = 'Отображает форму добавления и удаления желаний';
     
    protected
        $default_params = array(
            'indexTemplate' => 'form/formaddwish.tpl',
            'product_id' => null,
        );

    public function getParamObject()
    {
        return parent::getParamObject()->appendProperty(array(
            'indexTemplate' => new Type\String(array(
                'description' => t('Tpl')
            ))
        ));
    }

    function actionIndex()
    {
        $product_id = $this->getParam('product_id');
        $method = $this->url->request('method', TYPE_STRING, false);

        $api = new \Wishlist\Model\WishApi(); // Создаем экземпляр API желаний
        $user = \RS\Application\Auth::getCurrentUser(); //
        $wish_array = $api->getCurrentWish($user['id'], $product_id); // Массив! с одним объектом
        $all_user_wishes_product_ids = $api->getWishedProductIds($user['id']);

        // Если это POST авторизованного юзера и есть id продукта
        if ($this->isMyPost() && $api->checkAuth() && $product_id !== null) {
            $product = new \Catalog\Model\Orm\Product($product_id);

            if ($method == 'del') { // Если метод del - удаляем желание
                foreach ($wish_array as $wish) {
                    if ($api->deleteWish($wish['id'])) {
                        $this->refreshPage();
                    }
                    else {
                        $this->view->assign('error', $api->getElement()->getLastError());
                        return false;
                    }
                }
            } 
            elseif ($method == 'add') { // Если метод add - добавяем желание
                //$product = array($product); // Для создания ошибки
                if ($api->addWish($product, $user)) {
                    $this->refreshPage();
                }
                else {
                    $this->view->assign('error', $api->getElement()->getLastError());
                    return false;
                }
            }
        }

        $this->view->assign(array(
            'product_id' => $product_id,
            'method' => $method,
            'current_wish' => $current_wish,
        ));

        if (in_array($wish_array[0]['product_id'], $all_user_wishes_product_ids)) {
            $index_template = 'form/formdelwish.tpl';
        } 
        else {
            $index_template = 'form/formaddwish.tpl';
        }
        
        return $this->result->setTemplate( $index_template );
    }
}