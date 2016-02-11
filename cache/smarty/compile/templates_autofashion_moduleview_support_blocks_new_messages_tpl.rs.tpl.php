<?php 
/*
       * Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:57
       * compiled from "/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/support/blocks/new_messages.tpl"
       */
?>
<?php
 
/* %%SmartyHeaderCode:171696963456942ca99a8fd3-30131950%% */
if (! defined ( 'SMARTY_DIR' )) exit ( 'no direct access allowed' );
$_valid = $_smarty_tpl->decodeProperties ( array('file_dependency' => array('eba598d3bc5c37f5f956d6fcb0268f08c0ef605c' => array(0 => '/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/support/blocks/new_messages.tpl',1 => 1452548203,2 => 'rs' 
) 
),'nocache_hash' => '171696963456942ca99a8fd3-30131950','function' => array(),'variables' => array('new_count' => 0,'router' => 0 
),'has_nocache_code' => false,'version' => 'Smarty-3.1.18','unifunc' => 'content_56942ca99b7832_38144247' 
), false ); /* /%%SmartyHeaderCode%% */
?>
<?php if ($_valid && !is_callable('content_56942ca99b7832_38144247')) {function content_56942ca99b7832_38144247($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['new_count']->value>0) {?>
<div class="messages countBlock">
	<a
		href="<?php echo $_smarty_tpl->tpl_vars['router']->value->getUrl('support-front-support');?>
"
		class="mail"><?php echo $_smarty_tpl->tpl_vars['new_count']->value;?>
</a>
</div>
<?php }?><?php }} ?>
