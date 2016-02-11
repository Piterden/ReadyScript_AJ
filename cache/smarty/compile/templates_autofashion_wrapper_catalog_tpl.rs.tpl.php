<?php /* Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:57
         compiled from "/home/groupvm/www/site22/public_html/templates/autofashion/wrapper_catalog.tpl" */ ?>
<?php /*%%SmartyHeaderCode:28474645256942ca9761f18-09432350%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '647462544a044c5300745f6f8470458032f34c12' => 
    array (
      0 => '/home/groupvm/www/site22/public_html/templates/autofashion/wrapper_catalog.tpl',
      1 => 1452548187,
      2 => 'rs',
    ),
    '5ccba9d1b2f55cf51d285ef50a07dff33eb9f559' => 
    array (
      0 => '/home/groupvm/www/site22/public_html/templates/autofashion/wrapper.tpl',
      1 => 1452548186,
      2 => 'rs',
    ),
  ),
  'nocache_hash' => '28474645256942ca9761f18-09432350',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'THEME_IMG' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_56942ca981ad85_97484446',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56942ca981ad85_97484446')) {function content_56942ca981ad85_97484446($_smarty_tpl) {?><?php if (!is_callable('smarty_function_moduleinsert')) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/function.moduleinsert.php';
?><div class="bodyWrap">
    <header class="container">
        <div class="row">
            <div class="logoBlock col-md-8">
                
                <?php echo smarty_function_moduleinsert(array('name'=>"\Main\Controller\Block\Logo",'width'=>"335",'height'=>"60",'indexTemplate'=>"blocks/logo/logo.tpl"),$_smarty_tpl,'/home/groupvm/www/site22/public_html/templates/autofashion/wrapper.tpl');?>

            </div>
            <div class="searchFormWrap col-md-8 text-center">
                <?php echo smarty_function_moduleinsert(array('name'=>"\Catalog\Controller\Block\SearchLine",'hideAutoComplete'=>0,'searchLimit'=>5),$_smarty_tpl,'/home/groupvm/www/site22/public_html/templates/autofashion/wrapper.tpl');?>

            </div>
            <div class="phoneBlock col-md-8">
                <a href="tel:+78123859787" class="phone"><img src="<?php echo $_smarty_tpl->tpl_vars['THEME_IMG']->value;?>
/header-icon-phone.png" alt="Позвонить" title="Позвонить" class="phoneIcon"><span class="region">Санкт-Петербург</span><br><span class="number">+7 (812) 385-97-87</span></a>
            </div>
        </div>
    </header>
    <nav class="topMenuNav">
        <div class="container">
            <div class="row">
                <div class="col-sm-24">
                    <?php echo smarty_function_moduleinsert(array('name'=>"\Menu\Controller\Block\Menu",'root'=>"main-menu",'indexTemplate'=>"blocks/menu/hor_menu.tpl"),$_smarty_tpl,'/home/groupvm/www/site22/public_html/templates/autofashion/wrapper.tpl');?>

                    <div class="headerIconsBlock">
                        <div class="userAuthBlock">
                            <?php echo smarty_function_moduleinsert(array('name'=>"\Users\Controller\Block\Authblock"),$_smarty_tpl,'/home/groupvm/www/site22/public_html/templates/autofashion/wrapper.tpl');?>

                        </div>
                        <div class="cartBlock fixedCart">
                            <?php if (\RS\Module\Manager::staticModuleExists('shop')) {?>
                                
                                <?php echo smarty_function_moduleinsert(array('name'=>"\Shop\Controller\Block\Cart"),$_smarty_tpl,'/home/groupvm/www/site22/public_html/templates/autofashion/wrapper.tpl');?>

                            <?php }?>
                        </div>
                        <div class="wishlistBlock">
                            <?php if (\RS\Module\Manager::staticModuleExists('wishlist')) {?>
                                
                                <?php echo smarty_function_moduleinsert(array('name'=>"\Wishlist\Controller\Block\WishesCount"),$_smarty_tpl,'/home/groupvm/www/site22/public_html/templates/autofashion/wrapper.tpl');?>

                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <script>
        jQuery(document).ready(function($) {
            var $topMenuNav = $('nav.topMenuNav');
            var $catalogLine = $('.catalogLine');
            $(window).on('scroll', function() {
                if ($(this).scrollTop() >= 100) {
                    $topMenuNav.css('position', 'fixed');
                    $catalogLine.css('margin-top', '60px');
                } else {
                    $topMenuNav.css('position', 'relative');
                    $catalogLine.css('margin-top', '0');
                }
            });
        });
    </script>
    <div class="container catalogLine">
        <div class="row">
            <div class="col-md-12">
                <div class="categoriesList">
                    
                    <?php echo smarty_function_moduleinsert(array('name'=>"\Catalog\Controller\Block\Category",'indexTemplate'=>"blocks/category/category.tpl"),$_smarty_tpl,'/home/groupvm/www/site22/public_html/templates/autofashion/wrapper.tpl');?>

                </div>
            </div>
            <div class="col-md-12">
                <?php echo smarty_function_moduleinsert(array('name'=>"\Main\Controller\Block\Breadcrumbs"),$_smarty_tpl,'/home/groupvm/www/site22/public_html/templates/autofashion/wrapper.tpl');?>

            </div>
        </div>
    </div>
    <div class="viewport mainContent">
        
        
        
<?php if (($_smarty_tpl->tpl_vars['category']->value['level']!=0||$_smarty_tpl->tpl_vars['category']->value['is_spec_dir']=='Y')&&count($_smarty_tpl->tpl_vars['list']->value)) {?>
	<div class="container">
		<div class="row productListTitle">
			<div class="col-md-24 text-center mainTitle">
				<h1><?php echo $_smarty_tpl->tpl_vars['category']->value['name'];?>
</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div id="filtersBlock">
					<?php echo smarty_function_moduleinsert(array('name'=>"\Catalog\Controller\Block\SideFilters"),$_smarty_tpl,'/home/groupvm/www/site22/public_html/templates/autofashion/wrapper_catalog.tpl');?>

				</div>	
			</div>
			<div class="col-md-18">
				<?php echo $_smarty_tpl->tpl_vars['app']->value->blocks->getMainContent();?>

			</div>
		</div>
	</div>
<?php } else { ?>
	<?php echo $_smarty_tpl->tpl_vars['app']->value->blocks->getMainContent();?>

<?php }?>

        
        <div class="preFooter">
            <div class="container">
                <div class="row">
                    <div class="titleWrap col-md-24 text-center">
                        <h3>Мы принимаем</h3>
                    </div>
                </div>
                <div class="row payMethods">
                    <div class="text-center col-md-4 col-md-offset-2">
                        <img src="<?php echo $_smarty_tpl->tpl_vars['THEME_IMG']->value;?>
/big-icon-pay.png" alt="Способ оплаты">
                    </div>
                    <div class="text-center col-md-4">
                        <img src="<?php echo $_smarty_tpl->tpl_vars['THEME_IMG']->value;?>
/big-icon-pay.png" alt="Способ оплаты">
                    </div>
                    <div class="text-center col-md-4">
                        <img src="<?php echo $_smarty_tpl->tpl_vars['THEME_IMG']->value;?>
/big-icon-pay.png" alt="Способ оплаты">
                    </div>
                    <div class="text-center col-md-4">
                        <img src="<?php echo $_smarty_tpl->tpl_vars['THEME_IMG']->value;?>
/big-icon-pay.png" alt="Способ оплаты">
                    </div>
                    <div class="text-center col-md-4">
                        <img src="<?php echo $_smarty_tpl->tpl_vars['THEME_IMG']->value;?>
/big-icon-pay.png" alt="Способ оплаты">
                    </div>
                </div>
                <div class="row banner">
                    <div class="col-md-24 text-center">
                        <img src="" alt="Banner" width="728" height="90" style="border:1px solid grey">
                    </div>
                </div>
            </div>
        </div>
        <footer>
            <div class="container">
                <div class="row">
                    <div class="item footerFriendLinks col-md-4">
                        <div class="link">
                            <a href="auto-jack.com"><img src="<?php echo $_smarty_tpl->tpl_vars['THEME_IMG']->value;?>
/footer-ajlogo.png" alt="AutoJack"></a>
                        </div>
                        <div class="link">
                            <a href="limo-lady.com"><img src="<?php echo $_smarty_tpl->tpl_vars['THEME_IMG']->value;?>
/footer-lllogo.png" alt="LimoLady"></a>
                        </div>
                    </div>
                    <div class="item col-md-4">
                        <?php echo smarty_function_moduleinsert(array('name'=>"\Menu\Controller\Block\Menu",'root'=>"info",'indexTemplate'=>"blocks/menu/footer_menu.tpl"),$_smarty_tpl,'/home/groupvm/www/site22/public_html/templates/autofashion/wrapper.tpl');?>

                    </div>
                    <div class="item col-md-4">
                        <?php echo smarty_function_moduleinsert(array('name'=>"\Menu\Controller\Block\Menu",'root'=>"pokupatelyam",'indexTemplate'=>"blocks/menu/footer_menu.tpl"),$_smarty_tpl,'/home/groupvm/www/site22/public_html/templates/autofashion/wrapper.tpl');?>

                    </div>
                    <div class="item col-md-4">
                        <?php echo smarty_function_moduleinsert(array('name'=>"\Menu\Controller\Block\Menu",'root'=>"muzhskie-kurtki",'indexTemplate'=>"blocks/menu/footer_menu.tpl"),$_smarty_tpl,'/home/groupvm/www/site22/public_html/templates/autofashion/wrapper.tpl');?>

                    </div>
                    <div class="item col-md-4">
                        <?php echo smarty_function_moduleinsert(array('name'=>"\Menu\Controller\Block\Menu",'root'=>"zhenskie-kurtki",'indexTemplate'=>"blocks/menu/footer_menu.tpl"),$_smarty_tpl,'/home/groupvm/www/site22/public_html/templates/autofashion/wrapper.tpl');?>

                    </div>
                    <div class="item footerAddress col-md-4">
                        <div class="phones">
                            <div class="label">По России бесплатно:</div>
                            <div class="phone"><a href="tel:+78004409731">+7 (800) 440-97-31</a></div>
                            <div class="label">В Санкт-Петербурге:</div>
                            <div class="phone"><a href="tel:+78123229731">+7 (812) 322-97-31</a></div>
                        </div>
                        <div class="emails">
                            <div class="label">Общие вопросы:</div>
                            <div class="email"><a href="mailto:info@auto-jack.com">info@auto-jack.com</a></div>
                            <div class="label">Отдел маркетинга:</div>
                            <div class="email"><a href="mailto:marketing@auto-jack.com">marketing@auto-jack.com</a></div>
                        </div>
                        <div class="social">
                            <a href="http://vk.com/autojack" target="_blank">
                                <img src="<?php echo $_smarty_tpl->tpl_vars['THEME_IMG']->value;?>
/footer-icon-vkontakte.png" height="33" width="33" alt="Группа AutoJack и LimoLady в Vkontakte">
                                <img class="hover" src="<?php echo $_smarty_tpl->tpl_vars['THEME_IMG']->value;?>
/footer-icon-vkontakte-hover.png" height="33" width="33" alt="Группа AutoJack и LimoLady в Vkontakte">
                            </a>
                            <a href="http://www.facebook.com/autojack.official" target="_blank">
                                <img src="<?php echo $_smarty_tpl->tpl_vars['THEME_IMG']->value;?>
/footer-icon-facebook.png" height="33" width="33" alt="Группа AutoJack и LimoLady в FaceBook">
                                <img class="hover" src="<?php echo $_smarty_tpl->tpl_vars['THEME_IMG']->value;?>
/footer-icon-facebook-hover.png" height="33" width="33" alt="Группа AutoJack и LimoLady в FaceBook">
                            </a>
                            <a href="http://www.youtube.com/user/autojackandlimolady" target="_blank">
                                <img src="<?php echo $_smarty_tpl->tpl_vars['THEME_IMG']->value;?>
/footer-icon-youtube.png" height="33" width="33" alt="Канал AutoJack и LimoLady на YouTube">
                                <img class="hover" src="<?php echo $_smarty_tpl->tpl_vars['THEME_IMG']->value;?>
/footer-icon-youtube-hover.png" height="33" width="33" alt="Канал AutoJack и LimoLady на YouTube">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row copyright">
                    <div class="col-md-24 text-center">
                        Фирменный магазин одежды от&nbsp;производителя. &copy;&nbsp;ООО &laquo;Винтал&raquo; 2015
                    </div>
                </div>
            </div>
        </footer>
    </div>
    
</div> <!-- .bodyWrap --><?php }} ?>
