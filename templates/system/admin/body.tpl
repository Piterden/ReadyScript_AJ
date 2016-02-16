{addjs file="jquery.rsframework.js"}
{addjs file="jquery.deftext.js" basepath="common"}
{addjs file="jquery.autotranslit.js"}
{addjs file="jquery.messenger.js"}
{addjs file="jstour/jquery.tour.js" basepath="common"}
{addcss file="readyscript.ui/jquery-ui.css?v=2"}
{addcss file="debugstyle.css?v=8"}
{addcss file="style.css?v=2"}
{addcss file="framework.css"}
{addcss file="common/animate.css" basepath="common"}
{addcss file="common/tour.css" basepath="common"}
{$app->setBodyClass('admin-body admin-style')}
<div id="header">    
    {if $smarty.const.STRONG_NOTICE}
        <div class="strong-notice">
            {$smarty.const.STRONG_NOTICE}
        </div>
    {/if}
    <div class="menuline">
        <div class="viewport relative">
            <a href="{adminUrl mod_controller=false do=false}" class="brand"></a>
            {moduleinsert name="\Menu\Controller\Admin\View"}
            <div class="action-zone">
                {modulegetvars name="\Site\Controller\Admin\BlockSelectSite" var="sites"}
                <div class="panel-menu">
                    <div class="current">{$sites.current.title|teaser:"27"}</div>
                    <ul class="drop-down">
                        {foreach $sites.sites as $site}
                            {if $site.id != $sites.current.id}
                            <li><a href="{$router->getUrl('main.admin', ['Act' => 'changeSite', 'site' => $site.id])}">{$site.title}</a></li>
                            {/if}
                        {/foreach}
                        <li class="user-section">
                            <a href="{adminUrl mod_controller="users-ctrl" do="edit" id=$current_user.id}">{$current_user->getFio()}</a>
                            <a href="{$router->getUrl('main.admin', [Act => 'logout'])}">{t}Выход{/t}</a>
                        </li>
                    </ul>
                </div>
                {if $notice=__GET_ADMIN_NOTICE()}
                    <a {if !defined('CLOUD_UNIQ')}href="{adminUrl mod_controller="main-license" do=false}"{/if} class="action license-notice-icon" title="{$notice}"></a>
                {/if}                                    
                <a href="{$router->getUrl('main.admin', ["Act" => "cleanCache"])}" class="action clean-cache crud-get" title="{t}Очистить кэш{/t}"></a>
                <a href="{$SITE->getRootUrl(true)}" class="action to-site wide-element" title="{t}Перейти на сайт{/t}"><span class="wide-text">{t}перейти на сайт{/t}</span></a>
                {if ModuleManager::staticModuleExists('marketplace')}
                <a href="{$router->getAdminUrl(false, [], 'marketplace-ctrl')}" class="action to-marketplace wide-element" title="{t}Маркетплейс{/t}"><span class="wide-text">{t}Маркетплейс{/t}</span></a>
                {/if}
            </div>
        </div>
    </div>
</div>
{$app->blocks->getMainContent()}