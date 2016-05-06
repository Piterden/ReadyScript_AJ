{$shop_config=ConfigLoader::byModule('shop')}
{assign var=catalog_config value=ConfigLoader::byModule('catalog')}
{assign var=product_items value=$cart->getProductItems()}
<div class="cart" id="cartItems">
    <div class="top">
        <div class="cartIcon">Корзина</div>
        {if !empty($cart_data.items)}
        <a class="clearCart" href="{$router->getUrl('shop-front-cartpage', ["Act" => "cleanCart"])}"><span>очистить корзину</span></a>
        {/if}
    </div>
    <div class="padd">
        {if !empty($cart_data.items)}
        <div class="head">
            <div class="price">Цена</div>    
            <div class="amount">Количество</div>
        </div>
        <form method="POST" action="{$router->getUrl('shop-front-cartpage', ["Act" => "update"])}" id="cartForm">
            <input type="submit" class="hidden">
            <div class="viewport">
                <table class="cartProducts">
                    {foreach from=$cart_data.items key=index item=item name="items"}
                        {assign var=product value=$product_items[$index].product}
                        {assign var=cartitem value=$product_items[$index].cartitem}
                        {if !empty($cartitem.multioffers)}
                           {assign var=multioffers value=unserialize($cartitem.multioffers)} 
                        {/if}
                        <tr data-id="{$index}" data-product-id="{$cartitem.entity_id}" class="cartitem{if $smarty.foreach.items.first} first{/if}">
                            <td class="colPreview">
                                <a class="preview" href="{$product->getUrl()}"><img src="{$product->getMainImage(64,64)}" alt="{$product.title}"/></a>
                            </td>
                            <td class="colTitle">
                                <a class="title" href="{$product->getUrl()}">{$product.title}</a><br>
                                {if $product->isMultiOffersUse()}
                                    <div class="multiOffers">
                                        {foreach $product.multioffers.levels as $level}
                                            {if !empty($level.values)}
                                                <div class="title">{if $level.title}{$level.title}{else}{$level.prop_title}{/if}</div>
                                                <select name="products[{$index}][multioffers][{$level.prop_id}]" data-prop-title="{if $level.title}{$level.title}{else}{$level.prop_title}{/if}">
                                                    {foreach $level.values as $value}
                                                        <option {if $multioffers[$level.prop_id].value == $value.val_str}selected="selected"{/if} value="{$value.val_str}">{$value.val_str}</option>   
                                                    {/foreach}
                                                </select>
                                            {/if}
                                        {/foreach}
                                        {if $product->isOffersUse()}
                                            {foreach from=$product.offers.items key=key item=offer name=offers}
                                                <input id="offer_{$key}" type="hidden" name="hidden_offers" class="hidden_offers" value="{$key}" data-info='{$offer->getPropertiesJson()}' data-num="{$offer.num}"/>
                                                {if $cartitem.offer==$key}
                                                    <input type="hidden" name="products[{$index}][offer]" value="{$key}"/>
                                                {/if}
                                            {/foreach}
                                        {/if}
                                    </div>
                                {elseif $product->isOffersUse()}
                                    <select name="products[{$index}][offer]" class="offer">
                                        {foreach from=$product.offers.items key=key item=offer name=offers}
                                            <option value="{$key}" {if $cartitem.offer==$key}selected{/if}>{$offer.title}</option>
                                        {/foreach}
                                    </select>
                                {/if}
                            </td>
                            <td class="colAmount">      
                                <div class="amoutPicker">                    
                                    <div class="qpicker">
                                        <a class="inc"></a>
                                        <a class="dec"></a>
                                    </div>                    
                                    <input type="text" maxlength="4" class="fieldAmount" value="{$cartitem.amount}" name="products[{$index}][amount]"> 
                                    <span class="unit">
                                        {if $catalog_config.use_offer_unit}
                                            {$product.offers.items[$cartitem.offer]->getUnit()->stitle}
                                        {else}
                                            {$product->getUnit()->stitle}
                                        {/if}
                                    </span>
                                    <div class="error">{$item.amount_error}</div>
                                </div>
                            </td>
                            <td class="colPrice">
                                <div class="floatbox">
                                    <span class="priceBlock">
                                        <span class="priceValue">{$item.cost}</span>
                                    </span>
                                </div>
                                <div class="discount">
                                    {if $item.discount>0}
                                    скидка {$item.discount}
                                    {/if}
                                </div>
                            </td>
                            <td class="colRemove">
                                <a title="Удалить товар из корзины" class="remove" href="{$router->getUrl('shop-front-cartpage', ["Act" => "removeItem", "id" => $index])}"></a>
                            </td>
                        </tr>
                        {assign var=concomitant value=$product->getConcomitant()}
                        
                        {foreach from=$item.sub_products key=id item=sub_product_data}
                            {assign var=sub_product value=$concomitant[$id]}
                            <tr>

                                <td colspan="2" class="colTitle">
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
                                <td class="colAmount">
                                    {if $shop_config.allow_concomitant_count_edit}
                                        <div class="amoutPicker">                    
                                            <div class="qpicker">
                                                <a class="inc"></a>
                                                <a class="dec"></a>
                                            </div>                            
                                            <input type="text" maxlength="4" class="fieldAmount concomitant" data-id="{$sub_product->id}" value="{$sub_product_data.amount}" name="products[{$index}][concomitant_amount][{$sub_product->id}]"> 
                                            <span class="unit">{$product->getUnit()->stitle}</span>
                                        </div>
                                    {else}
                                        {$sub_product_data.amount} {$sub_product->getUnit()->stitle}
                                    {/if}
                                    <div class="error">{$sub_product_data.amount_error}</div>
                                </td>
                                <td class="colPrice">
                                    <span class="priceBlock">
                                        <span class="priceValue">{$sub_product_data.cost}</span>
                                    </span>
                                    <div class="discount">
                                        {if $sub_product_data.discount>0}
                                        скидка {$sub_product_data.discount}
                                        {/if}
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        {/foreach}
                    {/foreach}
                </table>
            </div>
            <div class="cartFooter">
                <div class="linesContainer">
                    {foreach from=$cart->getCouponItems() key=id item=item}
                        <div class="line">
                            <a href="{$router->getUrl('shop-front-cartpage', ["Act" => "removeItem", "id" => $id])}" class="remove" title="{t}удалить скидочный купон{/t}"></a>
                            <div class="text">{t}Купон на скидку{/t} {$item.coupon.code}</div>
                            <div class="digits"></div>
                        </div>
                    {/foreach}
                    {if $cart_data.total_discount>0}
                        <div class="line">
                            <div class="text">Скидка на заказ</div>
                            <div class="digits">{$cart_data.total_discount}</div>
                        </div>                        
                    {/if}
                </div>
                <div class="discountText">
                    <span class="info">Купон на скидку (если есть): </span><input type="text" class="couponCode{if $cart->getUserError('coupon')!==false} hasError{/if}" size="12" name="coupon" value="{$coupon_code}">&nbsp;
                    <a class="applyCoupon" data-href="{$router->getUrl('shop-front-cartpage', ["Act" => "applyCoupon"])}">применить</a>
                </div>
                <div class="total"><span class="text">Итого:</span> <span class="total-value">{$cart_data.total}</span></div>
                <div class="loader"></div>                                
            </div>
            <div class="bottom">
                <noscript><input type="submit" class="onemoreEmpty recalc" value="{t}Пересчитать{/t}"></noscript>
                <a href="{$router->getUrl('shop-front-checkout')}" class="submit{if $cart_data.has_error} disabled{/if}">{t}Оформить заказ{/t}</a>
                <a href="JavaScript:;" class="continue">Продолжить покупки</a>
                <div class="error" {if !empty($cart_data.errors)}style="display: block;"{/if}>
                    {foreach from=$cart_data.errors item=error}
                        {$error}<br>
                    {/foreach}
                </div>
            </div>
        </form>
        {else}
            <div class="empty">В корзине нет товаров</div>
        {/if}
    </div>
</div>