{if !$url->isAjax()}
<div class="crud-ajax-group">
    <div id="content-layout">
        <div class="viewport">
            <div class="updatable" data-url="{urlmake}">
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
                {$elements.headerHtml}
                <div class="columns">
                    <div class="common-column">
                        {$elements.beforeTableContent}
                        <div class="beforetable-line{if !isset($elements.filter) && !isset($elements.paginator)} sepspace{/if}">
                            {if isset($elements.paginator)}
                                {$elements.paginator->getView(['short' => true])}
                            {/if}                        
                            {if isset($elements.filter)}
                                {$elements.filter->getView()}
                            {/if}
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
        
    {if $elements.bottomToolbar}
        <div class="bottomToolbar">
            <div class="viewport">
                <div class="common-column">
                        {$elements.bottomToolbar->getView()}
                </div>
            </div>
        </div>    
    {/if}
</div>
{/if}