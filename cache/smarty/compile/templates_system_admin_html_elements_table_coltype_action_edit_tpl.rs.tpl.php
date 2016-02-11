<?php 
/*
       * Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:55
       * compiled from "/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/table/coltype/action/edit.tpl"
       */
?>
<?php
 
/* %%SmartyHeaderCode:109528860556942ca70f3868-12666006%% */
if (! defined ( 'SMARTY_DIR' )) exit ( 'no direct access allowed' );
$_valid = $_smarty_tpl->decodeProperties ( array('file_dependency' => array('96d7b3ce3e271df8edc1b814674a9948a0897855' => array(0 => '/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/table/coltype/action/edit.tpl',1 => 1452522616,2 => 'rs' 
) 
),'nocache_hash' => '109528860556942ca70f3868-12666006','function' => array(),'variables' => array('tool' => 0,'cell' => 0 
),'has_nocache_code' => false,'version' => 'Smarty-3.1.18','unifunc' => 'content_56942ca710c4c3_57176154' 
), false ); /* /%%SmartyHeaderCode%% */
?>
<?php if ($_valid && !is_callable('content_56942ca710c4c3_57176154')) {function content_56942ca710c4c3_57176154($_smarty_tpl) {?><a
	href="<?php echo $_smarty_tpl->tpl_vars['cell']->value->getHref($_smarty_tpl->tpl_vars['tool']->value->getHrefPattern());?>
"
	title="<?php echo $_smarty_tpl->tpl_vars['tool']->value->getTitle();?>
"
	class="tool <?php echo $_smarty_tpl->tpl_vars['tool']->value->getClass();?>
"
	<?php echo $_smarty_tpl->tpl_vars['cell']->value->getLineAttr($_smarty_tpl->tpl_vars['tool']->value);?>></a><?php }} ?>
