<div class="wishBox" title="Удалить из желаемых">
	<form method="POST" class="del_wish_form hidden" id="del_wish_form_{$product_id}">
		{$this_controller->myBlockIdInput()}
		<input type="hidden" name="method" value="del">
		<input type="hidden" name="product_id" value="{$product_id}">
		<input type="submit" value="OK" name="del_wish_form_{$product_id}" class="del_form_{$product_id}">
	</form>
    <img src="{$THEME_IMG}/wishlist.png" alt="Удалить из желаемых">
</div>
<div class="wishDesc">
	Удалить из списка<br>желаемых покупок
</div>