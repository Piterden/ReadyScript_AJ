<?php 
/*
       * Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:57
       * compiled from "/home/groupvm/www/site22/public_html/templates/system/meta.tpl"
       */
?>
<?php
 
/* %%SmartyHeaderCode:12381294856942ca9f181d6-46002653%% */
if (! defined ( 'SMARTY_DIR' )) exit ( 'no direct access allowed' );
$_valid = $_smarty_tpl->decodeProperties ( array('file_dependency' => array('e337f6cacf39c4cd6d754e287e3eb49f92da46ac' => array(0 => '/home/groupvm/www/site22/public_html/templates/system/meta.tpl',1 => 1452522616,2 => 'rs' 
) 
),'nocache_hash' => '12381294856942ca9f181d6-46002653','function' => array(),'variables' => array('meta_vars' => 0,'tagparam' => 0,'key' => 0,'value' => 0 
),'has_nocache_code' => false,'version' => 'Smarty-3.1.18','unifunc' => 'content_56942ca9f2a2a5_27552995' 
), false ); /* /%%SmartyHeaderCode%% */
?>
<?php


if ($_valid && ! is_callable ( 'content_56942ca9f2a2a5_27552995' )) {
	function content_56942ca9f2a2a5_27552995($_smarty_tpl)
	{
		?><?php

		
$_smarty_tpl->tpl_vars['tagparam'] = new Smarty_Variable ();
		$_smarty_tpl->tpl_vars['tagparam']->_loop = false;
		$_from = $_smarty_tpl->tpl_vars['meta_vars']->value;
		if (! is_array ( $_from ) && ! is_object ( $_from )) {
			settype ( $_from, 'array' );
		}
		foreach($_from as $_smarty_tpl->tpl_vars['tagparam']->key => $_smarty_tpl->tpl_vars['tagparam']->value) {
			$_smarty_tpl->tpl_vars['tagparam']->_loop = true;
			?>
<meta
	<?php
			
$_smarty_tpl->tpl_vars['value'] = new Smarty_Variable ();
			$_smarty_tpl->tpl_vars['value']->_loop = false;
			$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable ();
			$_from = $_smarty_tpl->tpl_vars['tagparam']->value;
			if (! is_array ( $_from ) && ! is_object ( $_from )) {
				settype ( $_from, 'array' );
			}
			foreach($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value) {
				$_smarty_tpl->tpl_vars['value']->_loop = true;
				$_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
				?>
	<?php echo $_smarty_tpl->tpl_vars['key']->value;?>="<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
"
	<?php } ?>>
<?php } ?><?php }} ?>
