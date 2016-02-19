<div class="viewport" id="cartItems">
    <div class="cartFloatBlock">
        {if !empty($cart_data.items)}
            <form method="POST" action="{$router->getUrl('shop-front-cartpage', ["Act" => "update", "floatCart" => $floatCart])}" id="cartForm">
                <div class="cartTop">
                    <div class="totalItems">товаров в корзине: <span class="productsValue">{$cart_data.items_count}</span></div>
                    <div class="closeDlg">скрыть</div>
                </div>
                <div class="scrollBox">
                    <table class="items cartTable">
                        <tbody>
                            {foreach $cart_data.items as $index => $item}
                                {$product=$product_items[$index].product}
                                {$cartitem=$product_items[$index].cartitem}
                                {if !empty($cartitem.multioffers)}
                                    {$multioffers=unserialize($cartitem.multioffers)}
                                {/if}
                            	{$offer=$product.offers.items[$cartitem.offer]}
                                <tr class="delimiter"></tr>
                                <tr data-id="{$index}" data-product-id="{$cartitem.entity_id}" class="cartitem{if $item@first} first{/if}">
                                    <td class="remove">
	                                    <a href="{$router->getUrl('shop-front-cartpage', ["Act" => "removeItem", "id" => $index, "floatCart" => $floatCart])}" title="Удалить товар из корзины" class="iconRemove"><i class="fa fa-times"></i></a>
	                                </td>
                                    <td class="image">
                                    	<a href="{$product->getUrl()}">
                                    		<img src="{$offer->getMainImage(65,94, 'axy')}" alt="{$product.title}"/>
                                    	</a>
                                    </td>
                                    <td class="title"><a href="{$product->getUrl()}">{$product.title}</a>
                                        {if $product.barcode}<p class="barcode offerBarcode">Артикул: {$product.barcode}</p>{/if}

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
                                                {if $product->isOffersUse()}
                                                    {foreach from=$product.offers.items key=key item=offer name=offers}
                                                        <input id="offer_{$key}" type="hidden" name="hidden_offers" class="hidden_offers" value="{$key}" data-info='{$offer->getPropertiesJson()}' data-num="{$offer.num}" data-change-cost='{ ".offerBarcode": "{$offer.barcode|default:$product.barcode}", ".myCost": "{$product->getCost(null, $key)}", ".lastPrice": "{$product->getCost('Зачеркнутая цена', $key)}"}' data-images='{$offer->getPhotosJson()}' data-sticks='{$offer->getStickJson()}'/>
                                                        {if $cartitem.offer==$key}
                                                            <input type="hidden" name="products[{$index}][offer]" value="{$key}"/>
                                                        {/if}
                                                    {/foreach}
                                                {/if}
                                                <div class="amount">
                                                    <input type="hidden" class="fieldAmount" value="{$cartitem.amount}" name="products[{$index}][amount]"/>
                                                    <a class="inc"><i class="fa fa-angle-up"></i></a>
                                                    <span class="num" title="Количество">{$cartitem.amount}</span>
                                                    <span class="unit">
                                                        {if $catalog_config.use_offer_unit}
                                                            {$product.offers.items[$cartitem.offer]->getUnit()->stitle}
                                                        {else}
                                                            {$product->getUnit()->stitle|default:"шт."}
                                                        {/if}
                                                    </span>
                                                    <a class="dec"><i class="fa fa-angle-down"></i></a>
                                                </div>
                                            </div>
                                        {elseif $product->isOffersUse()}
                                            <div class="offers">
                                                <select name="products[{$index}][offer]" class="offer">
                                                    {foreach from=$product.offers.items key=key item=offer name=offers}
                                                        <option value="{$key}" {if $cartitem.offer==$key}selected{/if}>{$offer.title}</option>
                                                    {/foreach}
                                                </select>
                                                <div class="amount">
                                                    <input type="hidden" class="fieldAmount" value="{$cartitem.amount}" name="products[{$index}][amount]"/>
                                                    <a class="inc"><i class="fa fa-angle-up"></i></a>
                                                    <span class="num" title="Количество">{$cartitem.amount}</span>
                                                    <span class="unit">
                                                        {if $catalog_config.use_offer_unit}
                                                            {$product.offers.items[$cartitem.offer]->getUnit()->stitle}
                                                        {else}
                                                            {$product->getUnit()->stitle|default:"шт."}
                                                        {/if}
                                                    </span>
                                                    <a class="dec"><i class="fa fa-angle-down"></i></a>
                                                </div>
                                            </div>
                                        {/if}
                                    </td>
                                    <td class="price">
                                        <div class="cost">{$item.cost}</div>
                                        <div class="discount">
                                            {if $item.discount>0}
                                                <div>скидка </div><div class="discountCost">{$item.discount}</div>
                                            {/if}
                                        </div>
                                        <div class="error">{$item.amount_error}</div>
                                    </td>
                                </tr>
                                {assign var=concomitant value=$product->getConcomitant()}
                                {foreach from=$item.sub_products key=id item=sub_product_data}
                                    {assign var=sub_product value=$concomitant[$id]}
                                    <tr>
                                        <td colspan="2" class="title">
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
                                        <td class="price">
                                            {if $shop_config.allow_concomitant_count_edit}
                                                <div class="amount">
                                                    <input type="hidden" class="fieldAmount concomitant" data-id="{$sub_product->id}" value="{$sub_product_data.amount}" name="products[{$index}][concomitant_amount][{$sub_product->id}]">
                                                    <a class="dec"></a>
                                                    <span class="num" title="Количество">{$sub_product_data.amount}</span> {$product->getUnit()->stitle|default:"шт."}
                                                    <a class="inc"></a>
                                                </div>
                                            {else}
                                                <div class="amount" title="Количество">{$sub_product_data.amount} {$sub_product->getUnit()->stitle|default:"шт."}</div>
                                            {/if}
                                            <div class="cost">{$sub_product_data.cost}</div>
                                            <div class="error">{$sub_product_data.amount_error}</div>
                                        </td>
                                        <td class="remove"></td>
                                    </tr>
                                {/foreach}
                            {/foreach}
                            {foreach from=$cart->getCouponItems() key=id item=item}
                                <tr data-id="{$index}" data-product-id="{$cartitem.entity_id}" class="cartitem couponLine">
                                    <td colspan="2" class="title">Купон на скидку {$item.coupon.code}</td>
                                    <td class="price"></td>
                                    <td class="remove"><a href="{$router->getUrl('shop-front-cartpage', ["Act" => "removeItem", "id" => $id, "floatCart" => $floatCart])}" title="Удалить скидочный купон" class="iconRemove"></a></td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
                <div class="cartHeader">
                    <div class="totalRow">
                        <span>Стоимость товаров: </span>
                        <span class="price">
                            {$cart_data.total}
                        </span>
                    </div>
                    <a href="{$router->getUrl('shop-front-cartpage', ["Act" => "cleanCart", "floatCart" => $floatCart])}" class="clearCart">очистить корзину</a>
                </div>
                <div class="cartFooter{if $coupon_code} onPromo{/if}">
                    <a class="hasPromo" onclick="$(this).parent().toggleClass('onPromo')">Ввести промо-код</a>
                    <div class="promo">
                        Промо-код: &nbsp;<input type="text" name="coupon" value="{$coupon_code}" class="couponCode">&nbsp;
                        <a data-href="{$router->getUrl('shop-front-cartpage', ["Act" => "applyCoupon", "floatCart" => $floatCart])}" class="applyCoupon">применить</a>
                    </div>
                </div>
                <div class="actionLine">
                    <a href="#" class="btn btn-primary closeDlg">Продолжить покупки</a>
                    <a href="{$router->getUrl('shop-front-checkout')}" class="submit btn btn-success color{if $cart_data.has_error} disabled{/if}">Оформить заказ</a>
                </div>
                <div class="cartError bg-danger{if empty($cart_data.errors)} hidden{/if}">
                    {foreach from=$cart_data.errors item=error}
                        {$error}<br>
                    {/foreach}
                </div>
            </form>
        {else}
            <div class="emptyCart">
                В корзине нет товаров
            </div>
        {/if}
    </div>
</div>
