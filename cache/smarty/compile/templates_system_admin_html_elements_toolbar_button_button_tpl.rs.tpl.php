<?php /* Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:54
         compiled from "/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/toolbar/button/button.tpl" */ ?>
<?php /*%%SmartyHeaderCode:24174122056942ca6cf25d1-76611183%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0635d3e5263c582609995128311315760bafa06e' => 
    array (
      0 => '/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/toolbar/button/button.tpl',
      1 => 1452522616,
      2 => 'rs',
    ),
  ),
  'nocache_hash' => '24174122056942ca6cf25d1-76611183',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'button' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_56942ca6d26f40_78292515',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56942ca6d26f40_78292515')) {function content_56942ca6d26f40_78292515($_smarty_tpl) {?><a <?php if ($_smarty_tpl->tpl_vars['button']->value->getHref()!='') {?>href="<?php echo $_smarty_tpl->tpl_vars['button']->value->getHref();?>
"<?php }?> <?php echo $_smarty_tpl->tpl_vars['button']->value->getAttrLine();?>
><?php echo $_smarty_tpl->tpl_vars['button']->value->getTitle();?>
</a><?php }} ?>
