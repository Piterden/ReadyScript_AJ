<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<link type="text/css" href="{$mod_css}commoditycheck.css" media="all" rel="stylesheet">
<body>             

{assign var=delivery value=$order->getDelivery()}
{assign var=address value=$order->getAddress()}
{assign var=cart value=$order->getCart()}
{assign var=order_data value=$cart->getOrderData(true, false)}
{assign var=order_data_unformatted value=$cart->getOrderData(false, false)}
{assign var=products value=$cart->getProductItems()}
{assign var=user value=$order->getUser()}

    <div class="centerColumn">    
        
        <table class="headTable">
            <tr>
                <td style="width:50%">
                    <img src="{$CONFIG.__logo->getUrl(200,100, 'xy')}">
                </td>
                <td class="aright" style="width:50%">
                    {if !empty($CONFIG.firm_name)}
                        {$CONFIG.firm_name}<br/>
                    {/if}
                    {if !empty($CONFIG.firm_inn)}
                        ИНН {$CONFIG.firm_inn}<br/>
                    {/if}
                    {if !empty($CONFIG.firm_kpp)}
                        КПП {$CONFIG.firm_kpp}<br/>
                    {/if}
                    {if !empty($CONFIG.admin_phone)}
                        тел. {$CONFIG.admin_phone}<br/>
                    {/if}
                    {if !empty($SITE.admin_email)}
                        e-mail: {$CONFIG.admin_email}<br/>
                    {/if}
                    {if !empty($SITE.full_title)}
                        сайт: {$SITE.full_title}
                    {/if}
                       
                </td>
            </tr>
        </table>

        <p class="acenter h1">Товарный чек №{$order.order_num} от {$order.dateof|dateformat:"d.m.Y"}</p>

        <table class="topTable" style="width:100%" cellpadding="3" cellspacing="0">
            <tr>
                <td>
                   №  
                </td>
                <td>
                   Наименование 
                </td>
                <td>
                   Кол-во 
                </td>
                <td>
                   Вес 
                </td>
                <td>
                   Цена 
                </td>
                <td>
                   Сумма 
                </td>
            </tr>
            {if !empty($products)}
                {$m=0}
                {foreach $order_data.items as $item}
                    {$product=$item.product}
                    {$m=$m+1}
                    <tr>
                        <td>
                           {$m} 
                        </td>
                        <td>
                           <div>
                             {$item.cartitem.title} 
                           </div>
                           {if !empty($item.cartitem.model)}<div>Модель: {$item.cartitem.model}</div>{/if}                           
                           <div>
                             <b>Артикул: {$item.cartitem.barcode}</b> 
                           </div>
                        </td>
                        <td align="right">
                           {$item.cartitem.amount} 
                        </td>
                        <td align="right">
                           {$item.cartitem.single_weight}
                        </td>
                        <td align="right">
                           {$item.single_cost}  
                        </td>
                        <td align="right">
                           {$item.total} 
                        </td>
                    </tr>
                {/foreach}
                {foreach from=$order_data.other key=n item=item}
                <tr>
                    <td colspan="5">{$item.cartitem.title}</td>
                    <td align="right">{if $item.total>0}{$item.total}{/if}</td>
                </tr>
                {/foreach}
            {/if}
            <tr>
                <td colspan="5" align="right">
                   <b>ИТОГО:</b>
                </td>
                <td align="right">
                   <b>{$order_data.total_cost}</b> 
                </td>
            </tr>
        </table>      
        
        {foreach from=$cartdata.taxes item=tax}
            {$tax.tax->getTitle()} {$tax.cost} <br/>           
        {/foreach}
        
        {static_call var=total_cost_string callback=['\RS\Helper\Tools','priceToString'] params=[$order_data_unformatted.total_cost]}
        <p class="aright itogo"><b>{$total_cost_string}</b></p>
        <p>Товар Покупателем осмотрен, комплектация проверена. К внешнему виду и комплектации претензий не имею.</p>
        {if $item.discount>0}<div class="discount">скидка {$item.discount}</div>{/if}
        <p class="pt40">Отпустил __________________/___________________</p>
        <p class="pt40">Покупатель ________________/___________________</p>
          
    </div>
 </body>
 </html>