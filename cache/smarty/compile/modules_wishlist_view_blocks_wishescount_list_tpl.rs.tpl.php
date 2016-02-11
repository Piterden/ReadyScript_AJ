<?php /* Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:57
         compiled from "/home/groupvm/www/site22/public_html/modules/wishlist/view/blocks/wishescount/list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:195815528656942ca99f9e20-57022237%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cf3ed489de1ea91da2024225c6cb28f4c75d4bf0' => 
    array (
      0 => '/home/groupvm/www/site22/public_html/modules/wishlist/view/blocks/wishescount/list.tpl',
      1 => 1452544584,
      2 => 'rs',
    ),
  ),
  'nocache_hash' => '195815528656942ca99f9e20-57022237',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'router' => 0,
    'THEME_IMG' => 0,
    'wishCount' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_56942ca9a0b5d2_97528283',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56942ca9a0b5d2_97528283')) {function content_56942ca9a0b5d2_97528283($_smarty_tpl) {?><a href="<?php echo $_smarty_tpl->tpl_vars['router']->value->getUrl('wishlist-front-wishlist');?>
" class="wishlistButton">
    <img src="<?php echo $_smarty_tpl->tpl_vars['THEME_IMG']->value;?>
/wishlist.png" alt="Wishlist">
    <?php if ($_smarty_tpl->tpl_vars['wishCount']->value>0) {?>
	    <div class="countBlock floatWishAmount"><?php echo $_smarty_tpl->tpl_vars['wishCount']->value;?>
</div>
    <?php }?>
</a><?php }} ?>
