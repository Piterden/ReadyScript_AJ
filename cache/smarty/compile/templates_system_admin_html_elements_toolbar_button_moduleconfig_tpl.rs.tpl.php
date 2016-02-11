<?php /* Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:54
         compiled from "/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/toolbar/button/moduleconfig.tpl" */ ?>
<?php /*%%SmartyHeaderCode:27159895456942ca6d2c076-18688833%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c4c1efdb4756d3f6ec6a18eadff08dc3ae9cb64b' => 
    array (
      0 => '/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/toolbar/button/moduleconfig.tpl',
      1 => 1452522616,
      2 => 'rs',
    ),
  ),
  'nocache_hash' => '27159895456942ca6d2c076-18688833',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'button' => 0,
    'Setup' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_56942ca6d62194_62303528',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56942ca6d62194_62303528')) {function content_56942ca6d62194_62303528($_smarty_tpl) {?><a <?php if ($_smarty_tpl->tpl_vars['button']->value->getHref()!='') {?>href="<?php echo $_smarty_tpl->tpl_vars['button']->value->getHref();?>
"<?php }?> <?php echo $_smarty_tpl->tpl_vars['button']->value->getAttrLine();?>
><img src="<?php echo $_smarty_tpl->tpl_vars['Setup']->value['IMG_PATH'];?>
/adminstyle/modoptions.png"><?php if ($_smarty_tpl->tpl_vars['button']->value->getTitle()) {?><span class="lmarg"><?php echo $_smarty_tpl->tpl_vars['button']->value->getTitle();?>
</span><?php }?></a><?php }} ?>
