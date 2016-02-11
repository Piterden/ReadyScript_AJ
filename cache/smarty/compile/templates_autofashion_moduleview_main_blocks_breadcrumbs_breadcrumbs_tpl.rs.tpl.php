<?php 
/*
       * Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:57
       * compiled from "/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/main/blocks/breadcrumbs/breadcrumbs.tpl"
       */
?>
<?php
 
/* %%SmartyHeaderCode:1999914856942ca9aba2a6-46014236%% */
if (! defined ( 'SMARTY_DIR' )) exit ( 'no direct access allowed' );
$_valid = $_smarty_tpl->decodeProperties ( array('file_dependency' => array('45d05660a48e99e68fcb3fad0c38ee6b2238d2ef' => array(0 => '/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/main/blocks/breadcrumbs/breadcrumbs.tpl',1 => 1452548196,2 => 'rs' 
) 
),'nocache_hash' => '1999914856942ca9aba2a6-46014236','function' => array(),'variables' => array('app' => 0,'bc' => 0,'item' => 0 
),'has_nocache_code' => false,'version' => 'Smarty-3.1.18','unifunc' => 'content_56942ca9aea235_96684212' 
), false ); /* /%%SmartyHeaderCode%% */
?>
<?php if ($_valid && !is_callable('content_56942ca9aea235_96684212')) {function content_56942ca9aea235_96684212($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars['bc'] = new Smarty_variable($_smarty_tpl->tpl_vars['app']->value->breadcrumbs->getBreadCrumbs(), null, 0);?>
<?php if (!empty($_smarty_tpl->tpl_vars['bc']->value)) {?>
<div class="breadcrumb-wrap">
	<ul class="breadcrumb">
		<?php
			
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable ();
			$_smarty_tpl->tpl_vars['item']->_loop = false;
			$_from = $_smarty_tpl->tpl_vars['bc']->value;
			if (! is_array ( $_from ) && ! is_object ( $_from )) {
				settype ( $_from, 'array' );
			}
			$_smarty_tpl->tpl_vars['item']->index = - 1;
			foreach($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
				$_smarty_tpl->tpl_vars['item']->_loop = true;
				$_smarty_tpl->tpl_vars['item']->index ++;
				$_smarty_tpl->tpl_vars['item']->first = $_smarty_tpl->tpl_vars['item']->index === 0;
				?>
			<?php if (empty($_smarty_tpl->tpl_vars['item']->value['href'])) {?>
				<li <?php if ($_smarty_tpl->tpl_vars['item']->first) {?>
			class="first" <?php }?>><span><?php echo $_smarty_tpl->tpl_vars['item']->value['title'];?>
</span></li>
			<?php } else { ?>
				<li <?php if ($_smarty_tpl->tpl_vars['item']->first) {?>
			class="first" <?php }?>><a
			href="<?php echo $_smarty_tpl->tpl_vars['item']->value['href'];?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['title'];?>
</a></li>
			<?php }?>
		<?php } ?>
	</ul>
</div>
<?php }?><?php }} ?>
