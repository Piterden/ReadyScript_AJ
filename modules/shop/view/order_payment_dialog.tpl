<div class="titlebox">Редактировать способ оплаты</div>
<form id="edit-pay">
<table class="table-form">
    <tbody class="new-address">
    <tr class="last">
        <td width="100" class="caption">Cпособ оплаты: </td>
        <td>
        <select name="payment">
        {foreach from=$plist item=item}
            <option value="{$item.id}" {if $item.id==$order.payment}selected{/if}>{$item.title}</option>
        {/foreach}
        </select>
        </td>
    </tr>
    </tbody>
</table>
</form>