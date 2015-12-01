<div class="formbox">
    {* Типы доставки, которые не требуют установки флага отправки на удалённый сервис доставки *}
    {$user_delivery_type=[
        'myself',
        'fixedpay',
        'universal',
        'manual'
    ]}
    <form id="deliveryAddForm" method="POST" action="{urlmake}" data-order-block="#deliveryBlockWrapper" enctype="multipart/form-data" class="crud-form">
        <table class="table-form">
            <tbody class="new-address">
            <tr>
                <td class="caption">{t}Cпособ доставки{/t}: </td>
                <td>
                {$selected_delivery_type="myself"}
                <select id="change_delivery" name="delivery">
                    {foreach $dlist as $item}
                        {$delivery_type=$item->getTypeObject()->getShortName()}
                        {if $item.id==$delivery_id}
                            {$selected_delivery_type=$delivery_type}
                        {/if}
                        <option value="{$item.id}" data-delivery-query-flag="{if in_array($delivery_type, $user_delivery_type)}0{else}1{/if}" {if $item.id==$delivery_id}selected{/if}>
                            {$item.title}
                        </option>
                    {/foreach}
                </select>
                </td>
            </tr>
            <tr>
                <td>{t}Изменяемый адрес{/t}:</td>
                <td>
                <select name="use_addr" id="change_addr" data-url="{adminUrl do=getAddressRecord}">
                    {foreach from=$address_list item=item}
                        <option value="{$item.id}" {if $current_address.id==$item.id}selected{/if}>{$item->getLineView()}</option>
                    {/foreach}
                    <option value="0">{t}Новый адрес для заказа{/t}</option>        
                </select>
                <div class="helper">{t}Внимание! если этот адрес используется в других заказах, то он также будет изменен.{/t}</div>
                </td>
            </tr>
            </tbody>    
            <tbody class="address_part">
                {$address_part}
            </tbody>
            <tbody>
            <tr class="last">
                <td class="caption">{t}Стоимость{/t}:</td>
                <td>
                    <input size="10" maxlength="20" value="{$order.user_delivery_cost}" name="user_delivery_cost">
                    <div class="helper">{t}Если стоимость доставки не указана, то сумма доставки будет рассчитана автоматически.{/t}</div>
                </td>
            </tr>    
            </tbody>
        </table>    
    </form>
    <script type="text/javascript">
        $(function() {
            /**
            * Назначаем действия, если всё успешно вернулось 
            */
            $('#deliveryAddForm').on('crudSaveSuccess', function(event, response) {
                if (response.success && response.insertBlockHTML){ //Если всё удачно и вернулся HTML для вставки в блок
                    var insertBlock = $(this).data('order-block');            
                    $(insertBlock).html(response.insertBlockHTML).trigger('new-content');
                    if (typeof(response.delivery)!='undefined'){ //Если указан id доставки
                       $('input[name="delivery"]').val(response.delivery); 
                    }
                    
                    if (typeof(response.user_delivery_cost)!='undefined'){ //Если указан id доставки
                       $('input[name="user_delivery_cost"]').val(response.user_delivery_cost); 
                    }
                    if (typeof(response.use_addr)!='undefined'){ //Если выбран адрес доставки
                       $('input[name="use_addr"]').val(response.use_addr); 
                    }
                    if (typeof(response.address)!='undefined'){ //Если выбран адрес
                       for(var m in response.address){
                          $('input[name="address[' + m + ']"]').val(response.address[m]);  
                       } 
                    }             
                    //Снимем флаг показа дополнительных кнопок доставки 
                    if ($("#showDeliveryButtons").length){
                        $("#showDeliveryButtons").val(0);     
                    }                           
                    //Обновимм корзину, т.к. доставка может прибавить стоимость
                    $.orderEdit('refresh');
                }
            });
            
            /**
            * Смена выпадающего списка с адресами
            */
            $('#change_addr').on('change', function() {
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
</div>