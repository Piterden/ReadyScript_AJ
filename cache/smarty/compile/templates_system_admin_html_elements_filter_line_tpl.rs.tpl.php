<?php 
/*
       * Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:54
       * compiled from "/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/filter/line.tpl"
       */
?>
<?php
 
/* %%SmartyHeaderCode:171339256456942ca6e265c7-11081034%% */
if (! defined ( 'SMARTY_DIR' )) exit ( 'no direct access allowed' );
$_valid = $_smarty_tpl->decodeProperties ( array('file_dependency' => array('e5753a2558bee63cf252925be54d1145863be9a0' => array(0 => '/home/groupvm/www/site22/public_html/templates/system/admin/html_elements/filter/line.tpl',1 => 1452522616,2 => 'rs' 
) 
),'nocache_hash' => '171339256456942ca6e265c7-11081034','function' => array(),'variables' => array('fline' => 0,'item' => 0 
),'has_nocache_code' => false,'version' => 'Smarty-3.1.18','unifunc' => 'content_56942ca6e49b30_73232874' 
), false ); /* /%%SmartyHeaderCode%% */
?>
<?php


if ($_valid && ! is_callable ( 'content_56942ca6e49b30_73232874' )) {
	function content_56942ca6e49b30_73232874($_smarty_tpl)
	{
		?><?php

		
if (! is_callable ( 'smarty_block_t' )) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/block.t.php';
		?><fieldset>
    <?php if ($_smarty_tpl->tpl_vars['fline']->value->getOption('is_first')&&$_smarty_tpl->tpl_vars['fline']->value->getOption('has_second_containers')) {?>
        <span class="item"> <a class="expand"></a>
	</span>
    <?php }?>
    <?php
		
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable ();
		$_smarty_tpl->tpl_vars['item']->_loop = false;
		$_from = $_smarty_tpl->tpl_vars['fline']->value->getItems ();
		if (! is_array ( $_from ) && ! is_object ( $_from )) {
			settype ( $_from, 'array' );
		}
		foreach($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
			$_smarty_tpl->tpl_vars['item']->_loop = true;
			?>
        <span class="item">
            <?php echo $_smarty_tpl->tpl_vars['item']->value->getView();?>

        </span>
    <?php } ?>
    <?php if ($_smarty_tpl->tpl_vars['fline']->value->getOption('is_first')) {?>
        <span class="item">
		<button type="submit" class="find"
			title="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
применить фильтр<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
"></button>
	</span>
    <?php }?>
</fieldset><?php }} ?>
