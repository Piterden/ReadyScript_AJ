<?php 
/*
       * Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:57
       * compiled from "/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/main/blocks/logo/logo.tpl"
       */
?>
<?php
 
/* %%SmartyHeaderCode:115298090256942ca9826947-45332152%% */
if (! defined ( 'SMARTY_DIR' )) exit ( 'no direct access allowed' );
$_valid = $_smarty_tpl->decodeProperties ( array('file_dependency' => array('4be57b62a6d7acceb9d8084b414acfc3164876b7' => array(0 => '/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/main/blocks/logo/logo.tpl',1 => 1452548196,2 => 'rs' 
) 
),'nocache_hash' => '115298090256942ca9826947-45332152','function' => array(),'variables' => array('site_config' => 0,'link' => 0,'width' => 0,'height' => 0 
),'has_nocache_code' => false,'version' => 'Smarty-3.1.18','unifunc' => 'content_56942ca9857953_74927962' 
), false ); /* /%%SmartyHeaderCode%% */
?>
<?php


if ($_valid && ! is_callable ( 'content_56942ca9857953_74927962' )) {
	function content_56942ca9857953_74927962($_smarty_tpl)
	{
		?><?php

		
if (! is_callable ( 'smarty_function_adminUrl' )) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/function.adminurl.php';
		?><?php

		
if ($_smarty_tpl->tpl_vars['site_config']->value['logo']) {
			?>
    <?php if ($_smarty_tpl->tpl_vars['link']->value!=' ') {?><a
	href="<?php echo $_smarty_tpl->tpl_vars['link']->value;?>
"
	class="logo"><?php }?>
    <img
	src="<?php echo $_smarty_tpl->tpl_vars['site_config']->value['__logo']->getUrl($_smarty_tpl->tpl_vars['width']->value,$_smarty_tpl->tpl_vars['height']->value);?>
">
	<div class="slogan">Фирменный магазин одежды с климат-контролем</div>
    <?php if ($_smarty_tpl->tpl_vars['link']->value!=' ') {?></a><?php }?>
<span class="slogan"><?php echo $_smarty_tpl->tpl_vars['site_config']->value['slogan'];?>
</span>
<?php } else { ?>
    <?php ob_start();?><?php echo smarty_function_adminUrl(array('do'=>false,'mod_controller'=>"site-options"),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ("theme:default/block_stub.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('class'=>"noBack blockSmall blockLeft blockLogo",'do'=>array($_tmp1=>t("Добавьте логотип"))), 0);?>

<?php }?><?php }} ?>
