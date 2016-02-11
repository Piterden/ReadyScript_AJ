<?php /* Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:55
         compiled from "/home/groupvm/www/site22/public_html/modules/modcontrol/view/col_enabled.tpl" */ ?>
<?php /*%%SmartyHeaderCode:146621440956942ca70c30f6-37394183%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c3dae1183d48cdfff19549b9ebbbb690a4792102' => 
    array (
      0 => '/home/groupvm/www/site22/public_html/modules/modcontrol/view/col_enabled.tpl',
      1 => 1452522616,
      2 => 'rs',
    ),
  ),
  'nocache_hash' => '146621440956942ca70c30f6-37394183',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'cell' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_56942ca70dabc0_32157713',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56942ca70dabc0_32157713')) {function content_56942ca70dabc0_32157713($_smarty_tpl) {?><?php if (!is_callable('smarty_function_adminUrl')) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/function.adminurl.php';
?><?php if ($_smarty_tpl->tpl_vars['cell']->value->getRow('installed')) {?>
    <?php if ($_smarty_tpl->tpl_vars['cell']->value->getValue()) {?>Да<?php } else { ?>Нет<?php }?>
<?php } else { ?>
    <a class="not_installed crud-get" href="<?php echo smarty_function_adminUrl(array('do'=>'ajaxreinstall','module'=>$_smarty_tpl->tpl_vars['cell']->value->getRow('class')),$_smarty_tpl);?>
" title="Нажмите, чтобы установить модуль" style="white-space:nowrap">Не установлен</a>
<?php }?><?php }} ?>
