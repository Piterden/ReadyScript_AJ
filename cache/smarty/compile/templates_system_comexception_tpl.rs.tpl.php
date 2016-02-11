<?php /* Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:57
         compiled from "/home/groupvm/www/site22/public_html/templates/system/comexception.tpl" */ ?>
<?php /*%%SmartyHeaderCode:116585982156942ca9c43005-25915567%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '613bb3eb8b566c8e43cf3b6e36765929ee00e306' => 
    array (
      0 => '/home/groupvm/www/site22/public_html/templates/system/comexception.tpl',
      1 => 1452522616,
      2 => 'rs',
    ),
  ),
  'nocache_hash' => '116585982156942ca9c43005-25915567',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'Setup' => 0,
    'exception' => 0,
    'controllerName' => 0,
    'uniq' => 0,
    'type' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_56942ca9c769b3_95255454',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56942ca9c769b3_95255454')) {function content_56942ca9c769b3_95255454($_smarty_tpl) {?><?php if (!is_callable('smarty_function_addcss')) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/function.addcss.php';
?><?php if ($_smarty_tpl->tpl_vars['Setup']->value['DETAILED_EXCEPTION']) {?>
    <?php echo smarty_function_addcss(array('file'=>"user/errorblocks.css",'basepath'=>"common"),$_smarty_tpl);?>

    <div class="comError">
        <div>
            <strong><?php echo $_smarty_tpl->tpl_vars['exception']->value->getMessage();?>
</strong><br>
            Ошибка в контроллере: <?php echo $_smarty_tpl->tpl_vars['controllerName']->value;?>
<br>
            <a href="JavaScript:;" onclick="document.getElementById('<?php echo $_smarty_tpl->tpl_vars['uniq']->value;?>
').style.display='block'; this.style.display = 'none'">подробнее</a>
            <div class="more" id="<?php echo $_smarty_tpl->tpl_vars['uniq']->value;?>
">
                Код ошибки:<?php echo $_smarty_tpl->tpl_vars['exception']->value->getCode();?>
<br>
                Тип ошибки:<?php echo $_smarty_tpl->tpl_vars['type']->value;?>
<br>
                Файл:<?php echo $_smarty_tpl->tpl_vars['exception']->value->getFile();?>
<br>
                Строка:<?php echo $_smarty_tpl->tpl_vars['exception']->value->getLine();?>
<br>
                Стек вызова: <pre><?php echo $_smarty_tpl->tpl_vars['exception']->value->getTraceAsString();?>
</pre><br>          
            </div>
                    
        </div>
    </div>
<?php } else { ?>
    <!-- Исключение в модуле <?php echo $_smarty_tpl->tpl_vars['controllerName']->value;?>
 -->
<?php }?><?php }} ?>
