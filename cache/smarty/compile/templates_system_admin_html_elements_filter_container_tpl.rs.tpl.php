<?php /* Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:54
         compiled from "/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/filter/container.tpl" */ ?>
<?php /*%%SmartyHeaderCode:24749363356942ca6e09237-61491760%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2e033dc28d2a51090b1f65ef5f7cf3b17dcc7d03' => 
    array (
      0 => '/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/filter/container.tpl',
      1 => 1452522616,
      2 => 'rs',
    ),
  ),
  'nocache_hash' => '24749363356942ca6e09237-61491760',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'fcontainer' => 0,
    'line' => 0,
    'sec_cont' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_56942ca6e22978_50457576',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56942ca6e22978_50457576')) {function content_56942ca6e22978_50457576($_smarty_tpl) {?>

<div class="formfilter">
    <a class="close"></a>
    <?php  $_smarty_tpl->tpl_vars['line'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['line']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['fcontainer']->value->getLines(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['line']->key => $_smarty_tpl->tpl_vars['line']->value) {
$_smarty_tpl->tpl_vars['line']->_loop = true;
?>
        <?php echo $_smarty_tpl->tpl_vars['line']->value->getView();?>

    <?php } ?>                

    <div class="more">
        <?php  $_smarty_tpl->tpl_vars['sec_cont'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sec_cont']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['fcontainer']->value->getSecContainers(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sec_cont']->key => $_smarty_tpl->tpl_vars['sec_cont']->value) {
$_smarty_tpl->tpl_vars['sec_cont']->_loop = true;
?>
            <?php echo $_smarty_tpl->tpl_vars['sec_cont']->value->getView();?>

        <?php } ?>
    </div>
</div><?php }} ?>
