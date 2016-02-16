{addjs file="jquery.treeview.js" basepath="common"}
{addjs file="jquery.treesort.js" basepath="common"}
{addjs file="jquery.scrollto.js" basepath="common"}
        
<div class="activetree tree" data-uniq="{$tree->options.uniq}">
    <ul class="treehead">
        <li>
            {if !$tree->options.noCheckbox}
            <div class="chk"><input type="checkbox" class="select-page" data-name="{$tree->getCheckboxName()}"></div>
            {/if}
            {if !$tree->options.noExpandCollapseButton}
            <a class="allplus" title="развернуть все">&nbsp;</a>
            <a class="allminus" title="свернуть все">&nbsp;</a>
            {/if}
            {foreach from=$tree->getHeadButtons() item=button}
                {if $button.tag}{assign var=tag value=$button.tag}{else}{assign var=tag value="a"}{/if}
                <{$tag} {foreach from=$button.attr|default:array() key=key item=value} {$key}="{$value}"{/foreach}>{$button.text}</{$tag}>
            {/foreach}
        </li>
    </ul>

    <ul class="treebody root{if $tree->options.sortable} treesort{/if}" data-sort-url="{$tree->options.sortUrl}">
        {include file="%system%/admin/html_elements/tree/tree_branch.tpl" level="0" list=$tree->getData()}
        {if !count($tree->getData())}
        <li class="empty-tree-row">{t}Нет элементов{/t}</li>
        {/if}
    </ul>
</div>