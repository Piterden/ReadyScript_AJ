<a href="{$router->getUrl('wishlist-front-wishlist')}" class="wishlistButton">
    <img src="{$THEME_IMG}/wishlist.png" alt="Wishlist">
    {if $wishCount > 0}
	    <div class="countBlock floatWishAmount">{$wishCount}</div>
    {/if}
</a>