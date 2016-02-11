<?php /* Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:55
         compiled from "/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/table/coltype/checkbox_head.tpl" */ ?>
<?php /*%%SmartyHeaderCode:172304653656942ca705ad96-40719814%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '37b0967ab41f6da59d76819cc4f24018bb773fd3' => 
    array (
      0 => '/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/table/coltype/checkbox_head.tpl',
      1 => 1452522616,
      2 => 'rs',
    ),
  ),
  'nocache_hash' => '172304653656942ca705ad96-40719814',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'cell' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_56942ca706f447_90620208',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56942ca706f447_90620208')) {function content_56942ca706f447_90620208($_smarty_tpl) {?><?php if (!is_callable('smarty_block_t')) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/block.t.php';
?><div class="chkhead-block">
    <input type="checkbox" title="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Выделить элементы на этой странице<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
" class="chk_head select-page" data-name="<?php echo $_smarty_tpl->tpl_vars['cell']->value->getName();?>
">
    <?php if ($_smarty_tpl->tpl_vars['cell']->value->property['showSelectAll']) {?>
    <div class="onover">
        <input type="checkbox" name="selectAll" value="on" class="select-all" title="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Выделить элементы на всех страницах<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
">
    </div>
    <?php }?>
</div><?php }} ?>
