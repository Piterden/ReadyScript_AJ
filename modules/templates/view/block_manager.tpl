{addjs file="{$mod_js}jquery.blockeditor.js" basepath="root"}
{addcss file="{$mod_css}manager.css" basepath="root"}
{addcss file="%templates%/bootstrap.css" basepath="common"}
{addcss file="common/960gs/960.css" basepath="common"}
{if !$url->isAjax()}
<div class="crud-ajax-group">
    <div id="content">
        <div class="viewport">
            <div class="updatable" data-url="{adminUrl context=$context}">
{/if}
                <div id="clienthead">
                    <div class="c-head  top-p">
                        <h2>{$elements.formTitle} {if isset($elements.topHelp)}<a class="help">?</a>{/if}</h2>
                        <div class="buttons">
                            {if $elements.topToolbar}
                                {$elements.topToolbar->getView()}
                            {/if}
                        </div>
                        <br class="clear">
                    </div>
                    <div class="c-help">
                        {$elements.topHelp}
                    </div>                    
                    
                    <div class="sepline">
                        {foreach from=$context_list key=key item=item}
                        <div class="block-link{if $context==$key} act{/if}">
                            <a href="{adminUrl context=$key}" class="block-link-item">{$item.title}</a>
                            {if $context==$key}
                            <a href="{adminUrl do="contextOptions" context=$key}" class="crud-edit block-link-options" title="{t}Настройки темы оформления{/t}"></a>
                            {/if}
                        </div>
                        {/foreach}
                    </div>
                </div>

                <div class="columns">
                    <div class="common-column">

                                    
                        <ul class="selector">
                            <li class="title">Страницы:</li>
                            <li {if $currentPage.route_id=='default'}class="act"{/if}><a href="{adminUrl context=$context}" class="call-update">По умолчанию</a>
                                <a class="crud-edit edit-page" href="{adminUrl do="editPage" context=$context}" title="{t}Настройки страницы{/t}"></a>
                                <div class="rs-list-button">
                                    <a class="tool rs-dropdown-handler">&nbsp;</a>
                                    <ul class="rs-dropdown">
                                        <li class="first">
                                            <a href="{adminUrl mod_controller="pageseo-ctrl" do="edit" id="default" context=$context create=1}" class="crud-edit">&nbsp;Настройки SEO</a>
                                        </li>
                                    </ul>
                                </div>
                                
                                
                            </li>
                            {foreach from=$pages item=page}
                                {if $page.route_id != 'default'}
                                <li {if $currentPage.id==$page.id}class="act"{/if}><a class="call-update" href="{adminUrl page_id=$page.id context=$context}">{if $page->getRoute() !== null}{$page->getRoute()->getDescription()}{else}Маршрут не найден{/if}</a>
                                    <a class="crud-edit edit-page" href="{adminUrl do="editPage" id=$page.id}" title="{t}Настройки страницы{/t}"></a>
                                    <div class="rs-list-button">
                                        <a class="tool rs-dropdown-handler">&nbsp;</a>
                                        <ul class="rs-dropdown">
                                            <li class="first">
                                                <a href="{adminUrl mod_controller="pageseo-ctrl" do="edit" id=$page.route_id create=1}" class="crud-edit">Настройки SEO</a>
                                            </li>
                                            <li>
                                                <a href="{adminUrl do="delPage" id=$page.id}" class="crud-remove-one">{t}удалить{/t}</a>
                                            </li>
                                        </ul>
                                    </div>                                    
                                </li>
                                {/if}
                            {/foreach}
                        </ul>
                        <div class="clear"></div>
                        {include file="%templates%/gs/{$grid_system}/pageview.tpl"}
                    </div>
                </div> <!-- .columns -->

{if !$url->isAjax()}
            </div> <!-- .updatable -->
        </div> <!-- .viewport -->
    </div> <!-- #content -->
</div>
<script>
    $.contentReady(function() {
        $('.pageview').blockEditor({
            sortContainerUrl: '{adminUrl do="ajaxMoveContainer"}',
            sortSectionUrl: '{adminUrl do="ajaxMoveSection"}',
            sortBlockUrl: '{adminUrl do="ajaxMoveBlock"}',
            toggleViewBlock: '{adminUrl do="ajaxToggleViewModule"}'
        });
    });
</script>
{/if}