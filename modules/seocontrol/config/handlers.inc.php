<?php
namespace SeoControl\Config;
use \RS\Router;

class Handlers extends \RS\Event\HandlerAbstract
{
    function init()
    {
        $this
            ->bind('controller.beforewrapoutput')
            ->bind('getmenus');
    }
    
    public static function getMenus($items)
    {
        $items[] = array(
                'title' => 'SEO контроль',
                'alias' => 'seocontrol',
                'link' => '%ADMINPATH%/seocontrol-ctrl/',
                'typelink' => 'link',
                'parent' => 'modules'
            );
        return $items;
    }   
    
    public static function controllerBeforeWrapOutput(\RS\Controller\AbstractController $controller) 
    {        
        if (!\RS\Router\Manager::obj()->isAdminZone()) {
            $api = new \SeoControl\Model\Api();
            $rule = $api->getRuleForUri(\RS\Http\Request::commonInstance()->server('REQUEST_URI'));
            if ($rule['meta_title']) {
                $controller->app->title->clean()->addSection($rule['meta_title']);
            }
            if ($rule['meta_keywords']) {
                $controller->app->meta->cleanMeta('keywords')->addKeywords($rule['meta_keywords']);
            }
            if ($rule['meta_description']) {
                $controller->app->meta->cleanMeta('description')->addDescriptions($rule['meta_description']);
            }
        }
    }

    
}