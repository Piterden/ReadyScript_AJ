<?php 
/*
       * Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:57
       * compiled from "/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/users/blocks/authblock/authblock.tpl"
       */
?>
<?php
 
/* %%SmartyHeaderCode:10425030456942ca991cfb4-94831127%% */
if (! defined ( 'SMARTY_DIR' )) exit ( 'no direct access allowed' );
$_valid = $_smarty_tpl->decodeProperties ( array('file_dependency' => array('58a28fa26ca6a654f6cdd290e94ef25caa6b2281' => array(0 => '/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/users/blocks/authblock/authblock.tpl',1 => 1452548205,2 => 'rs' 
) 
),'nocache_hash' => '10425030456942ca991cfb4-94831127','function' => array(),'variables' => array('is_auth' => 0,'router' => 0,'THEME_IMG' => 0,'current_user' => 0,'is_partner' => 0,'url' => 0,'referer' => 0 
),'has_nocache_code' => false,'version' => 'Smarty-3.1.18','unifunc' => 'content_56942ca9995c21_34593952' 
), false ); /* /%%SmartyHeaderCode%% */
?>
<?php


if ($_valid && ! is_callable ( 'content_56942ca9995c21_34593952' )) {
	function content_56942ca9995c21_34593952($_smarty_tpl)
	{
		?><?php

		
if (! is_callable ( 'smarty_function_static_call' )) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/function.static_call.php';
		if (! is_callable ( 'smarty_function_moduleinsert' )) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/function.moduleinsert.php';
		?><?php

		
if ($_smarty_tpl->tpl_vars['is_auth']->value) {
			?>
<a
	href="<?php echo $_smarty_tpl->tpl_vars['router']->value->getUrl('users-front-profile');?>
"
	class="userAuthButton"> <img
	src="<?php echo $_smarty_tpl->tpl_vars['THEME_IMG']->value;?>
/user.png"
	alt="User">
</a>
<div class="authorized">
	<div class="top">
		<div class="my">
			<div class="dropblock">
				<div class="user">
					<a
						href="<?php echo $_smarty_tpl->tpl_vars['router']->value->getUrl('users-front-profile');?>
"
						class="username"><?php echo $_smarty_tpl->tpl_vars['current_user']->value['name'];?>
 <?php echo $_smarty_tpl->tpl_vars['current_user']->value['surname'];?>
</a>
				</div>
				<ul class="dropdown">
					<li><a
						href="<?php echo $_smarty_tpl->tpl_vars['router']->value->getUrl('users-front-profile');?>
">Профиль</a></li>
					<li><a
						href="<?php echo $_smarty_tpl->tpl_vars['router']->value->getUrl('shop-front-myorders');?>
">Мои заказы</a></li>
					<li><a
						href="<?php echo $_smarty_tpl->tpl_vars['router']->value->getUrl('support-front-support');?>
">Сообщения</a></li>
					<li class="exitWrap"><a
						href="<?php echo $_smarty_tpl->tpl_vars['router']->value->getUrl('users-front-auth',array('Act'=>'logout'));?>
"
						class="exit">Выход</a></li>
                    <?php if (\RS\Module\Manager::staticModuleExists('partnership')) {?>
                        <?php echo smarty_function_static_call(array('var'=>"is_partner",'callback'=>array('Partnership\Model\Api','isUserPartner'),'params'=>$_smarty_tpl->tpl_vars['current_user']->value['id']),$_smarty_tpl);?>

                        <?php if ($_smarty_tpl->tpl_vars['is_partner']->value) {?>
                        <li><a
						href="<?php echo $_smarty_tpl->tpl_vars['router']->value->getUrl('partnership-front-profile');?>
">профиль партнера</a></li>
                        <?php }?>
                    <?php }?>
                </ul>
			</div>
		</div>
	</div>

    <?php if (\RS\Module\Manager::staticModuleExists('support')) {?>
        <?php echo smarty_function_moduleinsert(array('name'=>"\Support\Controller\Block\NewMessages"),$_smarty_tpl,'/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/users/blocks/authblock/authblock.tpl');?>

    <?php }?>

</div>
<?php } else { ?>
<div class="auth">
    <?php $_smarty_tpl->tpl_vars['referer'] = new Smarty_variable(urlencode($_smarty_tpl->tpl_vars['url']->value->server('REQUEST_URI')), null, 0);?>
    <a
		href="<?php echo $_smarty_tpl->tpl_vars['router']->value->getUrl('users-front-auth',array('referer'=>$_smarty_tpl->tpl_vars['referer']->value));?>
"
		class="first inDialog"><span>Войти</span></a> <a
		href="<?php echo $_smarty_tpl->tpl_vars['router']->value->getUrl('users-front-register',array('referer'=>$_smarty_tpl->tpl_vars['referer']->value));?>
"
		class="inDialog"><span>Зарегистрироваться</span></a>
</div>
<?php }?>
<?php }} ?>
