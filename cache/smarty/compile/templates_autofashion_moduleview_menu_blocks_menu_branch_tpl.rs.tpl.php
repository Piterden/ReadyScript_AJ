<?php 
/*
       * Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:57
       * compiled from "/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/menu/blocks/menu/branch.tpl"
       */
?>
<?php
 
/* %%SmartyHeaderCode:5784181856942ca98bfb46-21627936%% */
if (! defined ( 'SMARTY_DIR' )) exit ( 'no direct access allowed' );
$_valid = $_smarty_tpl->decodeProperties ( array('file_dependency' => array('cd0b29743c8db81933c292ea9e8c2baabfe176f7' => array(0 => '/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/menu/blocks/menu/branch.tpl',1 => 1452548197,2 => 'rs' 
) 
),'nocache_hash' => '5784181856942ca98bfb46-21627936','function' => array(),'variables' => array('menu_level' => 0,'item' => 0 
),'has_nocache_code' => false,'version' => 'Smarty-3.1.18','unifunc' => 'content_56942ca990bd97_87949283' 
), false ); /* /%%SmartyHeaderCode%% */
?>
<?php


if ($_valid && ! is_callable ( 'content_56942ca990bd97_87949283' )) {
	function content_56942ca990bd97_87949283($_smarty_tpl)
	{
		?><?php

		
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable ();
		$_smarty_tpl->tpl_vars['item']->_loop = false;
		$_from = $_smarty_tpl->tpl_vars['menu_level']->value;
		if (! is_array ( $_from ) && ! is_object ( $_from )) {
			settype ( $_from, 'array' );
		}
		foreach($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
			$_smarty_tpl->tpl_vars['item']->_loop = true;
			?>
<li
	class="<?php if (!empty($_smarty_tpl->tpl_vars['item']->value['child'])) {?>node<?php }?><?php if ($_smarty_tpl->tpl_vars['item']->value['fields']['typelink']=='separator') {?> separator<?php }?><?php if ($_smarty_tpl->tpl_vars['item']->value['fields']->isAct()) {?> act<?php }?>"
	<?php if ($_smarty_tpl->tpl_vars['item']->value['fields']['typelink']!='separator') {?>
	<?php echo $_smarty_tpl->tpl_vars['item']->value['fields']->getDebugAttributes();?>
	<?php }?>>
    <?php if ($_smarty_tpl->tpl_vars['item']->value['fields']['typelink']!='separator') {?>
        <a
	href="<?php echo $_smarty_tpl->tpl_vars['item']->value['fields']->getHref();?>
"
	<?php if ($_smarty_tpl->tpl_vars['item']->value['fields']['target_blank']) {?>
	target="_blank" <?php }?>><?php echo $_smarty_tpl->tpl_vars['item']->value['fields']['title'];?>
</a>
    <?php } else { ?>
        &nbsp;
    <?php }?>
    <?php if (!empty($_smarty_tpl->tpl_vars['item']->value['child'])) {?>
    <ul>
        <?php echo $_smarty_tpl->getSubTemplate ("%THEME%/blocks/menu/branch.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('menu_level'=>$_smarty_tpl->tpl_vars['item']->value['child']), 0);?>

    </ul>
    <?php }?>
</li>
<?php } ?><?php }} ?>
