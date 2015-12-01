{$order=$data->order}
{$order_before=$data->order->before_this}
{$address_before=$data->order->before_address->getLineView()} {* Предыдущий адрес, если есть *}
<pre>
Заказ N{$order.order_num} от {$order.dateof|date_format:"%d.%m.%Y"} был изменен.

--------
Произошли следующие изменения:
{if $order_before.status != $order.status} {* Статус *}
   - Статус заказа "{$order->getStatus()->title}"
{/if}
{if ($order_before.cart_md5 != $order->getProductsHash())} {* Товары *}
   - Изменился состав товаров 
{/if}
{if floatval($order_before.totalcost) != floatval($order.totalcost)}
   {static_call var=cost callback=['\Catalog\Model\CurrencyApi','getDefaultCurrency']}
   - Общая сумма заказа - {$order.totalcost|format_price} {$cost.title}
{/if}
{if $address_before != $order->getAddress()->getLineView()} {* Адрес *}
   - Адрес доставки - "{$order->getAddress()->getLineView()}"
{/if}
{if $order_before.delivery != $order.delivery} {* Способ доставки *}
   {$delivery=$order->getDelivery()} 
   - Способ доставки Вашего товара - "{$delivery.title}" (Стоимость: {$order->getDeliveryCostText($delivery)})
{/if}
{if $order_before.warehouse != $order.warehouse} {* Склад *}
   {$warehouse=$order->getWarehouse()} 
   - Склад самовывоза - "{$warehouse.title}" (Адрес: {$warehouse.adress}) 
{/if}
{if $order_before.contact_person != $order.contact_person} {* Контактное лицо *}
   - Контактное лицо - "{$order.contact_person}"
{/if}
{if $order_before.payment != $order.payment} {* Способ оплаты *}
   {$payment=$order->getPayment()} 
   - Способ оплаты у Вашего заказа "{$payment.title}".
   {if $order->canOnlinePay()}<a href="{$order->getOnlinePayUrl()}" class="formSave">Перейти к оплате</a>{elseif $payment->hasDocs()}{$type_object=$payment->getTypeObject()}
   {foreach from=$type_object->getDocsName() key=key item=doc}
<a href="{$type_object->getDocUrl($key, true)}" target="_blank">{$doc.title}</a>{if !$doc@last}<br/>{/if}
   {/foreach}
   {/if}
{/if}
{if $order_before.user_text != $order.user_text && !empty($order.user_text)} {* Комментарий пользователю *}
   - Текст покупателю: 
   "{$order.user_text}"
{/if}

--------

Все подробности заказа Вы можете посмотреть в <a href="{$router->getUrl('shop-front-myorders', [], true)}">личном кабинете</a>, 
там же будет виден текущий статус Вашего заказа.

С наилучшими пожеланиями, 
     администрация интернет магазина {$url->getDomainStr()}.
</pre>