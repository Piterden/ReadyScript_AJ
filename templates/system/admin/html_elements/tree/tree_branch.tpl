{foreach from=$list item=item key=key}
<li class="{if isset($tree->options.disabledField) && $item.fields[$tree->options.disabledField] === $tree->options.disabledValue}disabled{/if} {if isset($tree->options.activeField) && $tree->options.activeValue == $item.fields[$tree->options.activeField]}current{/if}{if $item.fields[$tree->options.classField]} {$item.fields[$tree->options.classField]}{/if}" {if !empty($item.fields.noDraggable)}data-notmove="notmove"{/if} data-id="{$item.fields[$tree->options.sortIdField]}">
    <div class="chk">
        {if !$item.fields.noCheckbox && !$tree->options.noCheckbox}
        <input type="checkbox" name="{$tree->getCheckboxName()}" value="{$item.fields[$tree->options.activeField]}" {if $tree->isChecked($item.fields[$tree->options.activeField])}checked{/if} {if $item.fields.disabledCheckbox}disabled{/if}>
        {/if}
    </div>
    <div class="line">
        <div class="toggle {if !empty($item.child)}{if $item.fields.closed}plus{else}minus{/if}{else}branch{/if}"></div>
        {if $tree->options.sortable}<div class="move{if !empty($item.fields.noDraggable)} no-move{/if}"></div>{/if}
        {if !$item.fields.noRedMarker}
        <div class="redmarker"></div>
        {/if}
        <div class="data">
            <div class="textvalue">
            {assign var=cell value=$tree->getMainColumn($item.fields)}
            {if isset($cell->property.href)}<a href="{$cell->getHref()}" class="call-update">{/if}
                {include file=$cell->getBodyTemplate() cell=$cell}
            {if isset($cell->property.href)}</a>{/if}
            </div>
            {if empty($item.fields.noOtherColumns)}
                {if isset($item.fields.treeTools)}
                    {$item.fields.treeTools->setRow($item.fields)|devnull}
                    {include file=$item.fields.treeTools->getBodyTemplate() cell=$item.fields.treeTools}
                {else}
                    {if $tree->getTools()}
                        {include file=$tree->getTools()->getBodyTemplate() cell=$tree->getTools($item.fields)}
                    {/if}
                {/if}
            {/if}
        </div>
    </div>
    {if !empty($item.child)}
    <ul class="childroot" {if ($item.fields.closed)}style="display:none"{/if}>
        {include file="%system%/admin/html_elements/tree/tree_branch.tpl" list=$item.child level=$level+1}
    </ul>
    {/if}    
</li>
{/foreach}