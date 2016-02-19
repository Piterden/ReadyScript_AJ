{$shop_config=ConfigLoader::byModule('shop')}
{$catalog_config=ConfigLoader::byModule('catalog')}
{$product_items=$cart->getProductItems()}
{$floatCart=(int)$smarty.request.floatCart}
{if $floatCart}
	{include file="%THEME%/moduleview/shop/blocks/cart/floatcart.tpl"}
{else}
    <div class="cartPage" id="cartItems">
        <div class="title text-center">
            <h1>Корзина</h1>
        </div>
        {if !empty($cart_data.items)}
            <a href="{$router->getUrl('shop-front-cartpage', ["Act" => "cleanCart"])}" class="clearCart">Очистить корзину</a>
            <form method="POST" action="{$router->getUrl('shop-front-cartpage', ["Act" => "update"])}" id="cartForm" class="formStyle">
                <input type="submit" class="hidden">
                <div class="scrollCartWrap">
                    {foreach $cart_data.items as $index => $item}
                        {$product=$product_items[$index].product}
                        {$cartitem=$product_items[$index].cartitem}
                        {if !empty($cartitem.multioffers)}
                            {$multioffers=unserialize($cartitem.multioffers)}
                        {/if}
                        {$offer=$product.offers.items[$cartitem.offer]}
                        <div data-id="{$index}" data-product-id="{$cartitem.entity_id}" class="row cartitem{if $smarty.foreach.items.first} first{/if}">
                            <div class="image col-sm-2 col-sm-offset-2">
                                <a href="{$product->getUrl()}"><img src="{$offer->getMainImage(78,109)}" alt="{$product.title}"/></a>
                            </div>
                            <div class="info col-sm-8">
                                <a href="{$product->getUrl()}" class="text h3">{$product.title}</a>
                                {if $product.barcode}<div class="barcode">Артикул: {$product->getBarCode($cartitem.offer)}</div>{/if}
                                <div class="desc">{$product.short_description}</div>
                            </div>
                            <div class="offers col-sm-4">
                                {if $product->isMultiOffersUse()}
                                    <div class="multiOffers">
                                        {foreach $product.multioffers.levels as $level}
                                            {if !empty($level.values)}
                                                <div class="multiofferTitle">{if $level.title}{$level.title}{else}{$level.prop_title}{/if}</div>
                                                <select name="products[{$index}][multioffers][{$level.prop_id}]" data-prop-title="{if $level.title}{$level.title}{else}{$level.prop_title}{/if}">
                                                    {foreach $level.values as $value}
                                                        <option {if $multioffers[$level.prop_id].value == $value.val_str}selected="selected"{/if} value="{$value.val_str}">{$value.val_str}</option>
                                                    {/foreach}
                                                </select>
                                                <br>
                                            {/if}
                                        {/foreach}
                                        {foreach from=$product.offers.items key=key item=offer name=offers}
                                            <input id="offer_{$key}" type="hidden" name="hidden_offers" class="hidden_offers" value="{$key}" data-info='{$offer->getPropertiesJson()}' data-num="{$offer.num}"/>
                                            {if $cartitem.offer==$key}
                                                <input type="hidden" name="products[{$index}][offer]" value="{$key}"/>
                                            {/if}
                                        {/foreach}
                                    </div>
                                {elseif $product->isOffersUse()}
                                    <div class="offers">
                                        <select name="products[{$index}][offer]" class="offer">
                                            {foreach from=$product.offers.items key=key item=offer name=offers}
                                                <option value="{$key}" {if $cartitem.offer==$key}selected{/if}>{$offer.title}</option>
                                            {/foreach}
                                        </select>
                                    </div>
                                {/if}
                            </div>
                            <div class="amount col-sm-3">
                                <input type="text" maxlength="4" class="inp fieldAmount" value="{$cartitem.amount}" name="products[{$index}][amount]">
                                <div class="incdec">
                                    <a href="#" class="inc"><i class="fa fa-chevron-up"></i></a>
                                    <a href="#" class="dec"><i class="fa fa-chevron-down"></i></a>
                                </div>
                                <span class="unit">
                                    {if $catalog_config.use_offer_unit}
                                        {$product.offers.items[$cartitem.offer]->getUnit()->stitle}
                                    {else}
                                        {$product->getUnit()->stitle}
                                    {/if}
                                </span>
                                <div class="error">{$item.amount_error}</div>
                            </div>
                            <div class="price col-sm-3">
                                {$item.cost}
                                <div class="discount">
                                    {if $item.discount>0}
                                        скидка {$item.discount}
                                    {/if}
                                </div>
                            </div>
                            <div class="remove col-sm-1">
                                <a title="Удалить товар из корзины" class="removeItem" href="{$router->getUrl('shop-front-cartpage', ["Act" => "removeItem", "id" => $index])}"><i class="fa fa-times"></i></a>
                            </div>
                        </div>
                        {$concomitant=$product->getConcomitant()}
                        {foreach $item.sub_products as $id => $sub_product_data}
                            {$sub_product=$concomitant[$id]}
                            <tr class="concomitant">
                                <td class="image"></td>
                                <td class="title">
                                    <label>
                                        <input
                                            class="fieldConcomitant"
                                            type="checkbox"
                                            name="products[{$index}][concomitant][]"
                                            value="{$sub_product->id}"
                                            {if $sub_product_data.checked}
                                                checked="checked"
                                            {/if}
                                            >
                                        {$sub_product->title}
                                    </label>
                                </td>
                                <td class="amount">
                                    {if $shop_config.allow_concomitant_count_edit}
                                        <input type="text" maxlength="4" class="inp fieldAmount concomitant" data-id="{$sub_product->id}" value="{$sub_product_data.amount}" name="products[{$index}][concomitant_amount][{$sub_product->id}]">
                                        <div class="incdec">
                                            <a href="#" class="inc"></a>
                                            <a href="#" class="dec"></a>
                                        </div>
                                    {else}
                                        <span class="amountWidth">{$sub_product_data.amount}</span>
                                    {/if}
                                    <div class="error">{$sub_product_data.amount_error}</div>
                                </td>
                                <td class="price">
                                    {$sub_product_data.cost}
                                </td>
                                <td class="remove"></td>
                            </tr>
                        {/foreach}
                    {/foreach}
                </div>
                <table class="cartTablePage cartTable">
                    <tbody>
                        {foreach $cart->getCouponItems() as $id => $item}
                            <tr class="coupons" data-id="{$id}">
                                <td class="image"></td>
                                <td class="title">Купон на скидку {$item.coupon.code}</td>
                                <td class="amount"></td>
                                <td class="price"></td>
                                <td class="remove">
                                    <a title="Удалить скидочный купон из корзины" class="iconRemove" href="{$router->getUrl('shop-front-cartpage', ["Act" => "removeItem", "id" => $id])}"></a>
                                </td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>

                <div class="cartTableAfter">
                    <p class="price">{$cart_data.total}</p>
                    <div class="loader"></div>

                    <span class="cap">Купон на скидку(если есть)&nbsp; </span>
                    <input type="text" class="couponCode{if $cart->getUserError('coupon')!==false} hasError{/if}" name="coupon" value="{$coupon_code}">
                    <a data-href="{$router->getUrl('shop-front-cartpage', ["Act" => "applyCoupon"])}" class="applyCoupon">Применить</a>
                </div>
                {if !empty($cart_data.errors)}
                    <div class="cartErrors">
                        {foreach $cart_data.errors as $error}
                            {$error}<br>
                        {/foreach}
                    </div>
                {/if}

                <div class="actionLine">
                    <a href="#" class="button continue">Продолжить покупки</a>
                    <a href="{$router->getUrl('shop-front-checkout')}" class="submit button color{if $cart_data.has_error} disabled{/if}">Оформить заказ</a>
                </div>
            </form>
        {else}
            <div class="emptyCart">
                В корзине нет товаров
            </div>
        {/if}
    </div>
{/if}
