{$shop_config=ConfigLoader::byModule('shop')}
{assign var=catalog_config value=ConfigLoader::byModule('catalog')}
{assign var=product_items value=$cart->getProductItems()}
{$floatCart=(int)$smarty.request.floatCart}
{if $floatCart}
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
                            {foreach from=$cart_data.items key=index item=item}
                                {assign var=product value=$product_items[$index].product}
                                {assign var=cartitem value=$product_items[$index].cartitem}
                                {if !empty($cartitem.multioffers)}
                                       {$multioffers=unserialize($cartitem.multioffers)}
                                {/if}
                            <tr class="delimiter"></tr>
                            <tr data-id="{$index}" data-product-id="{$cartitem.entity_id}" class="cartitem{if $item@first} first{/if}">
                                <td class="remove"><a href="{$router->getUrl('shop-front-cartpage', ["Act" => "removeItem", "id" => $index, "floatCart" => $floatCart])}" title="Удалить товар из корзины" class="iconRemove"><i class="fa fa-times"></i></a></td>
                                <td class="image"><a href="{$product->getUrl()}"><img src="{$product->getMainImage(65,94, 'axy')}" alt="{$product.title}"/></a></td>
                                <td class="title"><a href="{$product->getUrl()}">{$product.title}</a>
                                        {if $product.barcode}<p class="barcode">Артикул: {$product.barcode}</p>{/if}

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
                                                        <input id="offer_{$key}" type="hidden" name="hidden_offers" class="hidden_offers" value="{$key}" data-info='{$offer->getPropertiesJson()}' data-num="{$offer.num}"/>
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
                    <div data-id="{$index}" data-product-id="{$cartitem.entity_id}" class="row cartitem{if $smarty.foreach.items.first} first{/if}">
                        <div class="image col-sm-2 col-sm-offset-2">
                            <a href="{$product->getUrl()}"><img src="{$product->getMainImage(78,109)}" alt="{$product.title}"/></a>
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
