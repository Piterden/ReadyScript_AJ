{if $elem.payment>0}
    {assign var=hl value=["n","hl"]} 
    <h3>Оплата <a href="{adminUrl do=paymentDialog order_id=$elem.id}" class="tool-edit crud-add" title="редактировать"></a></h3>
    {if isset($payment_id)}
       <input type="hidden" name="payment" value="{$payment_id}"/>
    {/if}
    <table class="order-table">
            <tr class="{cycle values=$hl name="payment"}">
                <td width="20">
                    Тип
                </td>
                <td>{$pay.title}</td>
            </tr>
            {if $elem.id>0}
                <tr class="{cycle values=$hl name="payment"}">
                    <td>
                        Заказ оплачен?
                    </td>
                    <td>
                        {$elem.__is_payed->formView()}
                    </td>
                </tr>
                <tr class="{cycle values=$hl name="payment"}">
                    <td>
                        Документы покупателя
                    </td>
                    <td>
                        {$type_object=$pay->getTypeObject()}
                        {foreach from=$type_object->getDocsName() key=key item=doc name="docs"}
                            <a href="{$type_object->getDocUrl($key)}" class="underline" target="_blank">{$doc.title}</a>{if !$smarty.foreach.docs.last},{/if}
                        {/foreach}
                    </td>
                </tr>  
            {/if}                  
    </table>
{else}
    <p class="emptyOrderBlock">Тип оплаты не указан. <a href="{adminUrl do=paymentDialog order_id=$elem.id}" class="crud-add">Указать оплату</a>.</p>
{/if}