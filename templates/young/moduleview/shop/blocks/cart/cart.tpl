<a class="cartBlock showCart" href="{$router->getUrl('shop-front-cartpage')}" id="cart">
    <div class="icon"></div>
    <span class="products">
        <span class="value">{$cart_info.items_count}</span> {t count=$cart_info.items_count}[plural:%count:товар|товара|товаров]{/t}
    </span>
    <span class="priceBlock cost{if $cart_info.total==0} hidden{/if}">
        на <span class="value">{$cart_info.total}</span>
    </span>
</a>