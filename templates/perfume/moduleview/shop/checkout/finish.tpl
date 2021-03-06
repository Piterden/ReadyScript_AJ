<div class="formStyle checkoutForm">
    <div class="workArea noTopPadd">
<h3 class="confirm">Спасибо! Ваш заказ успешно оформлен</h3>    
        {if $user.id}            
            <p class="thanks">Следить за изменениями статуса заказа можно в разделе <a href="{$router->getUrl('shop-front-myorders')}" target="_blank">история заказов</a>. 
        {/if}
        Все уведомления об изменениях в данном заказе также будут отправлены на электронную почту покупателя.</p>
        <div class="coInfo">
            {$user=$order->getUser()}
            <h2>Сведения о заказе</h2>
            <div class="border">
                <table>
                    <tr>
                        <td class="key">Заказчик</td>
                        <td>{$user.surname} {$user.name}</td>
                    </tr>
                    <tr>
                        <td class="key">Телефон</td>
                        <td>{$user.phone}</td>
                    </tr>
                    <tr class="preSep">
                        <td class="key">e-mail</td>
                        <td>{$user.e_mail}</td>
                    </tr>
                    {$fmanager=$order->getFieldsManager()}
                    {if $fmanager->notEmpty()}
                        {foreach $fmanager->getStructure() as $field}
                            <tr class="{if $field@first}postSep{/if} {if $field@last}preSep{/if}">
                                <td class="key">{$field.title}</td>
                                <td>{$fmanager->textView($field.alias)}</td>
                            </tr>
                        {/foreach}
                    {/if}
                    {$delivery=$order->getDelivery()}
                    {$address=$order->getAddress()}
                    {$pay=$order->getPayment()}
                    {if $order.delivery}
                        <tr class="postSep">
                            <td class="key">Доставка</td>
                            <td>{$delivery.title}</td>
                        </tr>
                    {/if}
                    <tr>
                        <td class="key">Адрес</td>
                        <td>{$address->getLineView()}</td>
                    </tr>
                    {if $order.payment}
                        <tr>
                            <td class="key">Оплата</td>
                            <td>{$pay.title}</td>
                        </tr>
                    {/if}
                </table>
            </div>

            {if $order->getPayment()->hasDocs()}
            <div class="docs">
                <h2>Документы на оплату</h2>
                <div class="border">
                    <p>Воспользуйтесь следующими документами для оплаты заказа. Эти документы всегда доступны в разделе история заказов.</p>
                    {$type_object=$order->getPayment()->getTypeObject()}
                    {foreach $type_object->getDocsName() as $key => $doc}
                    <div class="download"><a href="{$type_object->getDocUrl($key)}" target="_blank" class="button">{$doc.title}</a></div>
                    {/foreach}
                </div>
            </div>            
            {/if}
            
        </div>            
        
        {assign var=orderdata value=$cart->getOrderData()}
        <div class="coItems">
            <h1>Заказ N {$order.order_num}</h1>
            <p class="orderDate">от {$order.dateof|date_format:"d.m.Y"}</p>
            <table class="themeTable noMobile">
                <thead>
                    <tr>
                        <td>Товар</td>
                        <td>Количество</td>
                        <td class="price">Цена</td>
                    </tr>
                </thead>
                <tbody>
                    {foreach $orderdata.items as $n=>$item}
                    {$orderitem=$item.cartitem}
                    {$barcode=$orderitem.barcode}
                    {$offer_title=$orderitem.model}
                    {$multioffer_titles=$orderitem->getMultiOfferTitles()}
                    <tr>
                        <td>{$orderitem.title}
                            <div class="codeLine">
                                {if $barcode != ''}Артикул:<span class="value">{$barcode}</span><br>{/if}
                                {if $multioffer_titles || $offer_title}
                                    <div class="multioffersWrap">
                                        {foreach $multioffer_titles as $multioffer}
                                            <p class="value">{$multioffer.title} - {$multioffer.value}</p>
                                        {/foreach}
                                        {if !$multioffer_titles}
                                            <p class="value">{$offer_title}</p>
                                        {/if}
                                    </div>
                                {/if}                                
                            </div>
                        </td>
                        <td>
                            {$orderitem.amount} {$orderitem.data.unit}
                        </td>
                        <td class="price">
                            {$item.total}
                            <div class="discount">
                                {if $item.discount>0}
                                скидка {$item.discount}
                                {/if}
                            </div>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
            <br>
            <table class="themeTable noMobile">
                <tbody>
                    {foreach $orderdata.other as $item}
                    <tr>
                        <td>{$item.cartitem.title}</td>
                        <td>{if $item.total != 0}{$item.total}{/if}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
            <div class="summary">
                <span class="text">Итого: </span> 
                <span class="price">{$orderdata.total_cost}</span>
            </div>
        </div>
    </div>
    
    <div class="buttonLine alignRight">
        {if $order->canOnlinePay()}
            <a href="{$order->getOnlinePayUrl()}" class="colorButton">Перейти к оплате</a>
        {else}
            <a href="{$router->getRootUrl()}" class="colorButton">Завершить заказ</a>
        {/if}
    </div>
</div>