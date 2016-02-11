<?php 
/*
       * Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:57
       * compiled from "/home/groupvm/www/site22/public_html/templates/autofashion/paginator.tpl"
       */
?>
<?php
 
/* %%SmartyHeaderCode:16187206156942ca965e110-96463772%% */
if (! defined ( 'SMARTY_DIR' )) exit ( 'no direct access allowed' );
$_valid = $_smarty_tpl->decodeProperties ( array('file_dependency' => array('a0c3b924f4037a27414945a5ce81c6cf9d141f2a' => array(0 => '/home/groupvm/www/site22/public_html/templates/autofashion/paginator.tpl',1 => 1452548186,2 => 'rs' 
) 
),'nocache_hash' => '16187206156942ca965e110-96463772','function' => array(),'variables' => array('paginator' => 0,'page' => 0 
),'has_nocache_code' => false,'version' => 'Smarty-3.1.18','unifunc' => 'content_56942ca96c5890_76108145' 
), false ); /* /%%SmartyHeaderCode%% */
?>
<?php if ($_valid && !is_callable('content_56942ca96c5890_76108145')) {function content_56942ca96c5890_76108145($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['paginator']->value->total_pages>1) {?>
<div class="row">
	<div class="paginator col-md-12 col-md-offset-6 text-center">
            <?php if ($_smarty_tpl->tpl_vars['paginator']->value->showFirst()) {?>
            <a
			href="<?php echo $_smarty_tpl->tpl_vars['paginator']->value->getPageHref(1);?>
"
			title="первая страница"><i class="fa fa-angle-double-left"></i></a>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['paginator']->value->page>1) {?>
            <a
			href="<?php echo $_smarty_tpl->tpl_vars['paginator']->value->getPageHref($_smarty_tpl->tpl_vars['paginator']->value->page-1);?>
"
			title="предыдущая страница"><i class="fa fa-angle-left"></i></a>
            <?php }?>
            <?php
			
$_smarty_tpl->tpl_vars['page'] = new Smarty_Variable ();
			$_smarty_tpl->tpl_vars['page']->_loop = false;
			$_from = $_smarty_tpl->tpl_vars['paginator']->value->getPageList ();
			if (! is_array ( $_from ) && ! is_object ( $_from )) {
				settype ( $_from, 'array' );
			}
			foreach($_from as $_smarty_tpl->tpl_vars['page']->key => $_smarty_tpl->tpl_vars['page']->value) {
				$_smarty_tpl->tpl_vars['page']->_loop = true;
				?>            
            <a
			href="<?php echo $_smarty_tpl->tpl_vars['page']->value['href'];?>
"
			<?php if ($_smarty_tpl->tpl_vars['page']->value['act']) {?>
			class="act" <?php }?>><?php if ($_smarty_tpl->tpl_vars['page']->value['class']=='left') {?>«<?php echo $_smarty_tpl->tpl_vars['page']->value['n'];?>
<?php } elseif ($_smarty_tpl->tpl_vars['page']->value['class']=='right') {?><?php echo $_smarty_tpl->tpl_vars['page']->value['n'];?>
»<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['page']->value['n'];?>
<?php }?></a>
            <?php } ?>
            <?php if ($_smarty_tpl->tpl_vars['paginator']->value->page<$_smarty_tpl->tpl_vars['paginator']->value->total_pages) {?>
            <a
			href="<?php echo $_smarty_tpl->tpl_vars['paginator']->value->getPageHref($_smarty_tpl->tpl_vars['paginator']->value->page+1);?>
"
			title="следующая страница"><i class="fa fa-angle-right"></i></a>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['paginator']->value->showLast()) {?>
            <a
			href="<?php echo $_smarty_tpl->tpl_vars['paginator']->value->getPageHref($_smarty_tpl->tpl_vars['paginator']->value->total_pages);?>
"
			title="последняя страница"><i class="fa fa-angle-double-right"></i></a>
            <?php }?>
        </div>
</div>
<?php }?><?php }} ?>
