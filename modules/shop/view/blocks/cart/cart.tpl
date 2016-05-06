<a class="basket showCart" id="cart" href="{$router->getUrl('shop-front-cartpage')}">
    <div class="cart"><span class="lineHolder"></span><span class="title">МОЯ КОРЗИНА</span></div>
    <p class="products">товаров: <span class="value">{$cart_info.items_count}</span></p>
    <p class="cost">сумма: <span class="value">{$cart_info.total}</span></p>
</a>