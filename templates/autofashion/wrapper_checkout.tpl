{addjs file="order.js"}
<div class="bodyWrap checkout">
    <header class="container">
        <div class="row">
            <div class="logoBlock col-md-8">
                {* Вставляем блок "логитип" *}
                {moduleinsert name="\Main\Controller\Block\Logo" width="335" height="60" indexTemplate="blocks/logo/logo.tpl"}
            </div>
            <div class="searchFormWrap col-md-8 text-center">
                {moduleinsert name="\Catalog\Controller\Block\SearchLine" hideAutoComplete=0 searchLimit=5}
            </div>
            <div class="phoneBlock col-md-8">
                <a href="tel:+78123859787" class="phone"><img src="{$THEME_IMG}/header-icon-phone.png" alt="Позвонить" title="Позвонить" class="phoneIcon"><span class="region">Санкт-Петербург</span><br><span class="number">+7 (812) 385-97-87</span></a>
            </div>
        </div>
    </header>
    <nav class="topMenuNav">
        <div class="container">
            <div class="row">
                <div class="col-sm-24">
                    {moduleinsert name="\Menu\Controller\Block\Menu" root="main-menu" indexTemplate="blocks/menu/hor_menu.tpl"}
                    <div class="headerIconsBlock">
                        <div class="userAuthBlock">
                            {moduleinsert name="\Users\Controller\Block\Authblock"}
                        </div>
                        <div class="cartBlock fixedCart">
                            {if ModuleManager::staticModuleExists('shop')}
                                {* Корзина *}
                                {moduleinsert name="\Shop\Controller\Block\Cart"}
                            {/if}
                        </div>
                        <div class="wishlistBlock">
                            {if ModuleManager::staticModuleExists('wishlist')}
                                {* Список желаний *}
                                {moduleinsert name="\Wishlist\Controller\Block\WishesCount"}
                            {/if}
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
                    {* Вставляем блок "категории товаров" *}
                    {moduleinsert name="\Catalog\Controller\Block\Category" indexTemplate="blocks/category/category.tpl"}
                </div>
            </div>
        </div>
    </div>
    <div class="viewport mainContent">
        <div class="container">
            <div class="row">
                <div class="mainTitle title text-center">
                    <h1><span class="whiteBack">Оформление заказа</span></h1>
                </div>
                <div class="row checkoutStepsWrap">
                    <div class="col-md-24 text-center">
                        {* Шаги оформления заказа *}
                        {moduleinsert name="\Shop\Controller\Block\CheckoutStep"}
                    </div>
                </div>
            </div>
            {* Главное содержимое страницы *}
            {$app->blocks->getMainContent()}
            {if $url->request('Act', TYPE_STRING)!='finish'}
                {moduleinsert name="\Shop\Controller\Block\Cartlist" indexTemplate="blocks/cart/checkout_cart.tpl"}
            {else}
                {moduleinsert name="\Shop\Controller\Block\Cartlist" indexTemplate="blocks/cart/checkout_cart.tpl" order=$order}
            {/if}
        </div>
        {* Данный блок будет переопределен у наследников данного шаблона *}
    {block name="content"}{/block}

    <div class="preFooter">
        <div class="container">
            <div class="row">
                <div class="titleWrap col-md-24 text-center">
                    <h3>Мы принимаем</h3>
                </div>
            </div>
            <div class="row payMethods">
                <div class="text-center col-md-4 col-md-offset-2">
                    <a href="{$router->getUrl('menu.item_4')}" data-toggle="tooltip" title="Оплата наличными"><img src="{$THEME_IMG}/paysystems-cash.png" alt="Оплата наличными" width="85" height="85"></a>
                </div>
                <div class="text-center col-md-4">
                    <a href="{$router->getUrl('menu.item_4')}" data-toggle="tooltip" title="Оплата Qiwi кошельком"><img src="{$THEME_IMG}/paysystems-qw.png" alt="Оплата Qiwi кошельком" width="85" height="85"></a>
                </div>
                <div class="text-center col-md-4">
                    <a href="{$router->getUrl('menu.item_4')}" data-toggle="tooltip" title="Оплата Яндекс Money"><img src="{$THEME_IMG}/paysystems-ym.png" alt="Оплата Яндекс Money" width="85" height="85"></a>
                </div>
                <div class="text-center col-md-4">
                    <a href="{$router->getUrl('menu.item_4')}" data-toggle="tooltip" title="Оплата банковскими картами"><img src="{$THEME_IMG}/paysystems-cards.png" alt="Оплата банковскими картами" width="85" height="85"></a>
                </div>
                <div class="text-center col-md-4">
                    <a href="{$router->getUrl('menu.item_4')}" data-toggle="tooltip" title="Оплата WebMoney"><img src="{$THEME_IMG}/paysystems-wm.png" alt="Оплата WebMoney" width="85" height="85"></a>
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
                        <a href="auto-jack.com"><img src="{$THEME_IMG}/footer-ajlogo.png" alt="AutoJack"></a>
                    </div>
                    <div class="link">
                        <a href="limo-lady.com"><img src="{$THEME_IMG}/footer-lllogo.png" alt="LimoLady"></a>
                    </div>
                </div>
                <div class="item col-md-4">
                    {moduleinsert name="\Menu\Controller\Block\Menu" indexTemplate='blocks/menu/footer_menu.tpl' root='12'}
                </div>
                <div class="item col-md-4">
                    {moduleinsert name="\Menu\Controller\Block\Menu" indexTemplate='blocks/menu/footer_menu.tpl' root='42'}
                </div>
                <div class="item col-md-4">
                    {moduleinsert name="\Menu\Controller\Block\Menu" root="muzhskie-kurtki" indexTemplate="blocks/menu/footer_menu.tpl"}
                </div>
                <div class="item col-md-4">
                    {moduleinsert name="\Menu\Controller\Block\Menu" root="zhenskie-kurtki" indexTemplate="blocks/menu/footer_menu.tpl"}
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
                        <div class="email"><script type="text/javascript">eval(unescape('%64%6f%63%75%6d%65%6e%74%2e%77%72%69%74%65%28%27%3c%61%20%68%72%65%66%3d%22%6d%61%69%6c%74%6f%3a%69%6e%66%6f%40%73%68%6f%70%2d%77%65%61%72%2e%63%6f%6d%22%3e%69%6e%66%6f%40%73%68%6f%70%2d%77%65%61%72%2e%63%6f%6d%3c%2f%61%3e%27%29%3b'))</script></div>
                        <!-- <div class="label">Отдел маркетинга:</div>
                        <div class="email"><a href="mailto:marketing@auto-jack.com">marketing@auto-jack.com</a></div> -->
                    </div>
                    <div class="social">
                        <a href="http://vk.com/autojack" target="_blank">
                            <img src="{$THEME_IMG}/footer-icon-vkontakte.png" height="33" width="33" alt="Группа AutoJack и LimoLady в Vkontakte">
                            <img class="hover" src="{$THEME_IMG}/footer-icon-vkontakte-hover.png" height="33" width="33" alt="Группа AutoJack и LimoLady в Vkontakte">
                        </a>
                        <a href="http://www.facebook.com/autojack.official" target="_blank">
                            <img src="{$THEME_IMG}/footer-icon-facebook.png" height="33" width="33" alt="Группа AutoJack и LimoLady в FaceBook" title="Группа AutoJack и LimoLady в FaceBook">
                            <img class="hover" src="{$THEME_IMG}/footer-icon-facebook-hover.png" height="33" width="33" alt="Группа AutoJack и LimoLady в FaceBook" title="Группа AutoJack и LimoLady в FaceBook">
                        </a>
                        <a href="http://www.youtube.com/user/autojackandlimolady" target="_blank">
                            <img src="{$THEME_IMG}/footer-icon-youtube.png" height="33" width="33" alt="Канал AutoJack и LimoLady на YouTube">
                            <img class="hover" src="{$THEME_IMG}/footer-icon-youtube-hover.png" height="33" width="33" alt="Канал AutoJack и LimoLady на YouTube">
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
</div>
