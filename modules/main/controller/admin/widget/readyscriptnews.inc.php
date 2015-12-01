<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace Main\Controller\Admin\Widget;

/**
* Виджет - свободное простанство на диске
* @ingroup Main
*/
class ReadyScriptNews extends \RS\Controller\Admin\Widget
{
    protected
        $orderapi,
        $info_title = 'Новости компании ReadyScript',
        $info_description = 'Информация об обновлениях, новых продуктах компании, а также новости из мира IT';
    
    
    function actionIndex()
    {
        return $this->view->fetch('widget/readyscript_news.tpl');
    }
}