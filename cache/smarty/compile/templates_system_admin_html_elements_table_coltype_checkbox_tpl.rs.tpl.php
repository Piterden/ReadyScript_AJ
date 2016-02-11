<?php 
/*
       * Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:55
       * compiled from "/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/table/coltype/checkbox.tpl"
       */
?>
<?php
 
/* %%SmartyHeaderCode:100421703256942ca70a9909-80104969%% */
if (! defined ( 'SMARTY_DIR' )) exit ( 'no direct access allowed' );
$_valid = $_smarty_tpl->decodeProperties ( array('file_dependency' => array('a7dc8504be8264bf957dc45c6121cf1dd048206f' => array(0 => '/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/table/coltype/checkbox.tpl',1 => 1452522616,2 => 'rs' 
) 
),'nocache_hash' => '100421703256942ca70a9909-80104969','function' => array(),'variables' => array('cell' => 0 
),'has_nocache_code' => false,'version' => 'Smarty-3.1.18','unifunc' => 'content_56942ca70b7b33_98952231' 
), false ); /* /%%SmartyHeaderCode%% */
?>
<?php if ($_valid && !is_callable('content_56942ca70b7b33_98952231')) {function content_56942ca70b7b33_98952231($_smarty_tpl) {?><input
	type="checkbox"
	name="<?php echo $_smarty_tpl->tpl_vars['cell']->value->getName();?>
"
	value="<?php echo $_smarty_tpl->tpl_vars['cell']->value->getValue();?>
"
	<?php echo $_smarty_tpl->tpl_vars['cell']->value->getCellAttr();?>><?php }} ?>
