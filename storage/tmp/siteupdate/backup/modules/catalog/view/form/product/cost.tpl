<table class="intable">
{$elem->calculateUserCost()}
{foreach from=$elem->getCostList() item=onecost}
    <tr>
        <td class="otitle">{$onecost.title}</td>
        <td>
        <input type="text" name="excost[{$onecost.id}][cost_original_val]" value="{$elem.excost[$onecost.id]['cost_original_val']}" {if $onecost.type=='auto'}disabled{/if}>
        {if $onecost.type=='auto'}<span class="hint" title="Автовычесляемое поле, будет расчитано после сохранения">?</span>{/if}
        </td>
        <td>
            {if $onecost.type=='manual'}
            <select name="excost[{$onecost.id}][cost_original_currency]">
                {foreach from=$elem->getCurrencies() key=id item=curr}
                <option value="{$id}" {if $elem.excost[$onecost.id].cost_original_currency == $id}selected{/if}>{$curr}</option>
                {/foreach}
            </select>
            {/if}
        </td>  
    </tr>
{/foreach}
</table>