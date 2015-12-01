{addjs file="jquery.min.js" name="jquery" basepath="common" header=true}
{addjs file="ui.jquery.all.min.js" basepath="common"}
{addjs file="jquery.form.js" basepath="common"}
{addjs file="jquery.datetimeaddon.min.js" basepath="common"}
{addjs file="lab.min.js" basepath="common"}
{addjs file="jquery.tooltip.js" basepath="common"}
{addjs file="admindebug.js" basepath="common"}
{addjs file="debug.js" basepath="common"}
{addjs file="updatable.js" basepath="common"}
{addjs file="coreobject.js" basepath="common"}
{addjs file="tabs.js" basepath="common"}
{addjs file="jquery.rsframework.js" basepath="common"}
{addjs file="jquery.cookie.js" basepath="common"}
{addjs file="jstour/jquery.tour.js" basepath="common"}

{addcss file="admin/readyscript.ui/jquery-ui.css" basepath="common"}
{addcss file="common/debug.css" basepath="common"}
{addcss file="common/debug.tooltip.css" basepath="common"}
{addcss file="common/animate.css" basepath="common"}
{addcss file="common/tour.css" basepath="common"}
{addcss file="admin/debugstyle_reset.css" basepath="common"}
{addcss file="admin/debugstyle.css?v=7" basepath="common"}
{addcss file="admin/framework.css" basepath="common"}
<div id="debug-top-block" class="admin-style">
<div class="menuline">
        <div class="viewport relative">
            <a href="{adminUrl mod_controller=false do=false}" class="brand"></a>
            <div class="debug-mode-switcher">
                <div data-url="{$router->getUrl('main.admin', [Act => 'ajaxToggleDebug'])}" class="rs-switch {if $this_controller->getDebugGroup()}on{else}off{/if} crud-get">
                    <div class="rs-border">
                        <div class="rs-back"></div>
                        <div class="rs-handle"></div>
                    </div>
                </div>
                <p class="debugmode-text">{t}режим отладки{/t}</p>                 
            </div>                            
            
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
                    <a href="{adminUrl mod_controller="main-license" do=false}" class="action license-notice-icon" title="{$notice}"></a>
                {/if}                                    
                <a href="{$router->getUrl('main.admin', ["Act" => "cleanCache"])}" class="action clean-cache crud-get" title="{t}Очистить кэш{/t}"></a>
                <a href="{$router->getUrl('main.admin')}" class="action to-admin wide-element" title="{t}Перейти в админ. панель{/t}"><span class="wide-text">{t}Перейти в админ. панель{/t}</span></a>
                <a class="action start-tour wide-element" data-tour-id="welcome" title="{t}Обучение{/t}"><span class="wide-text">{t}Обучение{/t}</span></a>
            </div>
        </div>
    </div>
</div>
{$result_html}