<div class="rs-list-button">
    <a class="tool rs-dropdown-handler"></a>
    <ul class="rs-dropdown">
        {foreach from=$tool->getItems() item=item name="tool"}
        {if !$tool->isItemHidden($item)}
        <li {if $smarty.foreach.tool.first}class="first"{/if}>
            <a {foreach from=$item.attr key=key item=val}{if $key[0]=='@'}{$key|substr:"1"}{else}{$key}{/if}="{if $key[0]=='@'}{$cell->getHref($val)}{else}{$val}{/if}" {/foreach}>{$item.title}</a></li>
        {/if}
        {/foreach}
    </ul>
</div>