{* Вставляем блок "меню" *}
<nav class="topMenuNav">
    <div class="container">
        <div class="row">
            <div class="col-sm-24">
                {moduleinsert name="\Menu\Controller\Block\Menu" root="main-menu" indexTemplate="theme:autojack/blocks/menu/hor_menu.tpl"}
                <div class="searchFormWrap">
                    {moduleinsert name="\Catalog\Controller\Block\SearchLine" indexTemplate="theme:autojack/blocks/searchline/searchform.tpl" hideAutoComplete=0 searchLimit=5}
                </div>
                <div class="headerIconsBlock">
                    <div class="userAuthBlock">
                        <a href="#" class="userAuthButton">
                            <img src="{$THEME_IMG}/user.png" alt="Wishlist">
                        </a>
                        {moduleinsert name="\Users\Controller\Block\Authblock" indexTemplate="theme:autojack/blocks/authblock/authblock.tpl"}
                    </div>
                    <div class="cartBlock">
                        <a href="#" class="cartButton">
                            <img src="{$THEME_IMG}/cart.png" alt="Wishlist">
                        </a>
                        {moduleinsert name="\Shop\Controller\Block\Cart" indexTemplate="theme:autojack/blocks/cart/cart.tpl"}
                    </div>
                    <div class="wishlistBlock">
                        <a href="#" class="wishlistButton">
                            <img src="{$THEME_IMG}/wishlist.png" alt="Wishlist">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<header class="container">
    <div class="row">
        <div class="col-sm-24">
            <div class="logoBlock">
                {* Вставляем блок "логитип" *}
                {moduleinsert name="\Main\Controller\Block\Logo" width="335" height="60" indexTemplate="theme:autojack/blocks/logo/logo.tpl"}
            </div>
            <div class="categoriesList">
                {* Вставляем блок "категории товаров" *}
                {moduleinsert name="\Catalog\Controller\Block\Category" indexTemplate="theme:autojack/blocks/category/category.tpl"}
            </div>
            <div class="clearfix"></div>        
        </div>
    </div>
</header>
{* Позволяем наследникам этого шаблона определять данную область *}
{block name="content"}{/block}

<footer>
    <div class="container">
        <div class="row">
            <div class="item col-md-4 col-md-offset-2">
                {moduleinsert name="\Menu\Controller\Block\Menu" root="info" indexTemplate="theme:autojack/blocks/menu/footer_menu.tpl"}
            </div>
            <div class="item col-md-4">
                {moduleinsert name="\Menu\Controller\Block\Menu" root="pokupatelyam" indexTemplate="theme:autojack/blocks/menu/footer_menu.tpl"}
            </div>
            <div class="item col-md-4">
                {moduleinsert name="\Menu\Controller\Block\Menu" root="muzhskie-kurtki" indexTemplate="theme:autojack/blocks/menu/footer_menu.tpl"}
            </div>
            <div class="item col-md-4">
                {moduleinsert name="\Menu\Controller\Block\Menu" root="zhenskie-kurtki" indexTemplate="theme:autojack/blocks/menu/footer_menu.tpl"}
            </div>
            <div class="item col-md-4">
                <div class="title h4"></div>
                <div class="list h4">
                    
                </div>
            </div>
        </div>
        <div class="row copyright">
            
        </div>
    </div>

    <!-- {* Вставляем еще раз блок "логотип", но с другими параметрами *}
    {moduleinsert name="\Main\Controller\Block\Logo" width="158" height="19"} -->
</footer>