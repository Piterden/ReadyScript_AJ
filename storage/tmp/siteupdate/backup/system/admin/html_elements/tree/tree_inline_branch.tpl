{foreach from=$list item=item key=key name="onebranch"}
<li class="branch{if $smarty.foreach.onebranch.last}-end{/if} {if isset($tree->options.activeField) && $tree->options.activeValue == $item.fields[$tree->options.activeField]}current-inlist{/if}" data-id="{$item.fields[$tree->options.sortIdField]}">
    {assign var=cell value=$tree->getMainColumn($item.fields)}
    {if isset($cell->property.href)}<a href="{$cell->getHref()}" class="call-update">{/if}
        {include file=$cell->getBodyTemplate() cell=$cell}
    {if isset($cell->property.href)}</a>{/if}

    {if !empty($item.child)}
    <ul>
        {include file="%system%/admin/html_elements/tree/tree_inline_branch.tpl" list=$item.child}
    </ul>
    {/if}    
</li>
{/foreach}