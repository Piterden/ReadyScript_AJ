<?php 
/*
       * Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:57
       * compiled from "/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/catalog/blocks/searchline/searchform.tpl"
       */
?>
<?php
 
/* %%SmartyHeaderCode:212415880556942ca986ac53-68165783%% */
if (! defined ( 'SMARTY_DIR' )) exit ( 'no direct access allowed' );
$_valid = $_smarty_tpl->decodeProperties ( array('file_dependency' => array('08acda13e69f95f2bd8a3e07d8012d57e142b5b4' => array(0 => '/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/catalog/blocks/searchline/searchform.tpl',1 => 1452548193,2 => 'rs' 
) 
),'nocache_hash' => '212415880556942ca986ac53-68165783','function' => array(),'variables' => array('router' => 0,'param' => 0,'query' => 0,'_block_id' => 0 
),'has_nocache_code' => false,'version' => 'Smarty-3.1.18','unifunc' => 'content_56942ca9885bf4_54363885' 
), false ); /* /%%SmartyHeaderCode%% */
?>
<?php if ($_valid && !is_callable('content_56942ca9885bf4_54363885')) {function content_56942ca9885bf4_54363885($_smarty_tpl) {?><form
	method="GET"
	action="<?php echo $_smarty_tpl->tpl_vars['router']->value->getUrl('catalog-front-listproducts',array());?>
">
	<div class="searchLine">
		<div class="queryWrap" id="queryBox">
			<input type="text"
				class="query<?php if (!$_smarty_tpl->tpl_vars['param']->value['hideAutoComplete']) {?> autocomplete<?php }?>"
				data-deftext="поиск в каталоге" name="query"
				value="<?php echo $_smarty_tpl->tpl_vars['query']->value;?>
"
				autocomplete="off"
				data-source-url="<?php echo $_smarty_tpl->tpl_vars['router']->value->getUrl('catalog-block-searchline',array('sldo'=>'ajaxSearchItems','_block_id'=>$_smarty_tpl->tpl_vars['_block_id']->value));?>
"
				placeholder="Поиск в каталоге">
		</div>
		<button type="submit" class="find">
			<i class="fa fa-search"></i>
		</button>
	</div>
</form><?php }} ?>
