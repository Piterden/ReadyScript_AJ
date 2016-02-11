<?php 
/*
       * Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:57
       * compiled from "/home/groupvm/www/site22/public_html/templates/autofashion/layout.tpl"
       */
?>
<?php
 
/* %%SmartyHeaderCode:203006892756942ca96cb7f7-44278769%% */
if (! defined ( 'SMARTY_DIR' )) exit ( 'no direct access allowed' );
$_valid = $_smarty_tpl->decodeProperties ( array('file_dependency' => array('107d50a767257fbcabbc63a57ea637de812b6466' => array(0 => '/home/groupvm/www/site22/public_html/templates/autofashion/layout.tpl',1 => 1452548185,2 => 'rs' 
) 
),'nocache_hash' => '203006892756942ca96cb7f7-44278769','function' => array(),'variables' => array('THEME_SHADE' => 0,'shop_config' => 0,'app' => 0 
),'has_nocache_code' => false,'version' => 'Smarty-3.1.18','unifunc' => 'content_56942ca9753014_92604607' 
), false ); /* /%%SmartyHeaderCode%% */
?>
<?php


if ($_valid && ! is_callable ( 'content_56942ca9753014_92604607' )) {
	function content_56942ca9753014_92604607($_smarty_tpl)
	{
		?><?php

		
if (! is_callable ( 'smarty_function_addcss' )) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/function.addcss.php';
		if (! is_callable ( 'smarty_function_addjs' )) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/function.addjs.php';
		if (! is_callable ( 'smarty_function_addmeta' )) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/function.addmeta.php';
		if (! is_callable ( 'smarty_function_tryinclude' )) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/function.tryinclude.php';
		?>
<?php echo smarty_function_addcss(array('file'=>"/rss-news/",'basepath'=>"root",'rel'=>"alternate",'type'=>"application/rss+xml",'title'=>"t('Новости')"),$_smarty_tpl);?>
<?php echo smarty_function_addcss(array('file'=>"reset.css"),$_smarty_tpl);?>
<?php echo smarty_function_addcss(array('file'=>"bootstrap.min.css"),$_smarty_tpl);?>
<?php echo smarty_function_addcss(array('file'=>"selectordie.css"),$_smarty_tpl);?>
<?php echo smarty_function_addcss(array('file'=>"style.css?v=3"),$_smarty_tpl);?>
<?php echo smarty_function_addcss(array('file'=>"style-j.css?v=3"),$_smarty_tpl);?>
<?php if ($_smarty_tpl->tpl_vars['THEME_SHADE']->value!=='orange') {?><?php echo smarty_function_addcss(array('file'=>((string)$_smarty_tpl->tpl_vars['THEME_SHADE']->value).".css"),$_smarty_tpl);?>
<?php }?><?php echo smarty_function_addcss(array('file'=>"colorbox.css"),$_smarty_tpl);?>
<?php echo smarty_function_addjs(array('file'=>"bootstrap.min.js",'unshift'=>true,'header'=>true),$_smarty_tpl);?>
<?php echo smarty_function_addjs(array('file'=>"html5shiv.js",'unshift'=>true,'header'=>true),$_smarty_tpl);?>
<?php echo smarty_function_addjs(array('file'=>"jquery.min.js",'name'=>"jquery",'basepath'=>"common",'unshift'=>true,'header'=>true),$_smarty_tpl);?>
<?php echo smarty_function_addjs(array('file'=>"jquery.autocomplete.js"),$_smarty_tpl);?>
<?php echo smarty_function_addjs(array('file'=>"jquery.activetabs.js"),$_smarty_tpl);?>
<?php echo smarty_function_addjs(array('file'=>"jquery.form.js",'basepath'=>"common"),$_smarty_tpl);?>
<?php echo smarty_function_addjs(array('file'=>"jquery.cookie.js",'basepath'=>"common"),$_smarty_tpl);?>
<?php echo smarty_function_addjs(array('file'=>"jquery.switcher.js"),$_smarty_tpl);?>
<?php echo smarty_function_addjs(array('file'=>"jquery.ajaxpagination.js"),$_smarty_tpl);?>
<?php echo smarty_function_addjs(array('file'=>"jquery.colorbox.js"),$_smarty_tpl);?>
<?php echo smarty_function_addjs(array('file'=>"modernizr.touch.js"),$_smarty_tpl);?>
<?php echo smarty_function_addjs(array('file'=>"selectordie.min.js"),$_smarty_tpl);?>
<?php echo smarty_function_addjs(array('file'=>"common.js"),$_smarty_tpl);?>
<?php echo smarty_function_addjs(array('file'=>"theme.js"),$_smarty_tpl);?>
<?php echo smarty_function_addmeta(array('http-equiv'=>"X-UA-Compatible",'content'=>"IE=Edge",'unshift'=>true),$_smarty_tpl);?>
<?php $_smarty_tpl->tpl_vars['shop_config'] = new Smarty_variable(\RS\Config\Loader::byModule('shop'), null, 0);?><?php if ($_smarty_tpl->tpl_vars['shop_config']->value===false) {?><?php echo $_smarty_tpl->tpl_vars['app']->value->setBodyClass('shopBase',true);?>
<?php }?><?php echo $_smarty_tpl->tpl_vars['app']->value->setDoctype('HTML');?>

<?php echo $_smarty_tpl->tpl_vars['app']->value->blocks->renderLayout();?>



<?php echo smarty_function_tryinclude(array('file'=>"%THEME%/scripts.tpl"),$_smarty_tpl);?>
<?php }} ?>
