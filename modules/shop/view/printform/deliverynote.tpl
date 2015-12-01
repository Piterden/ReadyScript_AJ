<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<link type="text/css" href="{$mod_css}orderform.css" media="all" rel="stylesheet">
<body>
{include file="%shop%/printform/head.tpl"}
{assign var=user value=$order->getUser()}
{assign var=cart value=$order->getCart()}
{assign var=order_data value=$cart->getOrderData(true, false)}
{assign var=products value=$cart->getProductItems()}

<h1>Лист доставки</h1>
<div class="floatbox">
    <div class="left-49p">
        <div class="bordered">
            <h3>Получатель</h3>
            <table class="order-table">
                    <tr class="{cycle values=$hl name="user"}">
                        <td class="otitle">
                            Контактное лицо:
                        </td>
                        <td>{$order.contact_person|default:$order->getUser()->getFio()}</td>
                    </tr>
                    <tr class="{cycle values=$hl name="user"}">
                        <td class="otitle">
                            Адрес:
                        </td>
                        <td>{$order->getAddress()->getLineView()}</td>
                    </tr>                    
                    <tr class="{cycle values=$hl name="user"}">
                        <td class="otitle">Телефон:</td>
                        <td>{$user.phone}</td>
                    </tr>
            </table>
            
        </div>
    </div>
    <div class="right-49p">
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
                        <tr class="status-bar {cycle values=$hl name="order"}">
                            <td class="otitle">
                                Комментарий к заказу:
                            </td>
                            <td>{$order.comments}</td>
                        </tr>
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
    </div>    
</div>
    <br><br>
    <table class="pr-table">
        <thead>
        <tr>
            <th></th>
            <th>Наименование</th>
            <th>Код</th>
            <th>Кол-во</th>
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
                <td>{$item.cartitem.amount}</td>
            </tr>
            {/foreach}
        </tbody>
     </table>
     <br>
     <div>
        <strong>Товары получены в надлежащем качестве и количестве.</strong>
        <br><br>
        <div class="floatbox">
            <div class="fleft nowrap">Получатель __________________</div>
            <div class="fleft nowrap">Подпись __________________</div>
            <div class="fright nowrap">Дата _______________</div>
        </div>
        
        
     </div>
</body>
</html>