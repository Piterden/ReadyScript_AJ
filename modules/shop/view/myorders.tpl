{if count($order_list)}
<table class="orderList">
    {foreach from=$order_list item=order}
    <tr>
        <td class="date">№ {$order.order_num}<br>{$order.dateof|date_format:"d.m.Y"}</td>
        <td class="products">
            {assign var=cart value=$order->getCart()}
            {assign var=products value=$cart->getProductItems()}
            {assign var=order_data value=$cart->getOrderData()}
            
            {assign var=products_first value=array_slice($products, 0, 5)}
            {assign var=products_more value=array_slice($products, 5)}
            
            
            <ul>
                {foreach from=$products_first item=item}
                    {assign var=multioffer_titles value=$item.cartitem->getMultiOfferTitles()}
                    <li>
                        {$main_image=$item.product->getMainImage()}
                        {if $item.product.id>0}
                            <a href="{$item.product->getUrl()}" class="image"><img src="{$main_image->getUrl(36, 36, 'xy')}" alt="{$main_image.title|default:"{$item.cartitem.title}"}"/></a>
                            <a href="{$item.product->getUrl()}" class="title">{$item.cartitem.title}</a>
                        {else}
                            <span class="image"><img src="{$main_image->getUrl(36, 36, 'xy')}" alt="{$main_image.title|default:"{$item.cartitem.title}"}"/></span>
                            <span class="title">{$item.cartitem.title}</span>
                        {/if}
                        {if $multioffer_titles || $item.cartitem.model}
                            <div class="multioffersWrap">
                                {foreach from=$multioffer_titles item=multioffer}
                                {$multioffer.value}{if !$multioffer@last}, {/if}
                                {/foreach}
                                {if !$multioffer_titles}
                                    {$item.cartitem.model}
                                {/if}
                            </div>
                        {/if}
                    </li>
                {/foreach}
            </ul>
            {if !empty($products_more)}
            <div class="moreItems">
                <a class="expand rs-parent-switcher">показать все...</a>
                <ul class="items">
                    {foreach from=$products_more item=item}
                        {assign var=multioffer_titles value=$item.cartitem->getMultiOfferTitles()}
                        <li>
                            {if $item.product.id>0}
                                <a href="{$item.product->getUrl()}" class="image"><img src="{$item.product->getMainImage(36, 36, 'xy')}"></a>
                                <a href="{$item.product->getUrl()}" class="title">{$item.cartitem.title}</a>
                                {if $multioffer_titles || $item.cartitem.model}
                                    <div class="multioffersWrap">
                                        {foreach from=$multioffer_titles item=multioffer}
                                        {$multioffer.value}{if !$multioffer@last}, {/if}
                                        {/foreach}
                                        {if !$multioffer_titles}
                                            {$item.cartitem.model}
                                        {/if}
                                    </div>
                                {/if}                                
                            {else}
                                <span class="image"><img src="{$item.product->getMainImage(36, 36, 'xy')}"></span>
                                <span class="title">{$item.cartitem.title}</span>
                            {/if}
                        </li>
                    {/foreach}
                </ul>
                <a class="collapse rs-parent-switcher">показать кратко</a>
            </div>            
            {/if}
        </td>
        <td class="price">
            {$order_data.total_cost}
        </td>
        <td class="status">
            <span class="statusItem" style="background: {$order->getStatus()->bgcolor}">{$order->getStatus()->title}</span>
        </td>
        <td class="actions">
            {if $order->getPayment()->hasDocs()}
                {assign var=type_object value=$order->getPayment()->getTypeObject()}
                {foreach from=$type_object->getDocsName() key=key item=doc}
                <a href="{$type_object->getDocUrl($key)}" target="_blank">{$doc.title}</a><br>
                {/foreach}            
            {/if}
            {if $order->canOnlinePay()}
                <a href="{$order->getOnlinePayUrl()}">оплатить</a><br>
            {/if}
            <a href="{$router->getUrl('shop-front-myorderview', ["order_id" => $order.order_num])}" class="more">подробнее</a>
        </td>
    </tr>
    {/foreach}
</table>
{else}
<div class="noData">
    Еще не оформлено ни одного заказа
</div>
{/if}
<br>
{include file="%THEME%/paginator.tpl"}