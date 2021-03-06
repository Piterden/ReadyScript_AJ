<div class="formbox">
    <form id="paymentAddForm" method="POST" action="{urlmake}" data-order-block="#paymentBlockWrapper" enctype="multipart/form-data" class="crud-form">
        <table class="table-form">
            <tbody class="new-address">
            <tr class="last">
                <td width="100" class="caption">{t}Cпособ оплаты{/t}: </td>
                <td>
                {if !empty($plist)}
                    <select name="payment">
                        {foreach from=$plist item=item}
                            <option value="{$item.id}" {if $item.id==$order.payment}selected{/if}>{$item.title}</option>
                        {/foreach}
                    </select>
                {else}
                    <p>Не создано ни одной оплаты.</p> 
                    <a href="{$router->getAdminUrl(false, null, 'shop-paymentctrl')}" class="all-user-orders" target="_blank">{t}Перейти в список оплат{/t}</a>
                {/if}
                </td>
            </tr>
            </tbody>
        </table>
    </form>
</div>

<script type="text/javascript">
    $(function() {
        /**
        * Назначаем действия, если всё успешно вернулось 
        */
        $('#paymentAddForm').on('crudSaveSuccess', function(event, response) {
            if (response.success && response.insertBlockHTML){ //Если всё удачно и вернулся HTML для вставки в блок
                var insertBlock = $(this).data('order-block');            
                $(insertBlock).html(response.insertBlockHTML).trigger('new-content');
                if (response.payment){ //Если указан id оплаты
                   $('input[name="payment"]').val(response.payment); 
                }
            }
        });    
    });
</script>