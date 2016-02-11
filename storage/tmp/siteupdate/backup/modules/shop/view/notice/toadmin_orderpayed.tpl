<p>Уважаемый, администратор! На сайте {$url->getDomainStr()} оплачен заказ N{$data->order.order_num}.
<a href="{$router->getAdminUrl('edit', ["id" => $data->order.id], 'shop-orderctrl', true)}">Перейти к заказу</a></p>

<p>Автоматическая рассылка {$url->getDomainStr()}.</p>