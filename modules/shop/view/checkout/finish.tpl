<h2><span>Заказ успешно оформлен</span></h2>
<p class="mb10 underText">
    Следить за изменениями статуса заказа можно в разделе <a href="{$router->getUrl('shop-front-myorders')}" target="_blank">история заказов</a>. 
    Все уведомления об изменениях в данном заказе также будут отправлены на электронную почту покупателя.
</p>
<div class="orderInfo fullwidth">
    <h1 style="font-size:20px; ">Заказ N {$order.order_num} от {$order.dateof|date_format:"d.m.Y"}</h1>
    
    {assign var=user value=$order->getUser()}
    <ul class="section">
        <li><span class="key">Заказчик:</span> {$user.surname} {$user.name}</li>
        <li><span class="key">Телефон:</span> {$user.phone}</li>
        <li><span class="key">E-mail:</span> {$user.e_mail}</li>
    </ul>
    
    {assign var=fmanager value=$order->getFieldsManager()}
    {if $fmanager->notEmpty()}
        <ul class="section">
            {foreach from=$fmanager->getStructure() item=field}
            <li><span class="key">{$field.title}:</span> {$fmanager->textView($field.alias)}</li>
            {/foreach}
        </ul>
    {/if}
    
    {assign var=delivery value=$order->getDelivery()}
    {assign var=address value=$order->getAddress()}
    <ul class="section">
        {if $order.delivery}
            <li><span class="key">Доставка:</span> {$delivery.title}</li>
        {/if}
        <li><span class="key">Адрес:</span> {$address->getLineView()}</li>
    </ul>
    {if $order.payment}
        {assign var=pay value=$order->getPayment()}
        <ul class="section">
            <li><span class="key">Оплата:</span> {$pay.title}</li>
        </ul>
    {/if}
</div>

{if $order->getPayment()->hasDocs()}
<div class="paymentDocuments">
    <h3>Документы на оплату</h3>
    {if $user.id}
        <p class="helpText underText">Воспользуйтесь следующими документами для оплаты заказа. Эти документы всегда доступны в разделе <a href="{$router->getUrl('shop-front-myorders')}" target="_blank">история заказов</a></p>
    {/if}
    <ul class="docsList">
        {assign var=type_object value=$order->getPayment()->getTypeObject()}
        {foreach from=$type_object->getDocsName() key=key item=doc}
        <li><a href="{$type_object->getDocUrl($key)}" target="_blank">{$doc.title}</a></li>
        {/foreach}
    </ul>
</div>    
{/if}

<div class="cartInfo fullwidth">
    {assign var=orderdata value=$cart->getOrderData()}
    <table class="orderBasket">
        <tbody class="head">
            <tr>
                <td></td>
                <td>Количество</td>
                <td>Цена</td>
            </tr>
        </tbody>
        <tbody>
            {foreach from=$orderdata.items item=item key=n name="basket"}
            {assign var=orderitem value=$item.cartitem}
            <tr {if $smarty.foreach.basket.first}class="first"{/if}>
                <td>
                    {assign var=barcode value=$orderitem.barcode}
                    {assign var=offer_title value=$orderitem.model}
                    {assign var=multioffer_titles value=$orderitem->getMultiOfferTitles()} 

                    <span class="prod-name">{$orderitem.title}</span>
                    <div class="codeLine">
                        {if $barcode != ''}Артикул:<span class="value">{$barcode}</span>&nbsp;&nbsp;{/if}
                        {if !empty($multioffer_titles)}
                            <div class="multioffersWrap">
                                Комплектация: 
                                {foreach $multioffer_titles as $multioffer}
                                    <div>
                                        <span class="value">{$multioffer.title} - {$multioffer.value}</span>
                                    </div>
                                {/foreach}
                            </div>
                        {elseif $offer_title != ''}
                            Комплектация:<span class="value">{$offer_title}</span>
                        {/if}
                    </div>
                </td>
                <td width="110">
                    {$orderitem.amount} {$orderitem.data.unit}
                </td>
                <td width="160">
                    <span class="priceBlock">
                        <span class="priceValue">{$item.total}</span>
                    </span>
                    <div class="discount">
                        {if $item.discount>0}
                        скидка {$item.discount}
                        {/if}
                    </div>
                </td>
            </tr>
            {/foreach}
        </tbody>
        <tbody class="additional">
            {foreach from=$orderdata.other item=item name="other"}
            <tr {if $smarty.foreach.other.first}class=""{/if}>
                <td colspan="2">{$item.cartitem.title}</td>
                <td class="price">{if $item.total != 0}{$item.total}{/if}</td>
            </tr>
            {/foreach}
        </tbody>
        <tfoot class="summary">
            <tr>
                <td colspan="2">Итого</td>
                <td>{$orderdata.total_cost}</td>
            </tr>
        </tfoot>
    </table>        
    {if $order->canOnlinePay()}
        <a href="{$order->getOnlinePayUrl()}" class="formSave">Перейти к оплате</a>
    {else}
        <a href="{$router->getRootUrl()}" class="formSave">Завершить заказ</a>
    {/if}
</div>