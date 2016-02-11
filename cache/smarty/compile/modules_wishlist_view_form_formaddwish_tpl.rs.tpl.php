<?php 
/*
       * Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:57
       * compiled from "/home/groupvm/www/site22/public_html/modules/wishlist/view/form/formaddwish.tpl"
       */
?>
<?php
 
/* %%SmartyHeaderCode:132854980156942ca957e7a4-37560139%% */
if (! defined ( 'SMARTY_DIR' )) exit ( 'no direct access allowed' );
$_valid = $_smarty_tpl->decodeProperties ( array('file_dependency' => array('70799ddc9ea04cea6580ff888a7b2f8b0df7cb84' => array(0 => '/home/groupvm/www/site22/public_html/modules/wishlist/view/form/formaddwish.tpl',1 => 1452544585,2 => 'rs' 
) 
),'nocache_hash' => '132854980156942ca957e7a4-37560139','function' => array(),'variables' => array('product_id' => 0,'this_controller' => 0,'THEME_IMG' => 0 
),'has_nocache_code' => false,'version' => 'Smarty-3.1.18','unifunc' => 'content_56942ca9597b13_23442539' 
), false ); /* /%%SmartyHeaderCode%% */
?>
<?php if ($_valid && !is_callable('content_56942ca9597b13_23442539')) {function content_56942ca9597b13_23442539($_smarty_tpl) {?><div
	class="wishBox" title="Добавить в желаемые">
	<form method="POST" class="add_wish_form hidden"
		id="add_wish_form_<?php echo $_smarty_tpl->tpl_vars['product_id']->value;?>
">
		<?php echo $_smarty_tpl->tpl_vars['this_controller']->value->myBlockIdInput();?>

		<input type="hidden" name="method" value="add"> <input type="hidden"
			name="product_id"
			value="<?php echo $_smarty_tpl->tpl_vars['product_id']->value;?>
"> <input type="submit" value="OK"
			name="add_wish_form_<?php echo $_smarty_tpl->tpl_vars['product_id']->value;?>
"
			class="add_form_<?php echo $_smarty_tpl->tpl_vars['product_id']->value;?>
">
	</form>
	<img
		src="<?php echo $_smarty_tpl->tpl_vars['THEME_IMG']->value;?>
/wishlist.png"
		alt="Добавить в желаемые">
</div>
<div class="wishDesc">
	Добавить в список<br>желаемых покупок
</div><?php }} ?>
