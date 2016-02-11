<?php /* Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:54
         compiled from "/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/filter/type/abstract.tpl" */ ?>
<?php /*%%SmartyHeaderCode:85328626556942ca6e4ec02-26354401%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '09d218acfe6bd1a7c2c6f5730abe250be3b0a282' => 
    array (
      0 => '/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/filter/type/abstract.tpl',
      1 => 1452522616,
      2 => 'rs',
    ),
  ),
  'nocache_hash' => '85328626556942ca6e4ec02-26354401',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'fitem' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_56942ca6e827b7_31064082',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56942ca6e827b7_31064082')) {function content_56942ca6e827b7_31064082($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['fitem']->value->getTitle()) {?><label <?php echo $_smarty_tpl->tpl_vars['fitem']->value->getTitleAttrString();?>
><?php echo $_smarty_tpl->tpl_vars['fitem']->value->getTitle();?>
</label><br><?php }?>

<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['fitem']->value->getPrefilters(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
    <?php echo $_smarty_tpl->tpl_vars['item']->value->getView();?>

<?php } ?>
    
<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['fitem']->value->tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
