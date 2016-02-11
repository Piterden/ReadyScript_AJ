<?php 
/*
       * Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:57
       * compiled from "/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/shop/blocks/cart/cart.tpl"
       */
?>
<?php
 
/* %%SmartyHeaderCode:35817505856942ca99d1ca2-57384012%% */
if (! defined ( 'SMARTY_DIR' )) exit ( 'no direct access allowed' );
$_valid = $_smarty_tpl->decodeProperties ( array('file_dependency' => array('577ec8ee9a33ab5b0431f4ba92c9df3c28403137' => array(0 => '/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/shop/blocks/cart/cart.tpl',1 => 1452548200,2 => 'rs' 
) 
),'nocache_hash' => '35817505856942ca99d1ca2-57384012','function' => array(),'variables' => array('router' => 0,'THEME_IMG' => 0,'cart_info' => 0 
),'has_nocache_code' => false,'version' => 'Smarty-3.1.18','unifunc' => 'content_56942ca99e50c1_45755834' 
), false ); /* /%%SmartyHeaderCode%% */
?>
<?php if ($_valid && !is_callable('content_56942ca99e50c1_45755834')) {function content_56942ca99e50c1_45755834($_smarty_tpl) {?><a
	class="basket showCart" id="cart"
	href="<?php echo $_smarty_tpl->tpl_vars['router']->value->getUrl('shop-front-cartpage');?>
"> <img
	src="<?php echo $_smarty_tpl->tpl_vars['THEME_IMG']->value;?>
/cart.png"
	alt="Cart">
	<?php if ($_smarty_tpl->tpl_vars['cart_info']->value['items_count']>0) {?>
		<div class="countBlock floatCartAmount"><?php echo $_smarty_tpl->tpl_vars['cart_info']->value['items_count'];?>
</div>
	<?php }?>
</a><?php }} ?>
