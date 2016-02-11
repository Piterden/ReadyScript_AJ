<?php /* Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:54
         compiled from "/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/table/table.tpl" */ ?>
<?php /*%%SmartyHeaderCode:133591459856942ca6e9f536-74598450%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '788f36f6f8f02dfe3d59eba8172c26537de4d202' => 
    array (
      0 => '/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/table/table.tpl',
      1 => 1452522616,
      2 => 'rs',
    ),
  ),
  'nocache_hash' => '133591459856942ca6e9f536-74598450',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'table' => 0,
    'item' => 0,
    'anyrows' => 0,
    'rows' => 0,
    'rownum' => 0,
    'anycell' => 0,
    'row' => 0,
    'cell' => 0,
    'count' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_56942ca7055059_45784802',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56942ca7055059_45784802')) {function content_56942ca7055059_45784802($_smarty_tpl) {?><?php if (!is_callable('smarty_function_addjs')) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/function.addjs.php';
?><?php echo smarty_function_addjs(array('file'=>"table.js"),$_smarty_tpl);?>


<table border="0" <?php echo $_smarty_tpl->tpl_vars['table']->value->getTableAttr();?>
>
    <thead>
    <tr>
        <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['col'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['table']->value->getColumns(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['col']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
            <?php if (!$_smarty_tpl->tpl_vars['item']->value->property['hidden']) {?>
            <th <?php echo $_smarty_tpl->tpl_vars['item']->value->getThAttr();?>
><?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['item']->value->getHeadTemplate(), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('cell'=>$_smarty_tpl->tpl_vars['item']->value), 0);?>
</th>
            <?php }?>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
        <tr class="empty no-over nodrop">
            <td colspan="<?php echo count($_smarty_tpl->tpl_vars['table']->value->getColumns());?>
"></td>
        </tr>
        
            <?php if (isset($_smarty_tpl->tpl_vars['anyrows']->value[0])&&empty($_smarty_tpl->tpl_vars['rows']->value)) {?>
            <tr <?php echo $_smarty_tpl->tpl_vars['table']->value->getAnyRowAttr($_smarty_tpl->tpl_vars['rownum']->value);?>
>
                <?php  $_smarty_tpl->tpl_vars['anycell'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['anycell']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['anyrows']->value[0]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['anycell']->key => $_smarty_tpl->tpl_vars['anycell']->value) {
$_smarty_tpl->tpl_vars['anycell']->_loop = true;
?>
                <td <?php echo $_smarty_tpl->tpl_vars['anycell']->value->getTdAttr();?>
>
                    <?php if (isset($_smarty_tpl->tpl_vars['anycell']->value->property['href'])) {?><a href="<?php echo $_smarty_tpl->tpl_vars['anycell']->value->property['href'];?>
" <?php echo $_smarty_tpl->tpl_vars['anycell']->value->getLinkAttr();?>
><?php }?><?php echo $_smarty_tpl->tpl_vars['anycell']->value->getValue();?>
<?php if (isset($_smarty_tpl->tpl_vars['anycell']->value->property['href'])) {?></a><?php }?>
                </td>
                <?php } ?>
            </tr>
            <?php }?>
        
        <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_smarty_tpl->tpl_vars['rownum'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['rows']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['rownum']->value = $_smarty_tpl->tpl_vars['row']->key;
?>
        <?php if (isset($_smarty_tpl->tpl_vars['anyrows']->value[$_smarty_tpl->tpl_vars['rownum']->value])) {?>
        <tr <?php echo $_smarty_tpl->tpl_vars['table']->value->getAnyRowAttr($_smarty_tpl->tpl_vars['rownum']->value);?>
>
            <?php  $_smarty_tpl->tpl_vars['anycell'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['anycell']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['anyrows']->value[$_smarty_tpl->tpl_vars['rownum']->value]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['anycell']->key => $_smarty_tpl->tpl_vars['anycell']->value) {
$_smarty_tpl->tpl_vars['anycell']->_loop = true;
?>
            <td <?php echo $_smarty_tpl->tpl_vars['anycell']->value->getTdAttr();?>
>
                <?php if (isset($_smarty_tpl->tpl_vars['anycell']->value->property['href'])) {?><a href="<?php echo $_smarty_tpl->tpl_vars['anycell']->value->property['href'];?>
" <?php echo $_smarty_tpl->tpl_vars['anycell']->value->getLinkAttr();?>
><?php }?><?php echo $_smarty_tpl->tpl_vars['anycell']->value->getValue();?>
<?php if (isset($_smarty_tpl->tpl_vars['anycell']->value->property['href'])) {?></a><?php }?>
            </td>
            <?php } ?>
        </tr>
        <?php }?>
        <tr <?php echo $_smarty_tpl->tpl_vars['table']->value->getRowAttr($_smarty_tpl->tpl_vars['rownum']->value);?>
>
            <?php  $_smarty_tpl->tpl_vars['cell'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cell']->_loop = false;
 $_smarty_tpl->tpl_vars['col'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['row']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cell']->key => $_smarty_tpl->tpl_vars['cell']->value) {
$_smarty_tpl->tpl_vars['cell']->_loop = true;
 $_smarty_tpl->tpl_vars['col']->value = $_smarty_tpl->tpl_vars['cell']->key;
?>
            <td <?php echo $_smarty_tpl->tpl_vars['cell']->value->getTdAttr();?>
>
                <?php if (isset($_smarty_tpl->tpl_vars['cell']->value->property['href'])) {?><a href="<?php echo $_smarty_tpl->tpl_vars['cell']->value->getHref();?>
" <?php echo $_smarty_tpl->tpl_vars['cell']->value->getLinkAttr();?>
><?php }?>
                    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['cell']->value->getBodyTemplate(), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('cell'=>$_smarty_tpl->tpl_vars['cell']->value), 0);?>
                
                <?php if (isset($_smarty_tpl->tpl_vars['cell']->value->property['href'])) {?></a><?php }?>
            </td>
            <?php } ?>
        </tr>
        <?php } ?>
        <?php if (empty($_smarty_tpl->tpl_vars['anyrows']->value)&&empty($_smarty_tpl->tpl_vars['rows']->value)) {?>
        <tr>
            <?php $_smarty_tpl->tpl_vars['count'] = new Smarty_variable(count($_smarty_tpl->tpl_vars['table']->value->getColumns()), null, 0);?>
            <td colspan="<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
" align="center"> Нет элементов
            </td>
        </tr>
        <?php }?>
    </tbody>
 </table>
<?php }} ?>
