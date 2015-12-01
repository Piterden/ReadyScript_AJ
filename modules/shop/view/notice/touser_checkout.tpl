{assign var=delivery value=$data->order->getDelivery()}
{assign var=address value=$data->order->getAddress()}
{assign var=pay value=$data->order->getPayment()}
{assign var=cart value=$data->order->getCart()}
{assign var=user value=$data->order->getUser()}
{assign var=order_data value=$cart->getOrderData(true, false)}
{assign var=products value=$cart->getProductItems()}
<pre>
Вы сделали заказ в интернет-магазине {$url->getDomainStr()}. 
Ваш заказ будет обработан в течение 1 рабочего дня.
При необходимости, с Вами свяжется наш менеджер.
Номер Вашего заказа: {$data->order.order_num}
Заказ оформлен: {$data->order.dateof|date_format:"%d.%m.%Y"}

<strong>Параметры заказа</strong>
{if $data->order.delivery}
    Адрес доставки: {$address->getLineView()}
{/if}
{if $data->order.payment}
    Способ оплаты: {$pay.title}
    {if $pay->hasDocs()}{assign var=type_object value=$pay->getTypeObject()}
    Документы на оплату: {foreach from=$type_object->getDocsName() key=key item=doc}<a href="{$type_object->getDocUrl($key, true)}" target="_blank">{$doc.title}</a> {/foreach}
    {/if}
{/if}
{if $data->order.delivery}
    Способ доставки: {$delivery.title}
{/if}


<table cellpadding="5" border="1" bordercolor="#969696" style="border-collapse:collapse; border:1px solid #969696">
    <thead>
    <tr>
        <th>Наименование</th>
        <th>Код</th>
        <th>Цена</th>
        <th>Кол-во</th>
        <th>Стоимость</th>
    </tr>
    </thead>
    <tbody>
        {foreach from=$order_data.items key=n item=item}
        {assign var=product value=$products[$n].product}
        <tr data-n="{$n}" class="item">
            <td>
                {$item.cartitem.title}
                <br>
                {if !empty($item.cartitem.model)}Модель: {$item.cartitem.model}{/if}
            </td>
            <td>{$item.cartitem.barcode}</td>
            <td>{$item.single_cost}</td>
            <td>{$item.cartitem.amount}</td>
            <td>
                <span class="cost">{$item.total}</span>
                {if $item.discount>0}скидка {$item.discount}{/if}
            </td>
        </tr>
        {/foreach}
    </tbody>
    <tbody class="additems">
        {foreach from=$order_data.other key=n item=item}
        <tr>
            <td colspan="4">{$item.cartitem.title}</td>
            <td>{if $item.total>0}{$item.total}{/if}</td>
        </tr>
        {/foreach}
    </tbody>
    <tfoot>
        <tr class="last">
            <td colspan="4"></td>
            <td class="total">
                Итого: {$order_data.total_cost}
            </td>
        </tr>
    </tfoot>
</table>

Вы можете изменить свои данные и ознакомиться со статусом заказа в разделе <a href="{$router->getUrl('shop-front-myorders',[], true)}">«Личный кабинет»</a>.

С Наилучшими пожеланиями,
        Администрация интернет-магазина {$url->getDomainStr()}
</pre>