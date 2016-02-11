<?php 
/*
       * Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:57
       * compiled from "/home/groupvm/www/site22/public_html/templates/system/debug/top.tpl"
       */
?>
<?php
 
/* %%SmartyHeaderCode:72351331456942ca9d52c26-65134098%% */
if (! defined ( 'SMARTY_DIR' )) exit ( 'no direct access allowed' );
$_valid = $_smarty_tpl->decodeProperties ( array('file_dependency' => array('a6125710dc01eeb8b30db12383b4ce69bda01508' => array(0 => '/home/groupvm/www/site22/public_html/templates/system/debug/top.tpl',1 => 1452522616,2 => 'rs' 
) 
),'nocache_hash' => '72351331456942ca9d52c26-65134098','function' => array(),'variables' => array('router' => 0,'this_controller' => 0,'sites' => 0,'site' => 0,'current_user' => 0,'notice' => 0,'result_html' => 0 
),'has_nocache_code' => false,'version' => 'Smarty-3.1.18','unifunc' => 'content_56942ca9e49069_44754540' 
), false ); /* /%%SmartyHeaderCode%% */
?>
<?php


if ($_valid && ! is_callable ( 'content_56942ca9e49069_44754540' )) {
	function content_56942ca9e49069_44754540($_smarty_tpl)
	{
		?><?php

		
if (! is_callable ( 'smarty_function_addjs' )) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/function.addjs.php';
		if (! is_callable ( 'smarty_function_addcss' )) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/function.addcss.php';
		if (! is_callable ( 'smarty_function_adminUrl' )) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/function.adminurl.php';
		if (! is_callable ( 'smarty_block_t' )) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/block.t.php';
		if (! is_callable ( 'smarty_function_modulegetvars' )) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/function.modulegetvars.php';
		if (! is_callable ( 'smarty_modifier_teaser' )) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/modifier.teaser.php';
		?><?php

		
echo smarty_function_addjs ( array('file' => "jquery.min.js",'name' => "jquery",'basepath' => "common",'header' => true 
		), $_smarty_tpl );
		?>

<?php echo smarty_function_addjs(array('file'=>"ui.jquery.all.min.js",'basepath'=>"common"),$_smarty_tpl);?>

<?php echo smarty_function_addjs(array('file'=>"jquery.form.js",'basepath'=>"common"),$_smarty_tpl);?>

<?php echo smarty_function_addjs(array('file'=>"jquery.datetimeaddon.min.js",'basepath'=>"common"),$_smarty_tpl);?>

<?php echo smarty_function_addjs(array('file'=>"lab.min.js",'basepath'=>"common"),$_smarty_tpl);?>

<?php echo smarty_function_addjs(array('file'=>"jquery.tooltip.js",'basepath'=>"common"),$_smarty_tpl);?>

<?php echo smarty_function_addjs(array('file'=>"admindebug.js",'basepath'=>"common"),$_smarty_tpl);?>

<?php echo smarty_function_addjs(array('file'=>"debug.js",'basepath'=>"common"),$_smarty_tpl);?>

<?php echo smarty_function_addjs(array('file'=>"updatable.js",'basepath'=>"common"),$_smarty_tpl);?>

<?php echo smarty_function_addjs(array('file'=>"coreobject.js",'basepath'=>"common"),$_smarty_tpl);?>

<?php echo smarty_function_addjs(array('file'=>"tabs.js",'basepath'=>"common"),$_smarty_tpl);?>

<?php echo smarty_function_addjs(array('file'=>"jquery.rsframework.js",'basepath'=>"common"),$_smarty_tpl);?>

<?php echo smarty_function_addjs(array('file'=>"jquery.cookie.js",'basepath'=>"common"),$_smarty_tpl);?>

<?php echo smarty_function_addjs(array('file'=>"jstour/jquery.tour.js",'basepath'=>"common"),$_smarty_tpl);?>


<?php echo smarty_function_addcss(array('file'=>"admin/readyscript.ui/jquery-ui.css",'basepath'=>"common"),$_smarty_tpl);?>

<?php echo smarty_function_addcss(array('file'=>"common/debug.css",'basepath'=>"common"),$_smarty_tpl);?>

<?php echo smarty_function_addcss(array('file'=>"common/debug.tooltip.css",'basepath'=>"common"),$_smarty_tpl);?>

<?php echo smarty_function_addcss(array('file'=>"common/animate.css",'basepath'=>"common"),$_smarty_tpl);?>

<?php echo smarty_function_addcss(array('file'=>"common/tour.css",'basepath'=>"common"),$_smarty_tpl);?>

<?php echo smarty_function_addcss(array('file'=>"admin/debugstyle_reset.css",'basepath'=>"common"),$_smarty_tpl);?>

<?php echo smarty_function_addcss(array('file'=>"admin/debugstyle.css?v=7",'basepath'=>"common"),$_smarty_tpl);?>

<?php echo smarty_function_addcss(array('file'=>"admin/framework.css",'basepath'=>"common"),$_smarty_tpl);?>

<div id="debug-top-block" class="admin-style">
	<div class="menuline">
		<div class="viewport relative">
			<a
				href="<?php echo smarty_function_adminUrl(array('mod_controller'=>false,'do'=>false),$_smarty_tpl);?>
"
				class="brand"></a>
			<div class="debug-mode-switcher">
				<div
					data-url="<?php echo $_smarty_tpl->tpl_vars['router']->value->getUrl('main.admin',array('Act'=>'ajaxToggleDebug'));?>
"
					class="rs-switch <?php if ($_smarty_tpl->tpl_vars['this_controller']->value->getDebugGroup()) {?>on<?php } else { ?>off<?php }?> crud-get">
					<div class="rs-border">
						<div class="rs-back"></div>
						<div class="rs-handle"></div>
					</div>
				</div>
				<p class="debugmode-text"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
режим отладки<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</p>
			</div>

			<div class="action-zone">
                <?php echo smarty_function_modulegetvars(array('name'=>"\Site\Controller\Admin\BlockSelectSite",'var'=>"sites"),$_smarty_tpl);?>

                <div class="panel-menu">
					<div class="current"><?php echo smarty_modifier_teaser($_smarty_tpl->tpl_vars['sites']->value['current']['title'],"27");?>
</div>
					<ul class="drop-down">
                        <?php
		
$_smarty_tpl->tpl_vars['site'] = new Smarty_Variable ();
		$_smarty_tpl->tpl_vars['site']->_loop = false;
		$_from = $_smarty_tpl->tpl_vars['sites']->value['sites'];
		if (! is_array ( $_from ) && ! is_object ( $_from )) {
			settype ( $_from, 'array' );
		}
		foreach($_from as $_smarty_tpl->tpl_vars['site']->key => $_smarty_tpl->tpl_vars['site']->value) {
			$_smarty_tpl->tpl_vars['site']->_loop = true;
			?>
                            <?php if ($_smarty_tpl->tpl_vars['site']->value['id']!=$_smarty_tpl->tpl_vars['sites']->value['current']['id']) {?>
                            <li><a
							href="<?php echo $_smarty_tpl->tpl_vars['router']->value->getUrl('main.admin',array('Act'=>'changeSite','site'=>$_smarty_tpl->tpl_vars['site']->value['id']));?>
"><?php echo $_smarty_tpl->tpl_vars['site']->value['title'];?>
</a></li>
                            <?php }?>
                        <?php } ?>
                        <li class="user-section"><a
							href="<?php echo smarty_function_adminUrl(array('mod_controller'=>"users-ctrl",'do'=>"edit",'id'=>$_smarty_tpl->tpl_vars['current_user']->value['id']),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['current_user']->value->getFio();?>
</a> <a
							href="<?php echo $_smarty_tpl->tpl_vars['router']->value->getUrl('main.admin',array('Act'=>'logout'));?>
"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Выход<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</a></li>
					</ul>
				</div>
                <?php if (!isset($_smarty_tpl->tpl_vars['notice'])) $_smarty_tpl->tpl_vars['notice'] = new Smarty_Variable(null);if ($_smarty_tpl->tpl_vars['notice']->value = __GET_ADMIN_NOTICE()) {?>
                    <a
					href="<?php echo smarty_function_adminUrl(array('mod_controller'=>"main-license",'do'=>false),$_smarty_tpl);?>
"
					class="action license-notice-icon"
					title="<?php echo $_smarty_tpl->tpl_vars['notice']->value;?>
"></a>
                <?php }?>                                    
                <a
					href="<?php echo $_smarty_tpl->tpl_vars['router']->value->getUrl('main.admin',array("Act"=>"cleanCache"));?>
"
					class="action clean-cache crud-get"
					title="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Очистить кэш<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
"></a> <a
					href="<?php echo $_smarty_tpl->tpl_vars['router']->value->getUrl('main.admin');?>
"
					class="action to-admin wide-element"
					title="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Перейти в админ. панель<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
"><span class="wide-text"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Перейти в админ. панель<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span></a> <a class="action start-tour wide-element"
					data-tour-id="welcome"
					title="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Обучение<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
"><span class="wide-text"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Обучение<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</span></a>
			</div>
		</div>
	</div>
</div>
<?php echo $_smarty_tpl->tpl_vars['result_html']->value;?>
<?php }} ?>
