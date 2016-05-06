{$app->autoloadScripsAjaxBefore()}
<div class="crud-ajax-group">
    {if !$url->isAjax()}
    <div id="content-layout">
        <div class="viewport">
    {/if}
            <div class="titlebox">{$elements.formTitle}</div>
            {if !$url->isAjax()}<div class="sepline sep-top-form"></div>{/if}
            <div class="middlebox {$middleclass}">
                <div class="crud-form-error">
                    {if count($elements.formErrors)}
                        <ul class="error-list">
                            {foreach from=$elements.formErrors item=data}
                                <li>
                                    <div class="{$data.class|default:"field"}">{$data.fieldname}<i class="cor"></i></div>
                                    <div class="text">
                                        {foreach from=$data.errors item=error}
                                        {$error}
                                        {/foreach}
                                    </div>
                                </li>
                            {/foreach}
                        </ul>
                    {/if}
                </div>
                <div class="crud-form-success text-success"></div>
                <div class="block-information inform-block" style="margin-top:10px;">
                    <table>
                        <tr>
                            <td align="right">Контроллер:</td>
                            <td><strong>{$elements.block_controller}</strong></td>
                        </tr>
                    </table>
                </div>
                {$elements.form}
                {if empty($elements.form)}
                    <div class="no_block_options">
                        У блока нет настроек
                    </div>
                {/if}
            </div>
        {if !$url->isAjax()}
            <div class="footerspace"></div>
        </div> <!-- .viewport -->
    </div> <!-- .content -->
    {/if}
    <div class="bottomToolbar zindex-dlg">
        <div class="viewport">
            <div class="common-column">
                {if isset($elements.bottomToolbar)}
                    {$elements.bottomToolbar->getView()}
                {/if}
            </div>
        </div>
    </div>    
</div>
{$app->autoloadScripsAjaxAfter()}