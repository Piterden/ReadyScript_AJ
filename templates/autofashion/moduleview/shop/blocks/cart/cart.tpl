<a class="basket showCart" id="cart" href="{$router->getUrl('shop-front-cartpage')}">
    <img src="{$THEME_IMG}/cart.png" alt="Cart">
    <div class="countBlock floatCartAmount" {if $cart_info.items_count==0 } style="display:none;" {/if}>
        {$cart_info.items_count}
    </div>
</a>
<script>
jQuery(document).ready(function($) {
    var amountBlk = '.countBlock.floatCartAmount',
        checkAmount = function() {
        	var $amountBlk = $(amountBlk);
        	if ($amountBlk.html() > 0) {
        		$amountBlk.show();
        	} else {
        		$amountBlk.hide();
        	}
        };

    checkAmount();
    $(amountBlk).bind("DOMSubtreeModified", checkAmount);
});
</script>
