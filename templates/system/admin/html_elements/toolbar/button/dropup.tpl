<div {$button->getAttrLine()}>
    {assign var=first value=$button->getFirstItem()}
    <a {if isset($first.href)}href="{$first.href}"{/if} {$button->getItemAttrLine($first, true)}>{$first.title}</a>{if count($button->getDropItems())}<a class="rs-dropdown-handler">&nbsp;</a>
        <ul class="rs-dropdown">
        {foreach from=$button->getDropItems() item=item name=ddown}
            <li {if $smarty.foreach.ddown.first}class="first"{/if}>
            <a {if isset($first.href)}href="{$first.href}"{/if} {$button->getItemAttrLine($item)}>{$item.title}</a>
            </li>
        {/foreach}        
        </ul>        
    {/if}
</div>