<div class="bodyWrap">
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
            <div class="col-md-12">
                {moduleinsert name="\Main\Controller\Block\Breadcrumbs"}
            </div>
        </div>
    </div>
    <div class="viewport mainContent">

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
                        <img src="{$THEME_IMG}/big-icon-pay.png" alt="Способ оплаты">
                    </div>
                    <div class="text-center col-md-4">
                        <img src="{$THEME_IMG}/big-icon-pay.png" alt="Способ оплаты">
                    </div>
                    <div class="text-center col-md-4">
                        <img src="{$THEME_IMG}/big-icon-pay.png" alt="Способ оплаты">
                    </div>
                    <div class="text-center col-md-4">
                        <img src="{$THEME_IMG}/big-icon-pay.png" alt="Способ оплаты">
                    </div>
                    <div class="text-center col-md-4">
                        <img src="{$THEME_IMG}/big-icon-pay.png" alt="Способ оплаты">
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
                        {moduleinsert name="\Menu\Controller\Block\Menu" root="info" indexTemplate="blocks/menu/footer_menu.tpl"}
                    </div>
                    <div class="item col-md-4">
                        {moduleinsert name="\Menu\Controller\Block\Menu" root="pokupatelyam" indexTemplate="blocks/menu/footer_menu.tpl"}
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
                            <div class="email"><a href="mailto:info@auto-jack.com">info@auto-jack.com</a></div>
                            <div class="label">Отдел маркетинга:</div>
                            <div class="email"><a href="mailto:marketing@auto-jack.com">marketing@auto-jack.com</a></div>
                        </div>
                        <div class="social">
                            <a href="http://vk.com/autojack" target="_blank">
                                <img src="{$THEME_IMG}/footer-icon-vkontakte.png" height="33" width="33" alt="Группа AutoJack и LimoLady в Vkontakte">
                                <img class="hover" src="{$THEME_IMG}/footer-icon-vkontakte-hover.png" height="33" width="33" alt="Группа AutoJack и LimoLady в Vkontakte">
                            </a>
                            <a href="http://www.facebook.com/autojack.official" target="_blank">
                                <img src="{$THEME_IMG}/footer-icon-facebook.png" height="33" width="33" alt="Группа AutoJack и LimoLady в FaceBook">
                                <img class="hover" src="{$THEME_IMG}/footer-icon-facebook-hover.png" height="33" width="33" alt="Группа AutoJack и LimoLady в FaceBook">
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

</div> <!-- .bodyWrap -->
