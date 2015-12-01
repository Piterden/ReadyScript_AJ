<div class="wishBox" title="Добавить в желаемые">
	<form method="POST" class="add_wish_form hidden" id="add_wish_form_{$product_id}">
		{$this_controller->myBlockIdInput()}
		<input type="hidden" name="method" value="add">
		<input type="hidden" name="product_id" value="{$product_id}">
		<input type="submit" value="OK" name="add_wish_form_{$product_id}" class="add_form_{$product_id}">
	</form>
    <img src="{$THEME_IMG}/wishlist.png" alt="Добавить в желаемые">
</div>
<div class="wishDesc">
    Добавить в список<br>желаемых покупок
</div>