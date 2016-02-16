<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Sitemap\Controller\Front;

class Sitemap extends \RS\Controller\Front
{
    function actionIndex()
    {
        $this->wrapOutput(false);
        $site_id = $this->url->request('site_id', TYPE_INTEGER);
        $api = new \Sitemap\Model\Api($site_id);
        $api->sitemapToOutput();
        return;
    }
}
?>
