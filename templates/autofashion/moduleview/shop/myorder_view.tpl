{assign var=catalog_config value=ConfigLoader::byModule('catalog')}
{addjs file="jcarousel/jquery.jcarousel.min.js"}
{addjs file="myorder_view.js"}
{assign var=cart value=$order->getCart()}
{assign var=products value=$cart->getProductItems()}
{assign var=order_data value=$cart->getOrderData()}

<div class="orderTitle col-md-24 text-center">
    <h2>Заказ №{$order.order_num}</h2>
</div>
<div class="orderInfoWrap col-md-14 col-md-offset-6">
    <table class="orderInfo">
        <tr>
            <td class="key">Заказ №{$order.order_num}</td>
            <td class="value">
                <span class="orderCount">{verb item=$products|@count values="позиция,позиции,позиций"}</span>
                <span class="orderStatus" style="background: {$order->getStatus()->bgcolor}">
                    {$order->getStatus()->title}
                </span>
            </td>
        </tr>
        <tr>
            <td class="key">Дата заказа</td>
            <td class="value">{$order.dateof|dateformat}</td>
        </tr>
        <tr>
            <td class="key">Тип доставки</td>
            <td class="value">{$order->getDelivery()->title}</td>
        </tr>
        <tr class="address">
            <td class="key">Адрес получения</td>
            <td class="value">{$order->getAddress()->getLineView()}</td>
        </tr>
        {if $order->contact_person}
            <tr>
                <td class="key">Контактное лицо</td>
                <td class="value">{$order->contact_person}</td>
            </tr>
        {/if}
        <tr>
            <td class="key"></td>
            <td class="value"></td>
        </tr>
        {$fm=$order->getFieldsManager()}
        {foreach $fm->getStructure() as $item}
            <tr>
                <td class="key">{$item.title}</td>
                <td class="value">{$item.current_val}</td>
            </tr>
        {/foreach}
        <pre>
        	{$order_data.other|@print_r}
        </pre>
        {foreach $order_data.other as $item}
            {if $item.cartitem.type != 'coupon'}
                <tr>
                    <td class="key">{$item.cartitem.title}</td>
                    <td class="value">
                        <div class="price">{$item.total}</div>
                    </td>
                </tr>
            {/if}
        {/foreach}
        {if $order->comments}
            <tr>
                <td class="key">Комментарий</td>
                <td class="value">{$order->comments}</td>
            </tr>
        {/if}
        <tr class="summary">
            <td class="key">Итого</td>
            <td class="value">
                <div class="price">{$order_data.total_cost}</div>
            </td>
        </tr>
    </table>
</div>
<div class="actions col-md-4">
    {if $order->getPayment()->hasDocs()}
        {$type_object=$order->getPayment()->getTypeObject()}
        {foreach $type_object->getDocsName() as $key=>$doc}
            <a href="{$type_object->getDocUrl($key)}" class="button" target="_blank">{$doc.title}</a>
        {/foreach}
    {/if}
    {if $order->canOnlinePay()}
        <a href="{$order->getOnlinePayUrl()}" class="colorButton">оплатить</a>
    {/if}
</div>
<ul class="products col-md-24">
    {foreach $order_data.items as $key=>$item}
        {$product=$products[$key].product}
        {$multioffer_titles=$item.cartitem->getMultiOfferTitles()}
        <li class="row">
            <div class="itemWrap">
                {$main_image=$product->getMainImage()}
                <div class="col-md-6 imageWrap text-center">
                    {if $product.id>0}
                        <a href="{$product->getUrl()}" class="image"><img src="{$main_image->getUrl(226, 236, 'xy')}" alt="{$main_image.title|default:"{$product.title}"}"/></a>
                        {else}
                        <span class="image"><img src="{$main_image->getUrl(226, 236, 'xy')}" alt="{$main_image.title|default:"{$product.title}"}"/></span>
                        <span class="title">{$item.cartitem.title}</span>
                    {/if}
                </div>
                <div class="col-md-15 info">
                    <div class="productTitleWrap">
                        {if $item.cartitem.short_description}
                            <a href="{$product->getUrl()}" class="shortDescription">{$item.cartitem.short_description}</a>
                        {/if}
                        <a href="{$product->getUrl()}" class="title">{$item.cartitem.title}</a>
                    </div>
                    <div class="amountWrap">
                        Количество -
                        <span class="amount">{$item.cartitem.amount}</span>
                        {if $catalog_config.use_offer_unit}
                            {$item.cartitem.data.unit}
                        {/if}
                    </div>
                    <div class="offersWrap">
                        {if !empty($multioffer_titles)}
                            {foreach $multioffer_titles as $multioffer}
                                <div class="offer">{$multioffer.title} - <span class="value">{$multioffer.value}</span></div>
                                {/foreach}
                            {/if}
                    </div>
                </div>
                <div class="col-md-3 price">
                    <p>Цена - <span class="price">{$item.cost} {$order.currency_stitle}</span></p>
                    {if $item.discount >0}
                        <p>Скидка - <span class="price">{$item.discount} {$order.currency_stitle}</span></p>
                    {/if}
                </div>
            </div>
        </li>
    {/foreach}
</ul>
{if !empty($order.user_text)}
    <div class="userText">
        {$order.user_text}
    </div>
{/if}
