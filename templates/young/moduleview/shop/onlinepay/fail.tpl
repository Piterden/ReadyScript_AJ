<div class="paymentResult">
    <div class="fail"><span class="text">Оплата не произведена</span></div><br>
    {if $transaction->getOrder()->id}
        <a href="{$router->getUrl('shop-front-myorderview', ['order_id' => $transaction->getOrder()->order_num])}" class="colorButton">перейти к заказу</a>
    {else}
        <a href="{$router->getUrl('shop-front-mybalance')}" class="colorButton">перейти к лицевому счету</a>
    {/if}
</div>