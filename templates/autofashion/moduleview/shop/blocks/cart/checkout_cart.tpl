{assign var=catalog_config value=ConfigLoader::byModule('catalog')}
<div class="row">
    <div class="titleWrap col-md-24 text-center">
        <h3>Ваш заказ</h3>
    </div>
    <div class="col-sm-20 col-sm-offset-2 cartList">
        {foreach $cart_items as $item}
            {if $url->request('Act', $smarty.const.TYPE_STRING)=='finish'}
                {$item=$item.cartitem}
            {/if}
            {assign var="product" value=$cart_products[$item.entity_id]}
            <!-- {$product->fillOffers()} -->
            {assign var="offer" value=$product.offers.items[$item.offer]}
            {assign var="offer_list" value=$item->getMultiOfferTitles()}
            <div class="row cartItem">
                <div class="col-sm-2 imageBlock">
                    <a href="{$product->getUrl()}" title="{$product.title}"><img src="{$offer->getMainImage(72, 72)}" alt="{$offer.title|default:"{$product.title}"}"/></a>
                </div>
                <div class="col-sm-16 infoBlock">
                    <div class="title h3"><a href="{$product->getUrl()}">{$product.title}</a></div>
                    <div class="sku">Артикул: {$product->getBarCode($item.offer)}</div>
                    <div class="offer">
                        {foreach $offer_list as $offer_field}
                            <span class="title">{$offer_field.title}: </span>
                            <span class="value">{$offer_field.value}</span>
                        {/foreach}
                    </div>
                </div>
                <div class="col-sm-3 amountBlock">
                    <div class="amount">
                        <input type="hidden" class="fieldAmount" value="{$item.amount}" name="products[{$index}][amount]"/>
                        <a class="inc"><i class="fa fa-angle-up"></i></a>
                        <span class="num" title="Количество">{$item.amount}</span>

                        <span class="unit">
                            {if $catalog_config.use_offer_unit}
                                {$offer->getUnit()->stitle}
                            {else}
                                {$product->getUnit()->stitle|default:"шт."}
                            {/if}
                        </span>

                        <a class="dec"><i class="fa fa-angle-down"></i></a>
                    </div>
                </div>
                <div class="col-sm-3 priceBlock">
                    <span class="itemCost">{$product->getCost(null, $item.offer)}</span>
                    <span class="currency">{$currency.stitle}</span>
                </div>
            </div>
        {/foreach}

        {assign var=delivery value=$order->getDelivery()}
        <div class="row deliveryItem{if !$delivery->valid()} hidden{/if}">
            <div class="col-sm-2 imageBlock">
                <span class="step_delivery"><span class="item"></span></span>
            </div>
            <div class="col-sm-16 infoBlock">
                <div class="headerBlock">
                    <span class="title h3">{$delivery.title}</span>
                    {if $url->request('Act',$smarty.const.TYPE_STRING)!='finish'}
                        <span class="change"><a href="{$router->getUrl('shop-front-checkout', ['Act' => 'delivery'])}">Изменить способ доставки</a></span>
                    {/if}
                </div>
                <div class="footerBlock">
                    {$delivery.description}
                </div>
                <address class="addressOfDelivery">
                    {if $order.use_addr > 0 && $order->getAddress()->valid()}
                        {$order->getAddress()->getLineView()}
                    {elseif $order.use_addr < 0}
						{if $order->getExtraKeyPair('update') == true}
							<div class="sdInfoWrap">
						    	<span class="text-capitalize">{$order->getExtraKeyPair('region_id_to')|lower}</span><br>
						        {$order->getExtraKeyPair('sd_info')|replace:'&lt;':'<'|replace:'&gt;':'>'|replace:'&quot;':'"'}
							</div>
						{/if}
                    {/if}
                </address>
            </div>
            <div class="col-sm-6 priceBlock priceOfDelivery">
                {if $delivery}
                    <span class="itemCost">{$delivery->getDeliveryCost($order, null, $delivery)}</span> <span class="currency">{$currency.stitle}</span>
                {/if}
            </div>
        </div>

        {assign var=payment value=$order->getPayment()}
        <div class="row paymentItem{if !$payment->valid()} hidden{/if}">
            <div class="col-sm-2 imageBlock">
                <span class="step_payment"><span class="item"></span></span>
            </div>
            <div class="col-sm-16 infoBlock">
                <div class="headerBlock">
                    <span class="title h3">{$payment.title}</span>
                    {if $url->request('Act',$smarty.const.TYPE_STRING)!='finish'}
                        <span class="change"><a href="{$router->getUrl('shop-front-checkout', ['Act' => 'payment'])}">Изменить способ оплаты</a></span>
                    {/if}
                </div>
                <div class="footerBlock description">
                    {$payment.description}
                </div>
            </div>
            {if (false)}
                <div class="col-sm-6 taxBlock">
                    2.5%
                </div>
            {/if}
            <div class="col-sm-6 priceBlock">
                {* {$order->getTotalPrice()} *}
            </div>
        </div>

    </div>
    <div class="col-sm-20 col-sm-offset-2 cartList noborder">
        <div class="row atAll">
            {if $url->request('Act',$smarty.const.TYPE_STRING)!='finish'}
	            <div class="col-sm-6 col-sm-offset-2 changeCartBlock">
	                <a href="{$router->getUrl('shop-front-cartpage')}" class="">Изменить заказ</a>
	            </div>
            {/if}
            <div class="col-sm-10">
                &nbsp;
            </div>
            <div class="col-sm-3 atAllBlock">
                Итого:
            </div>
            <div class="col-sm-3 priceBlock priceAllBlock">
                <span id="t-cost"></span> <span class="currency">{$currency.stitle}</span>
            </div>
        </div>
        <pre>
        {$order|@print_r}
        </pre>
    </div>
</div>
{literal}
<script>
    jQuery(document).ready(function($) {

    	var cost = 0;

        $('.checkout .cartList .row').each(function() {
            var maxHeight = 65,
                $childrens = $(this).children('div');

            $childrens.each(function() {
                maxHeight = (maxHeight > $(this).height()) ? maxHeight : $(this).height();
            });
            $childrens.height(maxHeight);

            cost += new Number($(this).find('.itemCost').text().replace(/\s/g, ''));
        });

        $('#t-cost').text(cost);
    });
</script>
{/literal}
