<?php /* Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:57
         compiled from "/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/catalog/blocks/category/category.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20896013456942ca9a1de37-97633533%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8e17e141a2b9fd3cebdb74253d6cfbf690b4447f' => 
    array (
      0 => '/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/catalog/blocks/category/category.tpl',
      1 => 1452548191,
      2 => 'rs',
    ),
  ),
  'nocache_hash' => '20896013456942ca9a1de37-97633533',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'dirlist' => 0,
    'dir' => 0,
    'pathids' => 0,
    'cnt' => 0,
    'columns' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_56942ca9aa45d7_82977928',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56942ca9aa45d7_82977928')) {function content_56942ca9aa45d7_82977928($_smarty_tpl) {?><?php if (!is_callable('smarty_function_adminUrl')) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/function.adminurl.php';
?>
<?php if ($_smarty_tpl->tpl_vars['dirlist']->value) {?>
    <ul class="category">
        <?php  $_smarty_tpl->tpl_vars['dir'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['dir']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['dirlist']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['dir']->key => $_smarty_tpl->tpl_vars['dir']->value) {
$_smarty_tpl->tpl_vars['dir']->_loop = true;
?>
        <li class="<?php echo $_smarty_tpl->tpl_vars['dir']->value['fields']['alias'];?>
<?php if (in_array($_smarty_tpl->tpl_vars['dir']->value['fields']['id'],$_smarty_tpl->tpl_vars['pathids']->value)) {?> act<?php }?>" <?php echo $_smarty_tpl->tpl_vars['dir']->value['fields']->getDebugAttributes();?>
><a href="<?php echo $_smarty_tpl->tpl_vars['dir']->value['fields']->getUrl();?>
"><?php echo $_smarty_tpl->tpl_vars['dir']->value['fields']['name'];?>
</a>
            <?php if (!empty($_smarty_tpl->tpl_vars['dir']->value['child'])) {?>
            <?php $_smarty_tpl->tpl_vars['cnt'] = new Smarty_variable(count($_smarty_tpl->tpl_vars['dir']->value['child']), null, 0);?>
            <?php if ($_smarty_tpl->tpl_vars['cnt']->value>9&&$_smarty_tpl->tpl_vars['cnt']->value<21) {?>
                <?php $_smarty_tpl->tpl_vars['columns'] = new Smarty_variable("twoColumn", null, 0);?>
            <?php } elseif ($_smarty_tpl->tpl_vars['cnt']->value>20) {?>
                <?php $_smarty_tpl->tpl_vars['columns'] = new Smarty_variable("threeColumn", null, 0);?>
            <?php }?>
            <ul <?php if ($_smarty_tpl->tpl_vars['columns']->value) {?>class="<?php echo $_smarty_tpl->tpl_vars['columns']->value;?>
"<?php }?>>
                <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['dir']->value['child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
                <li <?php if (in_array($_smarty_tpl->tpl_vars['item']->value['fields']['id'],$_smarty_tpl->tpl_vars['pathids']->value)) {?>class="act"<?php }?> <?php echo $_smarty_tpl->tpl_vars['item']->value['fields']->getDebugAttributes();?>
><a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['fields']->getUrl();?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['fields']['name'];?>
</a>
                <?php } ?>
            </ul>
            <?php }?>
        </li>
        <?php } ?>
    </ul>
<?php } else { ?>
    <?php ob_start();?><?php echo smarty_function_adminUrl(array('do'=>false,'mod_controller'=>"catalog-ctrl"),$_smarty_tpl);?>
<?php $_tmp3=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ("theme:default/block_stub.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('class'=>"blockCategory",'do'=>array(array('title'=>t("Добавьте категории товаров"),'href'=>$_tmp3))), 0);?>

<?php }?><?php }} ?>
