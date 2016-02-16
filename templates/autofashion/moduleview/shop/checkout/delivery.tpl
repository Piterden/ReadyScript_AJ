{* {debug} *}
<form method="POST" class="formStyle checkoutBox" id="order-form">
    {if $order->hasError()}
        <div class="row">
            <div class="pageError col-sm-14 col-sm-offset-5">
                {foreach $order->getErrors() as $item}
                    <div class="text-danger">{$item}</div>
                {/foreach}
            </div>
        </div>
    {/if}
    <!-- <input type="hidden" name="delivery" value="0"> -->
    <div class="row">
        <div class="titleWrap col-sm-24 text-center">
            <h3>Способ доставки</h3>
        </div>
    </div>


    <ul class="vertItems">
        {foreach $delivery_list as $item}
        	{$delivery_type=$item->getTypeObject()}
            {$something_wrong=$delivery_type->somethingWrong($order)}
            {if $order['order_extra']['delivery'] && $order['use_addr'] < 0}
            	{$sd_choosed=$order['order_extra']['delivery']}
            {/if}
            <li class="deliveryItem row{if $item@first} first{/if}{if $something_wrong} collapse{/if}" id="delivery_{$item.id}" data-delivery-id="{$item.id}" role="tabpanel">
                <div class="radio col-sm-8">
                    {if !empty($item.picture)}
                        <label for="dlv_{$item.id}" class="logoService">
                            <img class="" src="{$item.__picture->getUrl(100, 100, 'xy')}" alt="{$item.title}"/>
                        </label>
                    {/if}
                    <input type="radio" name="delivery" value="{$item.id}" id="dlv_{$item.id}"
                           {if ($order.use_addr<=0 && $item.id==7) || ($order.use_addr>0 && $item.id==3)} checked="checked"{/if}{if $something_wrong} disabled="disabled"{/if}>
                    <span class="back"></span>
                </div>
                <div class="info col-sm-10">
                    <div class="line">
                        <label for="dlv_{$item.id}" class="title h3">{$item.title}</label>
                    </div>
                    <div class="clearfix"></div>
                    {if ($item->getTypeObject()->getOption('address_type') * 1) < 0 && !$something_wrong}
                        <div class="addressBlock">
                            <div class="h4">Выберите пункт выдачи заказа</div>
                        </div>
	                    <div class="additionalInfo">{$item->getAddittionalHtml()}</div>
                        {if $sd_choosed}
                        	<span class="text-capitalize">{$order.order_extra.delivery.region_id_to|lower}</span><br>
	                        {$order.order_extra.delivery.sd_html|replace:'&lt;':'<'|replace:'&gt;':'>'|replace:'&quot;':'"'}
                        {/if}
                    {else}
                        <div class="addressBlock{if $order->getDelivery->id && $order->getDelivery->id != $item.id || !$order->getDelivery->id && $item.address_type < 0} hide{/if}">
                            <div class="addressText">
                                {$order->getAddress()->getLineView()}
                            </div>
                            <div class="addressLink">
                                <a class="spacing" href="{$router->getUrl('shop-front-checkout', ['Act' => 'address'])}">Изменить адрес</a>
                            </div>
                        </div>
                    {/if}
                    <div class="descr">{$item.description}</div>
                </div>
                <div class="priceWrap col-sm-3">
                    <div id="scost_{$item.id}" class="price">
                        {if $something_wrong}
                            <span class="text-danger">{$something_wrong}</span>
                        {else}
                            <span class="text-muted">{$order->getDeliveryExtraText($item)}</span>
                            <span class="text-success">{$item->getDeliveryCostText($order)}</span>
                        {/if}
                    </div>
                </div>
            </li>
        {/foreach}
        <li class="row sumRow">
			<div class="col-xs-10 col-xs-offset-8">
				<h3>
					<a class="collapsed"
						role="button"
						data-toggle="collapse"
						data-target=".deliveryItem.collapse"
						data-change="Скрыть">
						<span class="change">Показать</span> другие способы доставки
					</a>
				</h3>
			</div>
        </li>
    </ul>
    <div class="row">
        <div class="buttons col-sm-24 text-center">
            <button type="submit" class="button cornered">Далее</button>
        </div>
    </div>
</form>
