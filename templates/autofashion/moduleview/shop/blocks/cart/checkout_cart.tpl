{assign var=catalog_config value=ConfigLoader::byModule('catalog')}
<div class="row">
    <div class="titleWrap col-md-24 text-center">
        <h3>Ваш заказ</h3>
    </div>
	<div class="col-sm-20 col-sm-offset-2 cartList">
		{foreach $cart_items as $item}
			{if $url->request('Act', TYPE_STRING)=='finish'}
				{$item=$item.cartitem}
			{/if}
			{$product=$cart_products[$item.entity_id]}
			{$main_image=$product->getMainImage()}
			{$offer_list=$item->getMultiOfferTitles()}
			{$offer=$item['offer']}
			<div class="row cartItem">
				<div class="col-sm-2 imageBlock">
					<a href="{$product->getUrl()}" title="{$product.title}"><img src="{$main_image->getUrl(72, 72)}" alt="{$main_image.title|default:"{$product.title}"}"/></a>
				</div>
				<div class="col-sm-16 infoBlock">
					<div class="title h3"><a href="{$product->getUrl()}">{$product.title}</a></div>
					<div class="sku">Артикул: {$product->getBarCode($offer)}</div>
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
                            {$product.offers.items[$item.offer]->getUnit()->stitle}
                        {else}
                            {$product->getUnit()->stitle|default:"шт."}
                        {/if}
                        </span>

                        <a class="dec"><i class="fa fa-angle-down"></i></a>
                    </div>
				</div>
				<div class="col-sm-3 priceBlock">
					{$product->getCost(null, $offer)} {$currency.stitle}
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
					{if $url->request('Act', TYPE_STRING)!='finish'}
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
						<span class="text-capitalize">{$order.order_extra.delivery.region_id_to|lower}</span><br>
						{$order.order_extra.delivery.sd_html|replace:'&lt;':'<'|replace:'&gt;':'>'|replace:'&quot;':'"'}
					{/if}
				</address>
			</div>
			<div class="col-sm-6 priceBlock priceOfDelivery">
				{$delivery->getDeliveryCostText($order)}
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
					{if $url->request('Act', TYPE_STRING)!='finish'}
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
				{$order->getTotalPrice()}
			</div>
		</div>

	</div>
	<div class="col-sm-20 col-sm-offset-2 cartList noborder">
		<div class="row atAll">
			<div class="col-sm-6 col-sm-offset-2 changeCartBlock">
				<a href="{$router->getUrl('shop-front-cartpage')}" class="">Изменить заказ</a>
			</div>
			<div class="col-sm-10">
				&nbsp;
			</div>
			<div class="col-sm-3 atAllBlock">
				Итого:
			</div>
			<div class="col-sm-3 priceBlock priceAllBlock">
				{$cart->getCustomOrderPrice()|number_format:0:".":" "} {$currency.stitle}
			</div>
		</div>
	</div>
</div>
<script>
	jQuery(document).ready(function($) {
		$('.checkout .cartList .row').each(function() {
			var maxHeight = 65,
				$childrens = $(this).children('div');
			$childrens.each(function() {
				console.log($(this).height())
				maxHeight = (maxHeight > $(this).height()) ? maxHeight : $(this).height();
			})
			$childrens.height(maxHeight);
		});
	});
</script>
