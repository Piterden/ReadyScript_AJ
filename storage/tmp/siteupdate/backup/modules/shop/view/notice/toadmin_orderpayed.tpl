<pre>
Уважаемый, администратор! На сайте {$url->getDomainStr()} оплачен заказ N{$data->order.order_num}.
<a href="{$router->getAdminUrl('shop-orderctrl', ["do" => "edit", "id" => $data->order.id], true)}">Перейти к заказу</a>

Автоматическая рассылка {$url->getDomainStr()}.
</pre>