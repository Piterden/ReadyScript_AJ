<a class="basket showCart" id="cart" href="{$router->getUrl('shop-front-cartpage')}">
	<img src="{$THEME_IMG}/cart.png" alt="Cart">
	{if $cart_info.items_count > 0}
		<div class="countBlock floatCartAmount">{$cart_info.items_count}</div>
	{/if}
</a>