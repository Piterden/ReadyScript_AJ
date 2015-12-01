{literal}{* Файл генерируется автоматически исходя из полей объекта Order *}{/literal}
{$groups=$prop->getGroups(false, $switch)}
{foreach from=$groups key=i item=data}
    {foreach from=$data.items key=name item=item}
        {if $item->isVisible($switch, false)}
        {literal}
        {include file=$elem.__{/literal}{$name}{literal}->getRenderTemplate() field=$elem.__{/literal}{$name}{literal}}
        {/literal}
        {/if}
    {/foreach}
{/foreach}