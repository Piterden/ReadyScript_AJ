<?php /* Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:55
         compiled from "/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/table/coltype/strhead.tpl" */ ?>
<?php /*%%SmartyHeaderCode:175019409956942ca7072a13-46817152%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd366b512db0138d478b94a07170e760c1959d9dc' => 
    array (
      0 => '/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/table/coltype/strhead.tpl',
      1 => 1452522616,
      2 => 'rs',
    ),
  ),
  'nocache_hash' => '175019409956942ca7072a13-46817152',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'cell' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_56942ca7092551_16883155',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56942ca7092551_16883155')) {function content_56942ca7092551_16883155($_smarty_tpl) {?><?php if (!empty($_smarty_tpl->tpl_vars['cell']->value->property['Sortable'])) {?>
    <a href="<?php echo $_smarty_tpl->tpl_vars['cell']->value->sorturl;?>
" class="call-update sortable <?php echo mb_strtolower($_smarty_tpl->tpl_vars['cell']->value->property['CurrentSort'], 'UTF-8');?>
"><?php echo $_smarty_tpl->tpl_vars['cell']->value->getTitle();?>
</a>
<?php } else { ?>
    <?php echo $_smarty_tpl->tpl_vars['cell']->value->getTitle();?>

<?php }?><?php }} ?>
