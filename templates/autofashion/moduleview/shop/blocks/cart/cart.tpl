<a class="basket showCart" id="cart" href="{$router->getUrl('shop-front-cartpage')}">
	<img src="{$THEME_IMG}/cart.png" alt="Cart">
	<div class="countBlock floatCartAmount"{if $cart_info.items_count == 0} style="display:none;"{/if}>
		{$cart_info.items_count}
	</div>
</a>
