<p class="checkoutMobileCaption">Выбор склада</p>
{if $order->hasError()}
    <div class="pageError">
        {foreach $order->getErrors() as $item}
        <p>{$item}</p>
        {/foreach}
    </div>
{/if}
<form method="POST" class="formStyle checkoutForm" id="order-form">
    <input type="hidden" name="warehouse" value="0">
    <ul class="vertItems delivery">
        {foreach $warehouses_list as $item}
        <li>
            <div class="radioLine">
                <span class="input">
                    <input type="radio" name="warehouse" value="{$item.id}" id="wh_{$item.id}" {if ($order.warehouse>0)&&($order.warehouse==$item.id)}checked{elseif ($order.warehouse==0) && $item.default_house}checked{/if} >
                    <label for="wh_{$item.id}">{$item.title}</label>
                </span>
            </div>
            <div class="descr">
                {if !empty($item.image)}
                       <img class="logoService" src="{$item.__image->getUrl(100, 100, 'xy')}" alt="{$item.title}"/>
                    {/if}
                    <p>
                    {if !empty($item.adress)}Адрес: <span class="hl">{$item.adress}</span><br/>{/if} 
                    {if !empty($item.phone)}Телефон: <span class="hl">{$item.phone}</span><br/>{/if}
                    {if !empty($item.work_time)}Время работы: <span class="hl">{$item.work_time}</span>{/if}
                    </p>
            </div>
        </li>
        {/foreach}
    </ul>
    <div class="buttonLine">
        <input type="submit" value="Далее">
    </div>
</form>