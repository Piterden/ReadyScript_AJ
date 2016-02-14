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
            {$something_wrong=$item->getTypeObject()->somethingWrong($order)}
            <li class="deliveryItem row{if $item@first} first{/if}" id="delivery_{$item.id}" data-delivery-id="{$item.id}">
                <div class="radio col-sm-8">
                    {if !empty($item.picture)}
                        <label for="dlv_{$item.id}" class="logoService">
                            <img class="" src="{$item.__picture->getUrl(100, 100, 'xy')}" alt="{$item.title}"/>
                        </label>
                    {/if}
                    <input type="radio" name="delivery" value="{$item.id}" id="dlv_{$item.id}"
                           {if ($order.use_addr<=0 && $item.id==7) || ($order.use_addr>0 && $item.id==3)} checked="checked"{/if}
                           {if $something_wrong} disabled="disabled"{/if}>
                    <span class="back"></span>
                </div>
                <div class="info col-sm-10">
                    <div class="line">
                        <label for="dlv_{$item.id}" class="title h3">{$item.title}</label>
                    </div>
                    <div class="clearfix"></div>
                    {if $item.id == 7}
                        <div class="addressBlock">
                            <div class="h4">Выберите пункт выдачи заказа</div>
                        </div>
                    {else}
                        <div class="addressBlock hide">
                            <div class="addressText">
                                {$order->getAddress()->getLineView()}
                            </div>
                            <div class="addressLink">
                                <a class="spacing" href="{$router->getUrl('shop-front-checkout', ['Act' => 'address'])}">Изменить адрес</a>
                            </div>
                        </div>
                    {/if}
                    <div class="descr">{$item.description}</div>
                    <div class="additionalInfo">{$item->getAddittionalHtml()}</div>
                </div>
                <div class="priceWrap col-sm-3">
                    <div id="scost_{$item.id}" class="price">
                        {if $something_wrong}
                            <span style="color:red;">{$something_wrong}</span>
                        {else}
                            <span class="help">{$order->getDeliveryExtraText($item)}</span>
                            {*$order->getDeliveryCostText($item)*}
                            {static_call var=currencyLiter callback=['\Catalog\Model\CurrencyApi', 'getCurrecyLiter']}
                            <span>{$item->getDeliveryCost($order)} {$currencyLiter|replace:'р.':'<i class="fa fa-rub"></i>'}</span>
                        {/if}
                    </div>
                </div>
            </li>
        {/foreach}
    </ul>
    <div class="row">
        <div class="buttons col-sm-24 text-center">
            <button type="submit" class="button cornered">Далее</button>
        </div>
    </div>
</form>
