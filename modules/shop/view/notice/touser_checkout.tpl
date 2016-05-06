{assign var=delivery value=$data->order->getDelivery()}
{assign var=address value=$data->order->getAddress()}
{assign var=pay value=$data->order->getPayment()}
{assign var=cart value=$data->order->getCart()}
{assign var=user value=$data->order->getUser()}
{assign var=order_data value=$cart->getOrderData(true, false)}
{assign var=products value=$cart->getProductItems()}

Вы сделали заказ в интернет-магазине {$url->getDomainStr()}.<br>
Ваш заказ будет обработан в течение 1 рабочего дня.<br>
При необходимости, с Вами свяжется наш менеджер.<br>
Номер Вашего заказа: {$data->order.order_num}<br>
Заказ оформлен: {$data->order.dateof|date_format:"%d.%m.%Y"}<br>

<p><strong>Параметры заказа</strong></p>
{if $data->order.delivery && !$delivery->getTypeObject()->isMyselfDelivery()}
    Адрес доставки: {$address->getLineView()}<br>
{/if}
{if $data->order.payment}
    Способ оплаты: {$pay.title}<br>
    {if $pay->hasDocs()}{assign var=type_object value=$pay->getTypeObject()}
    Документы на оплату: {foreach from=$type_object->getDocsName() key=key item=doc}<a href="{$type_object->getDocUrl($key, true)}" target="_blank">{$doc.title}</a> {/foreach}<br>
    {/if}
{/if}
{if $data->order.delivery}
    Способ доставки: {$delivery.title}<br>
    {if $data->order.warehouse && $delivery->getTypeObject()->isMyselfDelivery()}
        {$warehouse=$data->order->getWarehouse()} 
        Склад самовывоза - "{$warehouse.title}" (Адрес: {$warehouse.adress}) <br>
    {/if}
{/if}

<p><strong>Состав заказа</strong></p>

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
        <tr>
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
    <tbody>
        {foreach from=$order_data.other key=n item=item}
        <tr>
            <td colspan="4">{$item.cartitem.title}</td>
            <td>{if $item.total>0}{$item.total}{/if}</td>
        </tr>
        {/foreach}
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4"></td>
            <td>
                Итого: {$order_data.total_cost}
            </td>
        </tr>
    </tfoot>
</table>

<p>Вы можете изменить свои данные и ознакомиться со статусом заказа в разделе <a href="{$router->getUrl('shop-front-myorders',[], true)}">«Личный кабинет»</a>.</p>

<p>С Наилучшими пожеланиями,<br>
        Администрация интернет-магазина {$url->getDomainStr()}</p>