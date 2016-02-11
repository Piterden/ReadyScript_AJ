<?php 
/*
       * Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:55
       * compiled from "/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/table/coltype/actions.tpl"
       */
?>
<?php
 
/* %%SmartyHeaderCode:94488275156942ca70e01c4-87944218%% */
if (! defined ( 'SMARTY_DIR' )) exit ( 'no direct access allowed' );
$_valid = $_smarty_tpl->decodeProperties ( array('file_dependency' => array('5c1d9c412b9e811c77954943ad609d02f94f967b' => array(0 => '/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/table/coltype/actions.tpl',1 => 1452522616,2 => 'rs' 
) 
),'nocache_hash' => '94488275156942ca70e01c4-87944218','function' => array(),'variables' => array('cell' => 0,'item' => 0 
),'has_nocache_code' => false,'version' => 'Smarty-3.1.18','unifunc' => 'content_56942ca70f0781_36018099' 
), false ); /* /%%SmartyHeaderCode%% */
?>
<?php if ($_valid && !is_callable('content_56942ca70f0781_36018099')) {function content_56942ca70f0781_36018099($_smarty_tpl) {?><div
	class="tools">
    <?php
		
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable ();
		$_smarty_tpl->tpl_vars['item']->_loop = false;
		$_from = $_smarty_tpl->tpl_vars['cell']->value->getActions ();
		if (! is_array ( $_from ) && ! is_object ( $_from )) {
			settype ( $_from, 'array' );
		}
		foreach($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
			$_smarty_tpl->tpl_vars['item']->_loop = true;
			?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['item']->value->getTemplate(), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('tool'=>$_smarty_tpl->tpl_vars['item']->value), 0);?>

    <?php } ?>
</div><?php }} ?>
