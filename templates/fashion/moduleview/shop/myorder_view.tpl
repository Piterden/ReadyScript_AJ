{assign var=catalog_config value=ConfigLoader::byModule('catalog')}
{addjs file="jcarousel/jquery.jcarousel.min.js"}
{addjs file="myorder_view.js"}
{assign var=cart value=$order->getCart()}
{assign var=products value=$cart->getProductItems()}
{assign var=order_data value=$cart->getOrderData()}

<div class="orderViewCaption oh">
    <div class="fleft">
        <p class="orderNumber">Заказ №{$order.order_num}</p>
        <span class="orderStatus" style="background: {$order->getStatus()->bgcolor}">{$order->getStatus()->title}</span>
    </div>
    <div class="fright">
        {if $order->getPayment()->hasDocs()}
            {$type_object=$order->getPayment()->getTypeObject()}
            {foreach $type_object->getDocsName() as $key=>$doc}
                <a href="{$type_object->getDocUrl($key)}" class="button" target="_blank">{$doc.title}</a>
            {/foreach}
        {/if}
        {if $order->canOnlinePay()}
            <a href="{$order->getOnlinePayUrl()}" class="colorButton">оплатить</a><br>
        {/if}
    </div>
</div>
<div class="orderViewProducts">
    <div class="scrollWrapper">
        <ul class="products">
            {foreach $order_data.items as $key=>$item}
                {$product=$products[$key].product}
                {$multioffer_titles=$item.cartitem->getMultiOfferTitles()}
                <li>
                    {$main_image=$product->getMainImage()}
                    {if $product.id>0}
                        <a href="{$product->getUrl()}" class="image"><img src="{$main_image->getUrl(226, 236, 'xy')}" alt="{$main_image.title|default:"{$product.title}"}"/></a>
                        <a href="{$product->getUrl()}" class="title">{$item.cartitem.title}</a>
                    {else}
                        <span class="image"><img src="{$main_image->getUrl(226, 236, 'xy')}" alt="{$main_image.title|default:"{$product.title}"}"/></span>
                        <span class="title">{$item.cartitem.title}</span>
                    {/if}                            
                    <div class="info">
                        {if !empty($multioffer_titles)}
                            {foreach $multioffer_titles as $multioffer}
                                <p>{$multioffer.title} - <span class="value">{$multioffer.value}</span></p>
                            {/foreach}
                        {/if}                
                        <p>Количество - 
                        
                        <span class="amount">{$item.cartitem.amount}</span>
                        {if $catalog_config.use_offer_unit}
                            {$item.cartitem.data.unit}
                        {/if}
                        </p>
                        <p>Цена - <span class="price">{$item.cost} {$order.currency_stitle}</span></p>
                        {if $item.discount >0}
                        <p>Скидка - <span class="price">{$item.discount} {$order.currency_stitle}</span></p>
                        {/if}
                    </div>
                </li>
            {/foreach}
        </ul>
    </div>
    <a href="#" class="control prev"></a>
    <a href="#" class="control next"></a>
</div>
<table class="orderInfo">
    <tr>
        <td class="key">Дата заказа</td>
        <td class="value">{$order.dateof|dateformat}</td>
    </tr>
    {if $order.delivery}
        <tr>
            <td class="key">Тип доставки</td>
            <td class="value">{$order->getDelivery()->title}</td>
        </tr>    
    {/if}            
    <tr>
        <td class="key">Адрес получения</td>
        <td class="value">{$order->getAddress()->getLineView()}</td>
    </tr>                
    {if $order->contact_person}
    <tr>
        <td class="key">Контактное лицо</td>
        <td class="value">{$order->contact_person}</td>
    </tr>                
    {/if}
    {$fm=$order->getFieldsManager()}
    {foreach $fm->getStructure() as $item}
        <tr>
            <td class="key">{$item.title}</td>
            <td class="value">{$item.current_val}</td>
        </tr>
    {/foreach}    
    {if $files=$order->getFiles()}
    <tr>
        <td class="key">Файлы</td>
        <td class="value">            
        {assign var=type_object value=$order->getPayment()->getTypeObject()}
        {foreach $files as $file}
            <a href="{$file->getUrl()}" class="underline" target="_blank">{$file.name}</a>{if !$file@last},{/if}
        {/foreach}
        </td>
    </tr>
    {/if}        
    {foreach $order_data.other as $item}    
    {if $item.cartitem.type != 'coupon'}
    <tr>
        <td class="key">{$item.cartitem.title}</td>
        <td class="value">{if $item.total >0}{$item.total}{/if}</td>
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
        <td class="value">{$order_data.total_cost}</td>
    </tr>                                
</table>
{if !empty($order.user_text)}
<div class="userText">
    {$order.user_text}
</div>
{/if}