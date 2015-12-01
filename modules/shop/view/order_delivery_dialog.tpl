<div class="titlebox">Редактировать доставку</div>
<form id="edit-delivery">
    <table class="table-form">
        <tbody class="new-address">
        <tr>
            <td class="caption">Cпособ доставки: </td>
            <td>
            <select name="delivery">
            {foreach from=$dlist item=item}
                <option value="{$item.id}" {if $item.id==$order.delivery}selected{/if}>{$item.title}</option>
            {/foreach}
            </select>
            </td>
        </tr>
        <tr>
            <td>Изменяемый адрес:</td>
            <td>
            <select name="use_addr" id="change_addr" data-url="{adminUrl do=getAddressRecord}">
                {foreach from=$address_list item=item}
                <option value="{$item.id}" {if $current_address.id==$item.id}selected{/if}>{$item->getLineView()}</option>
                {/foreach}
                <option value="0">Новый адрес для заказа</option>        
            </select>
            <div class="helper">Внимание! если этот адрес используется в других заказах, то он также будет изменен.</div>
            </td>
        </tr>
        </tbody>    
        <tbody class="address_part">
            {$address_part}
        </tbody>
        <tbody>
        <tr class="last">
            <td class="caption">Стоимость:</td>
            <td>
                <input size="10" maxlength="20" value="{$order.user_delivery_cost}" name="user_delivery_cost">
                <div class="helper">Если стоимость доставки не указана, то сумма доставки будет рассчитана автоматически.</div>
            </td>
        </tr>    
        </tbody>
    </table>
</form>
{literal}
<script>
    $(function() {
        $('#change_addr').change(function() {
            $.ajaxQuery({
                url: $(this).data('url'),
                data: {
                    'address_id': $(this).val()
                },
                success: function(response) {
                    $('.address_part').html(response.html);
                }
            });
        });
    });                                
</script>
{/literal}    