{$groups=$prop->getGroups(false, $switch)}
{foreach from=$groups key=i item=data}
    {foreach from=$data.items key=name item=item}
        {if !$item->isHidden()}
        {literal}
        <p class="label">{$elem.__{/literal}{$name}{literal}->getTitle()}</p>
        {include file=$elem.__{/literal}{$name}{literal}->getRenderTemplate() field=$elem.__{/literal}{$name}{literal}}
        {/literal}
        {/if}
    {/foreach}
{/foreach}