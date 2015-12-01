<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<link type="text/css" href="{$mod_css}orderform.css" media="all" rel="stylesheet">
<body>
{include file="%shop%/printform/head.tpl"}

{assign var=delivery value=$order->getDelivery()}
{assign var=address value=$order->getAddress()}
{assign var=cart value=$order->getCart()}
{assign var=order_data value=$cart->getOrderData(true, false)}
{assign var=products value=$cart->getProductItems()}
{assign var=user value=$order->getUser()}

{assign var=hl value=["n","hl"]}
    <h1>Заказ №{$order.order_num} от {$order.dateof}</h1>
    <div class="floatbox" style="margin-bottom:20px">
        <div class="o-leftcol {if !$order.delivery && !$order.payment}width100pr{/if}">
            <div class="bordered">
                <h3>Покупатель</h3>
                <table class="order-table">
                        <tr class="{cycle values=$hl name="user"}">
                            <td class="otitle">
                                Фамилия Имя Отчество:
                            </td>
                            <td>
                                {$user.surname} {$user.name} {$user.midname} {if $user.id}({$user.id}){/if}
                                {if $user.is_company}<div class="company_info">{$user.company}, ИНН: {$user.company_inn}</div>{/if}
                            </td>
                        </tr>
                        <tr class="{cycle values=$hl name="user"}">
                            <td class="otitle">
                                Пол:
                            </td>
                            <td>{$user.__sex->textView()}</td>
                        </tr>
                        <tr class="{cycle values=$hl name="user"}">
                            <td class="otitle">Телефон:</td>
                            <td>{$user.phone}</td>
                        </tr>
                        <tr class="{cycle values=$hl name="user"}">
                            <td class="otitle">E-mail:</td>
                            <td>{$user.e_mail}</td>
                        </tr>
                        {foreach from=$user->getUserFields() item=item name=uf}
                        <tr class="{cycle values=$hl name="user"}">
                            <td class="otitle">{$item.title}</td>
                            <td>{$item.current_val}</td>
                        </tr>                
                        {/foreach}
                </table>
            </div>

            <br>
            <div class="bordered">
                <h3>Информация о заказе</h3>
                    <table class="order-table">
                        <tr class="{cycle values=$hl name="order"}">
                            <td class="otitle">
                                Номер
                            </td>
                            <td>{$order.order_num}</td>
                        </tr>
                        <tr class="{cycle values=$hl name="order"}">
                            <td class="otitle">
                                Дата оформления
                            </td>
                            <td>{$order.dateof}</td>
                        </tr>
                        <tr class="{cycle values=$hl name="order"}">
                            <td class="otitle">
                                IP
                            </td>
                            <td>{$order.ip}</td>
                        </tr>
                        <tr class="status-bar {cycle values=$hl name="order"}">
                            <td class="otitle">
                                Статус:
                            </td>
                            <td height="20"><strong id="status_text">{$order->getStatus()->title}</strong>
                            </td>
                        </tr>
                        <tr class="status-bar {cycle values=$hl name="order"}">
                            <td class="otitle">
                                Заказ оформлен в валюте:
                            </td>
                            <td>{$order.currency}</td>
                        </tr>
                        <tr class="status-bar {cycle values=$hl name="order"}">
                            <td class="otitle">
                                Комментарий к заказу:
                            </td>
                            <td>{$order.comments}</td>
                        </tr>                        
                        {foreach from=$order->getExtraInfo() item=item}
                        <tr class="status-bar {cycle values=$hl name="order"}">
                            <td class="otitle">
                                {$item.title}:
                            </td>
                            <td>{$item.value}</td>
                        </tr>                         
                        {/foreach}                        
                        {assign var=fm value=$order->getFieldsManager()}
                        {foreach from=$fm->getStructure() item=item}
                            <tr class="{cycle values=$hl name="order"}">
                                <td class="otitle">
                                    {$item.title}
                                </td>
                                <td>{$item.current_val}</td>
                            </tr>
                        {/foreach}
                    </table>
            </div>        
            <br>
            <div><strong>Комментарий администратора</strong> (не отображается у покупателя): {$order.admin_comments}</div>
            
        </div> <!-- leftcol -->

        <div class="o-rightcol">
            <div class="padd">
            {if $order.delivery}
                <div class="bordered">
                    <h3>Доставка</h3>
                    <table class="order-table delivery-params">
                            <tr class="{cycle values=$hl name="delivery"}">
                                <td width="20" class="otitle">
                                    Тип
                                </td>
                                <td class="d_title">{$delivery.title}</td>
                            </tr>
                            <tr class="{cycle values=$hl name="delivery"}">
                                <td class="otitle">
                                    Индекс
                                </td>
                                <td class="d_zipcode">{$address.zipcode}</td>
                            </tr>
                            <tr class="{cycle values=$hl name="delivery"}">
                                <td class="otitle">Страна</td>
                                <td class="d_country">{$address.country}</td>
                            </tr>
                            <tr class="{cycle values=$hl name="delivery"}">
                                <td class="otitle">Край/область</td>
                                <td class="d_region">{$address.region}</td>
                            </tr>
                            <tr class="{cycle values=$hl name="delivery"}">
                                <td class="otitle">Город</td>
                                <td class="d_city">{$address.city}</td>
                            </tr>
                            <tr class="{cycle values=$hl name="delivery"}">
                                <td class="otitle">Адрес</td>
                                <td class="d_address">{$address.address}</td>
                            </tr>
                    </table>            
                </div>
                <br>
            {/if}
            {if $order.payment}
                {assign var=pay value=$order->getPayment()}
                <div class="bordered">
                    <h3>Оплата</h3>
                    <table class="order-table">
                            <tr class="{cycle values=$hl name="payment"}">
                                <td width="20" class="otitle">
                                    Тип
                                </td>
                                <td>{$pay.title}</td>
                            </tr>
                            <tr class="{cycle values=$hl name="payment"}">
                                <td class="otitle">
                                    Заказ оплачен?
                                </td>
                                <td>
                                    {if $order.is_payed}Да{else}Нет{/if}
                                </td>
                            </tr>                    
                    </table>
                </div>
                <br>        
            {/if}
            </div>
        </div> <!-- right col -->
        
    </div> <!-- -->
    
    <table class="pr-table">
        <thead>
        <tr>
            <th></th>
            <th>Наименование</th>
            <th>Код</th>
            <th>Вес</th>
            <th>Цена</th>
            <th>Кол-во</th>
            <th>Стоимость</th>
        </tr>
        </thead>
        <tbody>
            {foreach from=$order_data.items key=n item=item}
            {assign var=product value=$products[$n].product}
            <tr data-n="{$n}" class="item">
                <td>
                    <img src="{$product->getMainImage(36,36, 'xy')}">
                </td>
                <td>
                    <b>{$item.cartitem.title}</b>
                    <br>
                    {if !empty($item.cartitem.model)}Модель: {$item.cartitem.model}{/if}
                    {if $product.multioffers.use}
                        <div class="parameters">
                            
                               {assign var=multioffers_values value=unserialize($item.cartitem.multioffers)} 
                                {foreach $product.multioffers.levels as $level}
                                    {foreach $level.values as $value}
                                        {if $value.val_str == $multioffers_values[$level.prop_id].value}   
                                            <div class="offer_subinfo"> 
                                              {if $level.title}{$level.title}{else}{$level.prop_title}{/if} : {$value.val_str}
                                            </div>
                                        {/if}
                                    {/foreach}
                                {/foreach}
                            
                        </div>
                    {/if}
                </td>
                <td>{$item.cartitem.barcode}</td>
                <td>{$item.cartitem.single_weight}</td>
                <td>{$item.single_cost}</td>
                <td>{$item.cartitem.amount}</td>
                <td>
                    <span class="cost">{$item.total}</span>
                    {if $item.discount>0}<div class="discount">скидка {$item.discount}</div>{/if}
                </td>
            </tr>
            {/foreach}
        </tbody>
        <tbody class="additems">
            {foreach from=$order_data.other key=n item=item}
            <tr>
                <td colspan="6">{$item.cartitem.title}</td>
                <td>{if $item.total>0}{$item.total}{/if}</td>
            </tr>
            {/foreach}
        </tbody>
        <tfoot>
            <tr class="last">
                <td colspan="3" class="tools"></td>
                <td class="total">Вес: <span class="total_weight">{$order_data.total_weight}</span></td>
                <td></td>
                <td></td>
                <td class="total">
                    Итого: <span class="summary">{$order_data.total_cost}</span>
                </td>
            </tr>
        </tfoot>
     </table>
     <br><br>
     <label><strong>Текст для покупателя</strong> (данный текст будет виден покупателю на странице просмотра заказа)</label>:
     {$order.user_text}
 </body>
 </html>