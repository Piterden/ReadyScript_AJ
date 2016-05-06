{$app->autoloadScripsAjaxBefore()}
<div class="crud-ajax-group">
    {if !$url->isAjax()}
    <div id="content-layout">
        <div class="viewport">
        <div class="updatable" data-url="{adminUrl mod=$module_item->getName()}">
    {/if}
            <div class="titlebox">{$elements.formTitle}</div>
            <div class="sepline sep-top-form"></div>
            <div class="middlebox">
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
                
                {if $module_item->getConfig()->installed}                
                <div class="columns">
                    <div class="tools-column fullheight">
                        <div class="controller_info">
                            <h3>Утилиты</h3>
                            <ul class="list-with-help">
                                {foreach from=$module_item->getTools() item=item}
                                <li><a {if !empty($item.confirm)}data-confirm-text="{$item.confirm}"{/if} class="{if $item.class}{$item.class}{else}crud-get{/if}" href="{$item.url}" style="text-decoration:underline">{$item.title}</a>
                                    {if $item.description}<div class="tool-descr">{$item.description}</div>{/if}
                                </li>
                                {/foreach}
                            </ul>
                        </div>    
                    
                    </div>
                    <div class="form-column">
                        {if !$module_item->getConfig()->isMultisiteConfig()}
                        <br><div class="notice-box">{t}Настройки данного модуля едины для всех сайтов в рамках мультисайтовости{/t}</div>
                        {/if}
                            {$elements.form}
                    </div>
                    <div class="clearboth"></div>
                </div>
                {else}
                    <div class="inform-block margvert10">
                        Модуль не установлен. <a href="{adminUrl do=ajaxreinstall module=$module_item->getName()}" class="u-link crud-get">Установить</a>
                    </div>
                {/if}
            </div>
        {if !$url->isAjax()}
            </div>
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