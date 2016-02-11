<?php /* Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:57
         compiled from "/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/menu/blocks/menu/footer_menu.tpl" */ ?>
<?php /*%%SmartyHeaderCode:170425610856942ca9c8fd88-12724591%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fb95e8ea2e2bd7beb9c8e8d5c27972ac88484b1c' => 
    array (
      0 => '/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/menu/blocks/menu/footer_menu.tpl',
      1 => 1452548197,
      2 => 'rs',
    ),
  ),
  'nocache_hash' => '170425610856942ca9c8fd88-12724591',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'items' => 0,
    'id' => 0,
    'menulist' => 0,
    'item' => 0,
    'this_controller' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_56942ca9cd72f3_80507342',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56942ca9cd72f3_80507342')) {function content_56942ca9cd72f3_80507342($_smarty_tpl) {?><?php if (!is_callable('smarty_function_static_call')) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/function.static_call.php';
if (!is_callable('smarty_modifier_replace')) include '/home/groupvm/www/site22/public_html/core/smarty/plugins/modifier.replace.php';
?><?php if ($_smarty_tpl->tpl_vars['items']->value) {?>
	<?php $_smarty_tpl->tpl_vars["id"] = new Smarty_variable($_smarty_tpl->tpl_vars['items']->value[0]['fields']['parent'], null, 0);?>
	<?php echo smarty_function_static_call(array('var'=>'menulist','callback'=>array('\Menu\Model\Api','staticSelectList')),$_smarty_tpl);?>

	<div class="title h4"><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['menulist']->value[$_smarty_tpl->tpl_vars['id']->value],'&nbsp;','');?>
</div>
	<div class="list h4">
		<ul>
		<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
			<li>
			<?php if ($_smarty_tpl->tpl_vars['item']->value['fields']['typelink']!='separator') {?><a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['fields']->getHref();?>
" <?php if ($_smarty_tpl->tpl_vars['item']->value['fields']['target_blank']) {?>target="_blank"<?php }?>><?php echo $_smarty_tpl->tpl_vars['item']->value['fields']['title'];?>
</a><?php } else { ?>&nbsp;<?php }?>
			</li>
		<?php } ?>
		</ul>
	</div>
<?php } else { ?>
	<?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['this_controller']->value->getSettingUrl();?>
<?php $_tmp4=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ("theme:default/block_stub.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('class'=>"noBack blockSmall blockLeft blockLogo",'do'=>array($_tmp4=>t("Настройте блок"))), 0);?>

<?php }?><?php }} ?>
