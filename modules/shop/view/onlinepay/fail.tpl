{*
    В этом шаблоне доступна переменная $transaction
    Объект заказа можно получить так: $transaction->getOrder()
*}

<div class="failPay">
    <img src="{$THEME_IMG}/failpay.png">
    <p class="title">Оплата не произведена</p>
    {*<p></p>*}
    {if $transaction->getOrder()->id}
        <a href="{$router->getUrl('shop-front-myorderview', ['order_id' => $transaction->getOrder()->order_num])}">перейти к заказу</a>
    {else}
        <a href="{$router->getUrl('shop-front-mybalance')}">перейти к лицевому счету</a>
    {/if}
</div>