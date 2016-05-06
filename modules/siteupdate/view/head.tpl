{addjs file="{$mod_js}/siteupdate.js" basepath="root"}
{addcss file="{$mod_css}/siteupdate.css" basepath="root"}
{if $success_text}
<div class="text-success">
    {$success_text}
</div>
{/if}
<br>
{if $currentStep=='1'}
<ul class="stepbystep" data-current-step="{$currentStep}">
    <li class="first{if $currentStep=='1'} act{/if}{if $currentStep>1} already{/if} step1">
        <a href="{adminUrl do=false}" class="check-update item{if !$canUpdate} disabled{/if}" data-change-text="{t}идет проверка обновлений...{/t}">{t}проверить обновления{/t}</a>
        <div class="load-indicator"></div>
    </li>
</ul>
{else}
<ul class="stepbystep" data-current-step="{$currentStep}">
    <li class="first{if $currentStep=='1'} act{/if}{if $currentStep>1} already{/if} step1">
        <a href="{adminUrl do=false}" class="check-update item" >{t}проверка обновлений{/t}</a>
        <div class="load-indicator"></div>
    </li>
    <li class="{if $currentStep=='2'}act{/if}{if $currentStep>2} already{/if} step2">
        {if is_array($data) && count($data.products)>1}
            <a href="{adminUrl do='selectProduct'}" class="item">{if $currentStep == 3}Продукт: {$data.updateProduct}{else}выбор продукта{/if}<i></i></a>
        {else}
            <span class="item">Продукт: {$data.updateProduct}</span>
        {/if}
    </li>
    <li class="{if $currentStep=='3'}act{/if} step3">
        <span class="item">установка обновлений<i></i></span>
    </li>
</ul>
{/if}

{if $currentStep == '1' && $canUpdate}
<div class="clicktostart">
    &larr; Нажмите, чтобы проверить наличие обновлений для Вашей системы.
</div>
{/if}

<div class="clear"></div>

<div class="error-block">
{if !empty($errors)}
    <ul class="error-list">
        {foreach from=$errors item=data}
        <li>
            <div class="field">{$data.fieldname}<i class="cor"></i></div>
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