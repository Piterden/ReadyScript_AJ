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
                    {$elements.form}
                    <div class="footerspace"></div>
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