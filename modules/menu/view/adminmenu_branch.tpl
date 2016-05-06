{foreach from=$list item=item}
<li {if !empty($item.child)} class="node"{/if}{if $item.fields.typelink=='separator'} class="separator"{/if}>
    {if $item.fields.typelink!='separator'}
    <a {if isset($sel_id) && $sel_id==$item.fields.id}class="selected"{/if} href="{$item.fields.link}">{t context="adminmenu"}{$item.fields.title}{/t}</a>
    {/if}
    {if !empty($item.child)}
        <ul>
        {include file="adminmenu_branch.tpl" list=$item.child}
        </ul>
    {/if}
</li>
{/foreach}