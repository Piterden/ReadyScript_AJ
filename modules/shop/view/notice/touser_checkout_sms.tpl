{assign var=delivery value=$data->order->getDelivery()}
{assign var=address value=$data->order->getAddress()}
{assign var=pay value=$data->order->getPayment()}
{assign var=cart value=$data->order->getCart()}
{assign var=user value=$data->order->getUser()}
{assign var=order_data value=$cart->getOrderData(true, false)}
{assign var=products value=$cart->getProductItems()}
Спасибо! Ваш заказ №{$data->order->order_num} принят