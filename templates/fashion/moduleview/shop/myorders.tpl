{if count($order_list)}
    <div class="tabs gray ordersTabs">
        <ul class="tabList">
            <li class="act"><a>Заказы</a></li>
        </ul>
        <div class="tab act">
            <table class="themeTable orderTable">
                {foreach $order_list as $order}
                    {$cart=$order->getCart()}
                    {$products=$cart->getProductItems()}
                    {$order_data=$cart->getOrderData()}

                    {$products_first=array_slice($products, 0, 5)}
                    {$products_more=array_slice($products, 5)}            
                    <tr>
                        <td class="number">
                            <div class="info">
                                <a href="{$router->getUrl('shop-front-myorderview', ["order_id" => $order.order_num])}" class="more">№ {$order.order_num}</a>
                                <span class="date">{$order.dateof|date_format:"d.m.Y"}</span>
                            </div>
                            <span class="orderStatus" style="background: {$order->getStatus()->bgcolor}">{$order->getStatus()->title}</span>
                        </td>
                        <td class="items">
                            <ul>
                                {foreach $products_first as $item}
                                    {$multioffer_titles=$item.cartitem->getMultiOfferTitles()}
                                    <li>
                                        {$main_image=$item.product->getMainImage()}
                                        {if $item.product.id>0}
                                            <a href="{$item.product->getUrl()}" class="image"><img src="{$main_image->getUrl(32, 45, 'xy')}" alt="{$main_image.title|default:"{$item.cartitem.title}"}"/></a>
                                            <a href="{$item.product->getUrl()}" class="title">{$item.cartitem.title}</a>
                                        {else}
                                            <span class="image"><img src="{$main_image->getUrl(32, 45, 'xy')}" alt="{$main_image.title|default:"{$item.cartitem.title}"}"/></span>
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
                                        {foreach $products_more as $item}
                                            <li>
                                                {if $item.product.id>0}
                                                    <a href="{$item.product->getUrl()}" class="image"><img src="{$item.product->getMainImage(32, 45, 'xy')}"></a>
                                                    <a href="{$item.product->getUrl()}" class="title">{$item.cartitem.title}</a>
                                                {else}
                                                    <span class="image"><img src="{$item.product->getMainImage(32, 45, 'xy')}"></span>
                                                    <span class="title">{$item.cartitem.title}</span>
                                                {/if}
                                            </li>
                                        {/foreach}
                                    </ul>
                                    <a class="collapse rs-parent-switcher">показать кратко</a>
                                </div>
                            {/if}
                        </td>
                        <td class="price">{$order_data.total_cost}</td>
                        <td class="actions">
                            {if $order->getPayment()->hasDocs()}
                                {assign var=type_object value=$order->getPayment()->getTypeObject()}
                                {foreach $type_object->getDocsName() as $key=>$doc}
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
        </div>
    </div>
{else}
    <div class="noEntity">
        Еще не оформлено ни одного заказа
    </div>
{/if}
{include file="%THEME%/paginator.tpl"}