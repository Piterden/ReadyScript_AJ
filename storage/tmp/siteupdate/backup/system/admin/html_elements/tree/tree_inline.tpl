{addjs file="jquery.treeview.js" basepath="common"}
<div class="category-filter">
<div class="category-selector">
    {assign var=path value=$tree->getPathToFirst()}
        <span class="current">
            {foreach from=$path item=item name=breadcrumb}
                {if !$smarty.foreach.breadcrumb.first}>{/if}
                {assign var=cell value=$tree->getMainColumn($item)}
                {include file=$cell->getBodyTemplate() cell=$cell} 
            {/foreach}
        </span>
        <a class="dropdown-handler">&nbsp;</a>
        <div class="list">
            <ul class="root-ul">
                {include file="%system%/admin/html_elements/tree/tree_inline_branch.tpl" level="0" list=$tree->getData()}
            </ul>
        </div>
    </div><div class="cf-tools">
        {assign var=current_item value=end($path)}
        {if isset($current_item.inlineButtons)}
            {assign var=tools value=$current_item.inlineButtons}
        {else}
            {assign var=tools value=$tree->getOption('inlineButtons')}
        {/if}
        {foreach from=$tools item=tool key=button_name}
            {if !isset($current_item.hideInlineButtons) || !in_array($button_name, $current_item.hideInlineButtons)}
                <a {foreach from=$tool.attr item=value key=key}{$key}="{$value}"{/foreach}></a>
            {/if}
        {/foreach}
    </div>
</div>                    