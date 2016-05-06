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
                        <div class="beforetable-line{if !isset($elements.filter)} sepspace{/if}">
                            {if isset($elements.filter)}
                                {$elements.filter->getView()}
                            {/if}
                        </div>
                        <div class="clear-right"></div>
                        {if isset($elements.tree)}
                            <form method="POST" enctype="multipart/form-data" action="{urlmake}" class="crud-list-form">
                                {$elements.tree->getView()}
                            </form>
                        {/if}
                        <div class="footerspace"></div>
                    </div>
                </div> <!-- .columns -->

{if !$url->isAjax()}
            </div> <!-- .updatable -->
        </div> <!-- .viewport -->
    </div> <!-- #content -->
        
    <div class="bottomToolbar">
        <div class="viewport">
            <div class="common-column">
                {if $elements.bottomToolbar}
                    {$elements.bottomToolbar->getView()}
                {/if}
            </div>
        </div>
    </div>    
</div>
{/if}