{if $order->hasError()}
    <div class="pageError">
        {foreach from=$order->getErrors() item=item}
        <p>{$item}</p>
        {/foreach}
    </div>
{/if}
<form method="POST" id="order-form">
    <input type="hidden" name="warehouse" value="0">
    <div class="formSection">
        <span class="formSectionTitle">Доставка - выбор склада для вывоза товара</span>
    </div>    
    
    <table class="formTable">
            {foreach from=$warehouses_list item=item}
                <tr class="row">
                    <td class="value fixedRadio topPadd" width="40">
                        <input type="radio" name="warehouse" value="{$item.id}" id="dlv_{$item.id}" {if ($order.warehouse>0)&&($order.warehouse==$item.id)}checked{elseif ($order.warehouse==0) && $item.default_house}checked{/if} >
                    </td>                    
                    <td class="value marginRadio topPadd" colspan="2">
                        {if !empty($item.image)}
                           <img class="logoService" src="{$item.__image->getUrl(100, 100, 'xy')}"/>
                       {/if}
                       <div class="middleBox">
                           <label for="dlv_{$item.id}">{$item.title}</label>
                            <div class="help">
                                {if !empty($item.adress)}Адрес:{$item.adress}<br/>{/if} 
                                {if !empty($item.phone)}Тел.:{$item.phone}<br/>{/if}
                                {if !empty($item.work_time)}Время работы.:{$item.work_time}{/if}
                            </div>
                       </div>
                    </td>
                </tr>
            {/foreach}
        </tr>
    </table>
    <button type="submit" class="formSave">Далее</button>
</form>
<br><br><br>