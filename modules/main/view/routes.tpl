{if !$url->isAjax()}
<div class="crud-ajax-group">
    <div id="content-layout">
        <div class="viewport">
            <div class="updatable" data-url="{adminUrl}">
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
                    
                    <div class="sepline"></div>
                </div>
                <div class="columns">
                    <div class="common-column">
                        <div class="beforetable-line routes-filter">
                            <form method="GET" class="form-call-update form-style">
                                <label class="line-label">Хост</label> 
                                <input type="text" name="host" value="{$host|escape}">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <label class="line-label">URI</label> 
                                <input type="text" name="uri" value="{$uri|escape}" style="width:300px;"> <span class="help-icon" title="{t}Например: /products/ или /products/computers/{/t}">?</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="submit" class="button" value="{t}Проверить на соответствие{/t}">
                                {if $uri !== false}
                                    <div class="inform-block" style="margin-top:10px;">
                                        {if $selected}{t route=$selected}Соответствует маршрут <strong>%route</strong>{/t}{else}{t}Ни один маршрут не соответствует заданном URI{/t}{/if}
                                    </div>
                                {/if}
                            </form>
                        </div>
                        <div class="clear-right"></div>
                        {if isset($elements.table)}
                            <form method="POST" enctype="multipart/form-data" action="{urlmake}" class="crud-list-form">
                                {foreach from=$elements.hiddenFields key=key item=item}
                                <input type="hidden" name="{$key}" value="{$item}">
                                {/foreach}
                                {$elements.table->getView()}
                            </form>
                        {/if}
                        {if isset($elements.paginator)}
                            {$elements.paginator->getView()}
                        {/if}
                        <div class="footerspace"></div>
                    </div>
                </div> <!-- .columns -->

{if !$url->isAjax()}
            </div> <!-- .updatable -->
        </div> <!-- .viewport -->
    </div> <!-- #content -->
        
</div>
{/if}