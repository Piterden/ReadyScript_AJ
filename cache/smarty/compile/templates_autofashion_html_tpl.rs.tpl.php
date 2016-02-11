<?php /* Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:57
         compiled from "/home/groupvm/www/site22/public_html/templates/autofashion/html.tpl" */ ?>
<?php /*%%SmartyHeaderCode:103728391456942ca9e62541-01484246%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6b916975b8026ee95b1acf06608ee4a3bff576a0' => 
    array (
      0 => '/home/groupvm/www/site22/public_html/templates/autofashion/html.tpl',
      1 => 1452548185,
      2 => 'rs',
    ),
  ),
  'nocache_hash' => '103728391456942ca9e62541-01484246',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'app' => 0,
    'css' => 0,
    'js' => 0,
    'body' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_56942ca9f0e7e3_38222172',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56942ca9f0e7e3_38222172')) {function content_56942ca9f0e7e3_38222172($_smarty_tpl) {?><!DOCTYPE <?php echo $_smarty_tpl->tpl_vars['app']->value->getDoctype();?>
>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<html>
<head <?php echo $_smarty_tpl->tpl_vars['app']->value->getHeadAttributes(true);?>
>
<?php echo $_smarty_tpl->tpl_vars['app']->value->meta->get();?>

<title><?php echo $_smarty_tpl->tpl_vars['app']->value->title->get();?>
</title>
<?php  $_smarty_tpl->tpl_vars['css'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['css']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['app']->value->getCss(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['css']->key => $_smarty_tpl->tpl_vars['css']->value) {
$_smarty_tpl->tpl_vars['css']->_loop = true;
?>
	<?php echo $_smarty_tpl->tpl_vars['css']->value['params']['before'];?>
<link <?php if ($_smarty_tpl->tpl_vars['css']->value['params']['type']!==false) {?>type="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['css']->value['params']['type'])===null||$tmp==='' ? "text/css" : $tmp);?>
"<?php }?> href="<?php echo $_smarty_tpl->tpl_vars['css']->value['file'];?>
" <?php if ($_smarty_tpl->tpl_vars['css']->value['params']['media']!==false) {?>media="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['css']->value['params']['media'])===null||$tmp==='' ? "all" : $tmp);?>
"<?php }?> rel="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['css']->value['params']['rel'])===null||$tmp==='' ? "stylesheet" : $tmp);?>
"><?php echo $_smarty_tpl->tpl_vars['css']->value['params']['after'];?>

<?php } ?>
<script>
    var global = <?php echo $_smarty_tpl->tpl_vars['app']->value->getJsonJsVars();?>
;
</script>
<?php  $_smarty_tpl->tpl_vars['js'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['js']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['app']->value->getJs(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['js']->key => $_smarty_tpl->tpl_vars['js']->value) {
$_smarty_tpl->tpl_vars['js']->_loop = true;
?>
	<?php echo $_smarty_tpl->tpl_vars['js']->value['params']['before'];?>
<script type="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['js']->value['params']['type'])===null||$tmp==='' ? "text/javascript" : $tmp);?>
" src="<?php echo $_smarty_tpl->tpl_vars['js']->value['file'];?>
"></script><?php echo $_smarty_tpl->tpl_vars['js']->value['params']['after'];?>

<?php } ?>
<?php if ($_smarty_tpl->tpl_vars['app']->value->getJsCode()!='') {?>
	<script language="JavaScript"><?php echo $_smarty_tpl->tpl_vars['app']->value->getJsCode();?>
</script>
<?php }?>
<?php echo $_smarty_tpl->tpl_vars['app']->value->getAnyHeadData();?>

</head>
<body <?php if ($_smarty_tpl->tpl_vars['app']->value->getBodyClass()!='') {?>class="<?php echo $_smarty_tpl->tpl_vars['app']->value->getBodyClass();?>
"<?php }?>>
    <?php echo $_smarty_tpl->tpl_vars['body']->value;?>

    
    <?php  $_smarty_tpl->tpl_vars['js'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['js']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['app']->value->getJs('footer'); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['js']->key => $_smarty_tpl->tpl_vars['js']->value) {
$_smarty_tpl->tpl_vars['js']->_loop = true;
?>
	    <?php echo $_smarty_tpl->tpl_vars['js']->value['params']['before'];?>
<script type="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['js']->value['params']['type'])===null||$tmp==='' ? "text/javascript" : $tmp);?>
" src="<?php echo $_smarty_tpl->tpl_vars['js']->value['file'];?>
"></script><?php echo $_smarty_tpl->tpl_vars['js']->value['params']['after'];?>

    <?php } ?>    
</body>
</html><?php }} ?>
