{addjs file="filter.js"}
<form method="GET" class="filter form-call-update"  id="{$fcontrol->uniq}">
    {foreach from=$fcontrol->getAddParam('hiddenfields') key=key item=val}
    <input type="hidden" name="{$key}" value="{$val}">
    {/foreach}
    <a class="openfilter"><span>{$fcontrol->getCaption()}</span></a>
    {$fcontrol->getContainerView()}
</form>
{assign var=parts value=$fcontrol->getParts()}
{if count($parts)}
<div class="filter-parts">
{if count($parts)>1}<span class="part clean_all"><a href="{$fcontrol->getCleanFilterUrl()}" class="clean call-update" title="{t}Сбросить все фильтры{/t}"></a></span>{/if}
{foreach from=$parts item=part}
    <span class="part">{$part.title}: {$part.value}<a href="{$part.href_clean}" class="clean call-update" title="{t}Сбросить этот фильтр{/t}"></a></span>
{/foreach}
</div>
{/if}