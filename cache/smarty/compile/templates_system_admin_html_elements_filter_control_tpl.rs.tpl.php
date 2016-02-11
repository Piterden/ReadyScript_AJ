<?php /* Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:54
         compiled from "/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/filter/control.tpl" */ ?>
<?php /*%%SmartyHeaderCode:88156866156942ca6d681e6-27311581%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '908ef48d2746d8336a7bd5c237714efe6cdff2f2' => 
    array (
      0 => '/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/filter/control.tpl',
      1 => 1452522616,
      2 => 'rs',
    ),
  ),
  'nocache_hash' => '88156866156942ca6d681e6-27311581',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'fcontrol' => 0,
    'key' => 0,
    'val' => 0,
    'parts' => 0,
    'part' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_56942ca6e02dd7_50635194',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56942ca6e02dd7_50635194')) {function content_56942ca6e02dd7_50635194($_smarty_tpl) {?><?php if (!is_callable('smarty_function_addjs')) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/function.addjs.php';
if (!is_callable('smarty_block_t')) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/block.t.php';
?><?php echo smarty_function_addjs(array('file'=>"filter.js"),$_smarty_tpl);?>

<form method="GET" class="filter form-call-update"  id="<?php echo $_smarty_tpl->tpl_vars['fcontrol']->value->uniq;?>
">
    <?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['fcontrol']->value->getAddParam('hiddenfields'); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
    <input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['val']->value;?>
">
    <?php } ?>
    <a class="openfilter"><span><?php echo $_smarty_tpl->tpl_vars['fcontrol']->value->getCaption();?>
</span></a>
    <?php echo $_smarty_tpl->tpl_vars['fcontrol']->value->getContainerView();?>

</form>
<?php $_smarty_tpl->tpl_vars['parts'] = new Smarty_variable($_smarty_tpl->tpl_vars['fcontrol']->value->getParts(), null, 0);?>
<?php if (count($_smarty_tpl->tpl_vars['parts']->value)) {?>
<div class="filter-parts">
<?php if (count($_smarty_tpl->tpl_vars['parts']->value)>1) {?><span class="part clean_all"><a href="<?php echo $_smarty_tpl->tpl_vars['fcontrol']->value->getCleanFilterUrl();?>
" class="clean call-update" title="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Сбросить все фильтры<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
"></a></span><?php }?>
<?php  $_smarty_tpl->tpl_vars['part'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['part']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['parts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['part']->key => $_smarty_tpl->tpl_vars['part']->value) {
$_smarty_tpl->tpl_vars['part']->_loop = true;
?>
    <span class="part"><?php echo $_smarty_tpl->tpl_vars['part']->value['title'];?>
: <?php echo $_smarty_tpl->tpl_vars['part']->value['value'];?>
<a href="<?php echo $_smarty_tpl->tpl_vars['part']->value['href_clean'];?>
" class="clean call-update" title="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Сбросить этот фильтр<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
"></a></span>
<?php } ?>
</div>
<?php }?><?php }} ?>
