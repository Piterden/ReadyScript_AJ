{$order=$data->order}{$order_before=$data->order->before_this}
{if $order_before.status != $order.status} {* Статус *}
   Заказ №{$order.order_num} от {$order.dateof|date_format:"%d.%m.%Y"}, изменился статус на "{$order->getStatus()->title}".
   {$url->getDomainStr()}
{else}
    Заказ №{$order.order_num} от {$order.dateof|date_format:"%d.%m.%Y"} был изменен.
    Все подробности на сайте {$url->getDomainStr()}
{/if}
