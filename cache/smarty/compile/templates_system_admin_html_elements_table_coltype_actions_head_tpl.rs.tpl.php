<?php 
/*
       * Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:55
       * compiled from "/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/table/coltype/actions_head.tpl"
       */
?>
<?php
 
/* %%SmartyHeaderCode:16758198456942ca70968e0-48649025%% */
if (! defined ( 'SMARTY_DIR' )) exit ( 'no direct access allowed' );
$_valid = $_smarty_tpl->decodeProperties ( array('file_dependency' => array('c9ff8a0b17448b357763a79e3c7d9e6534aa0697' => array(0 => '/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/table/coltype/actions_head.tpl',1 => 1452522616,2 => 'rs' 
) 
),'nocache_hash' => '16758198456942ca70968e0-48649025','function' => array(),'variables' => array('cell' => 0 
),'has_nocache_code' => false,'version' => 'Smarty-3.1.18','unifunc' => 'content_56942ca70a6679_57277355' 
), false ); /* /%%SmartyHeaderCode%% */
?>
<?php


if ($_valid && ! is_callable ( 'content_56942ca70a6679_57277355' )) {
	function content_56942ca70a6679_57277355($_smarty_tpl)
	{
		?><?php

		
if (! is_callable ( 'smarty_block_t' )) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/block.t.php';
		?><?php

		
if ($_smarty_tpl->tpl_vars['cell']->value->property['SettingsUrl']) {
			?>
<a
	data-url="<?php echo $_smarty_tpl->tpl_vars['cell']->value->property['SettingsUrl'];?>
"
	class="options crud-add"
	title="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Настройка таблицы<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
">&nbsp;</a>
<?php }?><?php }} ?>
