<?php 
/*
       * Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:57
       * compiled from "/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/menu/blocks/menu/hor_menu.tpl"
       */
?>
<?php
 
/* %%SmartyHeaderCode:137088202156942ca98a3193-89140526%% */
if (! defined ( 'SMARTY_DIR' )) exit ( 'no direct access allowed' );
$_valid = $_smarty_tpl->decodeProperties ( array('file_dependency' => array('43aa149977a98bcb68d95b42555d857ba3024158' => array(0 => '/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/menu/blocks/menu/hor_menu.tpl',1 => 1452548197,2 => 'rs' 
) 
),'nocache_hash' => '137088202156942ca98a3193-89140526','function' => array(),'variables' => array('items' => 0 
),'has_nocache_code' => false,'version' => 'Smarty-3.1.18','unifunc' => 'content_56942ca98bc9c2_27606485' 
), false ); /* /%%SmartyHeaderCode%% */
?>
<?php


if ($_valid && ! is_callable ( 'content_56942ca98bc9c2_27606485' )) {
	function content_56942ca98bc9c2_27606485($_smarty_tpl)
	{
		?><?php

		
if (! is_callable ( 'smarty_function_adminUrl' )) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/function.adminurl.php';
		?><?php

		
if ($_smarty_tpl->tpl_vars['items']->value) {
			?>
<ul class="topMenu">
	    <?php echo $_smarty_tpl->getSubTemplate ("blocks/menu/branch.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('menu_level'=>$_smarty_tpl->tpl_vars['items']->value), 0);?>

	</ul>
<?php } else { ?>
    <?php ob_start();?><?php echo smarty_function_adminUrl(array('do'=>"add",'mod_controller'=>"menu-ctrl"),$_smarty_tpl);?>
<?php $_tmp2=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ("theme:autojack/block_stub.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('class'=>"noBack blockSmall blockLeft blockMenu",'do'=>array($_tmp2=>t("Добавьте пункт меню"))), 0);?>

<?php }?><?php }} ?>
