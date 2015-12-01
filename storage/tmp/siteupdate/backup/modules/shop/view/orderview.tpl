{if !$refresh_mode}
{addcss file="{$mod_css}orderview.css?v=2" basepath="root"}
{addcss file="common/jquery.lightbox.packed.css" basepath="common"}
{addcss file="{$catalog_folders.mod_css}selectproduct.css" basepath="root"}
{addjs file="jquery.lightbox.min.js"}
{addjs file="{$catalog_folders.mod_js}selectproduct.js" basepath="root"}
{addjs file="jquery.userselect.js" basepath="common"}
{addjs file="{$mod_js}jquery.orderedit.js" basepath="root"}
<form id="orderForm" class="crud-form" method="post" action="{urlmake}">
{/if}
{assign var=catalog_config value=ConfigLoader::byModule('catalog')} 
{assign var=delivery value=$elem->getDelivery()}
{assign var=address value=$elem->getAddress()}
{assign var=cart value=$elem->getCart()}

{assign var=order_data value=$cart->getOrderData(true, false)}
{assign var=products value=$cart->getProductItems()}

{assign var=hl value=["n","hl"]}                              
    <input type="hidden" name="order_id" value="{$elem.id}">
    <input type="hidden" name="user_id" value="{$elem.user_id|default:0}">
    <input type="hidden" name="delivery" value="{$elem.delivery|default:0}">
    <input type="hidden" name="use_addr" value="{$elem.use_addr|default:0}">
    <input type="hidden" name="address[zipcode]" value="{$address.zipcode}">
    <input type="hidden" name="address[country]" value="{$address.country}">
    <input type="hidden" name="address[region]" value="{$address.region}">
    <input type="hidden" name="address[city]" value="{$address.city}">
    <input type="hidden" name="address[address]" value="{$address.address}">
    <input type="hidden" name="address[region_id]" value="{$address.region_id}">
    <input type="hidden" name="address[country_id]" value="{$address.country_id}">
    <input type="hidden" name="user_delivery_cost" value="{$elem.user_delivery_cost}">
    <input type="hidden" name="payment" value="{$elem.payment|default:0}">
    <input type="hidden" name="status" id="status" value="{$elem.status}">
    {if $elem.id>0}
        <input type="hidden" name="show_delivery_buttons" id="showDeliveryButtons" value="{$show_delivery_buttons|default:1}"/>
    {/if}

    <div class="status-bar">
        <div class="change-status-text">Переключить статус:</div>
        <ul>
            {foreach from=$status_list item=item}
            {if (!is_array($item) || !empty($item))}
                <li class="top{if is_array($item)} node{/if}{if ((is_array($item) && !in_array($elem.status, $default_statuses)) || $item.id == $elem.status)} act{/if}">
                    {if !is_array($item)}
                    <a href="JavaScript:;" data-id="{$item.id}" style="background:{$item.bgcolor}">{$item.title}</a>
                    {else}
                        <span class="a other">Другой статус</span>
                        <ul class="sublist">
                            {foreach from=$item item=subitem}
                            <li><a href="JavaScript:;" data-id="{$subitem.id}">{$subitem.title}</a></li>
                            {/foreach}
                        </ul>
                    {/if}
                </li>
            {/if}
            {/foreach}
        </ul>
    </div>
    <br style="clear:both">

    <div class="floatbox" style="margin-bottom:20px">
        <div class="o-leftcol">
            <div class="bordered userBlock">
                <h3>Покупатель</h3>                
                <div id="userBlockWrapper">
                    {$user=$elem->getUser()}
                    {include file="%shop%/form/order/user.tpl" user=$user router=$router order=$elem user_num_of_order=$user_num_of_order}
                </div>
            </div>

            {if $elem.id>0}
                <br>
                <div class="bordered">    
                    <h3>Информация о заказе</h3>
                        <table class="order-table">
                            <tr class="{cycle values=$hl name="order"}">
                                <td class="otitle">
                                    Номер
                                </td>
                                <td>{$elem.order_num}</td>
                            </tr>
                            <tr class="{cycle values=$hl name="order"}">
                                <td class="otitle">
                                    Дата оформления
                                </td>
                                <td>{$elem.dateof}</td>
                            </tr>
                            <tr class="{cycle values=$hl name="order"}">
                                <td class="otitle">
                                    IP
                                </td>
                                <td>{$elem.ip}</td>
                            </tr>
                            <tr class="status-bar {cycle values=$hl name="order"}">
                                <td class="otitle">
                                    Статус:
                                </td>
                                <td height="20"><strong id="status_text">{$elem->getStatus()->title}</strong>
                                </td>
                            </tr>
                            <tr class="{cycle values=$hl name="order"}">
                                <td class="otitle">
                                    Заказ оформлен в валюте:
                                </td>
                                <td>{$elem.currency}</td>
                            </tr>
                            <tr class="{cycle values=$hl name="order"}">
                                <td class="otitle">
                                    Комментарий к заказу:
                                </td>
                                <td>{$elem.comments}</td>
                            </tr>                        
                            {foreach from=$elem->getExtraInfo() item=item}
                            <tr class="{cycle values=$hl name="order"}">
                                <td class="otitle">
                                    {$item.title}:
                                </td>
                                <td>{$item.value}</td>
                            </tr>                         
                            {/foreach}                        
                            {assign var=fm value=$elem->getFieldsManager()}
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
            {/if}      
            <br>
            <div><strong>Комментарий администратора</strong> (не отображается у покупателя)</div>
            {$elem.__admin_comments->formView()}
            <br>
            <br>
            <input type="checkbox" name="notify_user" value="1" id="notify_user" checked >
            <label for="notify_user">Уведомить пользователя об изменениях в заказе.</label>
            
        </div> <!-- leftcol -->

        <div class="o-rightcol">
            <div class="padd">
            <div id="documentsBlockWrapper">
                {if $elem.id>0}
                    <div class="bordered">
                        <h3>Документы</h3>

                        <table class="order-table">
                            {foreach from=$elem->getPrintForms() key=id item=print_form}
                                <tr>
                                    <td width="20"><input type="checkbox" id="op_{$id}" value="{adminUrl do=printForm order_id=$elem.id type=$id}" class="printdoc"></td>
                                    <td><label for="op_{$id}">{$print_form->getTitle()}</label></td>
                                </tr>
                            {/foreach}
                        </table>
                        <button type="button" id="printOrder">Печать</button>
                    </div>
                    <br> 
                {/if}
            </div>
                    
            <div id="deliveryBlockWrapper" class="bordered">
                {include file="%shop%/form/order/delivery.tpl" delivery=$delivery address=$address elem=$elem warehouse_list=$warehouse_list}
            </div>    
            <br>
            
            <div id="paymentBlockWrapper" class="bordered">
                {$pay=$elem->getPayment()}
                {include file="%shop%/form/order/payment.tpl" pay=$pay elem=$elem}
            </div>
            <br>        
            </div>
        </div> <!-- right col -->
        
    </div> <!-- -->

    {if $elem.id>0 && !$elem->canEdit()}
        <div class="notice-box no-padd">
            <div class="notice-bg">
                Редактирование списка товаров невозможно, так как были удалены некоторые элементы заказа                        
            </div>    
        </div>
        <br>
    {/if}
    
    <table class="pr-table">
        <thead>
        <tr>
            <th class="chk" style="text-align:center" width="20">
                <input type="checkbox" data-name="chk[]" class="chk_head select-page" alt="">
            </th>
            <th></th>
            <th>Наименование</th>
            <th>Код</th>
            <th>Вес {if !empty($catalog_config.default_weight_unit)}({$catalog_config.default_weight_unit}){/if}</th>
            <th>Цена</th>
            <th>Кол-во</th>
            <th>Стоимость</th>
        </tr>
        </thead>       
        <tbody class="ordersEdit">
            {if !empty($order_data.items)}
                {foreach from=$order_data.items key=n item=item}
                {assign var=product value=$products[$n].product}
                <tr data-n="{$n}" class="item">
                    <td class="chk">
                        <input type="checkbox" name="chk[]" value="{$n}" {if !$elem->canEdit()}disabled{/if}>
                        <input type="hidden" name="items[{$n}][uniq]" value="{$n}">
                        <input type="hidden" name="items[{$n}][entity_id]" value="{$item.cartitem.entity_id}">
                        <input type="hidden" name="items[{$n}][type]" value="{$item.cartitem.type}">
                        <input type="hidden" name="items[{$n}][single_weight]" value="{$item.cartitem.single_weight}">
                    </td>
                    <td>
                        {if $product->hasImage()}
                            <a href="{$product->getMainImage(800, 600, 'xy')}" rel="lightbox-products" data-title="{$item.cartitem.title}"><img src="{$product->getMainImage(36,36, 'xy')}"></a>
                        {else}
                            <img src="{$product->getMainImage(36,36, 'xy')}">
                        {/if}
                    </td>
                    <td>
                        {if $product.id}
                        <a href="{$product->getUrl()}" target="_blank" class="title">{$item.cartitem.title}</a>
                        {else}
                            {$item.cartitem.title}
                        {/if}
                        <br>
                        {if !empty($item.cartitem.model)}Модель: {$item.cartitem.model}{/if}
                        {if $product.multioffers.use && $elem->canEdit()}
                            {assign var=multioffers_values value=unserialize($item.cartitem.multioffers)}
                            <div>
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
                            <a class="show-change-offer">[изменить]</a>
                            <br>
                            
                            <div class="multiOffers hidden">
                                
                                
                                {foreach $product.multioffers.levels as $level}
                                    {if !empty($level.values)}
                                        
                                       
                                        <div class="title">{if $level.title}{$level.title}{else}{$level.prop_title}{/if}</div>
                                        <select name="items[{$n}][multioffers][{$level.prop_id}]" class="product-multioffer " data-url="{adminUrl do="getOfferPrice" product_id=$product.id}" data-prop-title="{if $level.title}{$level.title}{else}{$level.prop_title}{/if}">
                                            {foreach $level.values as $value}
                                                <option value="{$value.val_str}" {if $value.val_str == $multioffers_values[$level.prop_id].value}selected="selected"{/if}>{$value.val_str}</option>   
                                            {/foreach}
                                        </select>
                                    {/if}
                                    
                                {/foreach}
                                
                                {if $product->isOffersUse()}
                                    {* Комплектации к многомерным комлектациям *}
                                    
                                    <select name="items[{$n}][offer]" class="product-offers hidden">
                                        {foreach from=$product.offers.items item=offer key=key}
                                            <option value="{$offer.sortn}" id="offer_{$n}_{$key}" class="hidden_offers" {if $offer.sortn == $item.cartitem.offer}selected="selected"{/if} {if $catalog_config.use_offer_unit}data-unit="{$product.offers.items[$key]->getUnit()->stitle}"{/if} data-info='{$offer->getPropertiesJson()}' data-num="{$offer.num}">{$offer.title}</option>
                                        {/foreach}
                                    </select>
                                    
                                    {* Комплектации к многомерным комлектациям *}
                                    
                                    <select class="product-offer-cost hidden">{*Сюда будут вставлены цены комплектации*}</select>
                                    <input type="button" value="OK" class="apply-cost-btn hidden"/> 
                                {/if}
                            </div>
     
                        {elseif $product->isOffersUse() && $elem->canEdit()}
                            <a class="show-change-offer">[изменить]</a>
                            <br>
                            <select name="items[{$n}][offer]" class="product-offer hidden" data-url="{adminUrl do="getOfferPrice" product_id=$product.id}">
                            {foreach from=$product.offers.items key=key item=offer}
                                <option value="{$offer.sortn}" {if $offer.sortn == $item.cartitem.offer}selected="selected"{/if} {if $catalog_config.use_offer_unit}data-unit="{$product.offers.items[$key]->getUnit()->stitle}"{/if}>{$offer.title}</option>
                            {/foreach}
                            </select>
                            <select class="product-offer-cost hidden">{*Сюда будут вставлены цены комплектации*}</select>
                            <input type="button" value="OK" class="apply-cost-btn hidden"/> 
                        {/if}
                    </td>
                    <td>{$item.cartitem.barcode}</td>
                    <td>{$item.cartitem.single_weight}</td>
                    <td><input type="text" name="items[{$n}][single_cost]" class="invalidate single_cost" value="{$item.single_cost_noformat}" size="10" {if !$elem->canEdit()}disabled{/if}></td>
                    <td>
                        <input type="text" name="items[{$n}][amount]" class="invalidate num" value="{$item.cartitem.amount}" size="4" data-product-id="{$product.id}" {if !$elem->canEdit()}disabled{/if}>
                        {if $catalog_config.use_offer_unit}
                            <span class="unit">
                                {$item.cartitem.data.unit}
                            </span>
                        {/if}
                    </td>
                    <td>
                        <span class="cost">{$item.total}</span>
                        {if $item.discount>0}<div class="discount">скидка {$item.discount}</div>{/if}
                    </td>
                </tr>
                {/foreach}
            {else}
                <tr>
                    <td colspan="8" align="center">Добавьте товары к заказу</td>
                </tr>    
            {/if}
        </tbody>
    
        <tbody class="additems">  
            {foreach from=$order_data.other key=n item=item}
            <tr>
                {if $item.cartitem.type=='coupon'}
                <td class="chk">
                    <input type="checkbox" name="chk[]" value="{$n}" {if !$elem->canEdit()}disabled{/if}>
                    <input type="hidden" name="items[{$n}][uniq]" value="{$n}" class="coupon">
                    <input type="hidden" name="items[{$n}][type]" value="coupon">
                    <input type="hidden" name="items[{$n}][entity_id]" value="{$item.cartitem.entity_id}">
                    <input type="hidden" name="items[{$n}][title]" value="{$item.cartitem.title}">
                </td>
                {/if}
                
                <td colspan="{if $item.cartitem.type=='coupon'}6{else}7{/if}">{$item.cartitem.title}</td>
                <td>{if $item.total>0}{$item.total}{/if}</td>
            </tr>
            {/foreach}
        </tbody>
        
        <tfoot>
            <tr class="last">                           
                <td colspan="4" class="tools">
                
                    {if ($elem.id<0) || $elem->canEdit()}
                        <a class="tool remove" title="{t}Удалить выбранное{/t}"></a>
                        <a class="tool addcoupon" title="{t}Добавить купон на скидку{/t}"></a>
                        <a class="tool addproduct" title="{t}Добавить товар{/t}"></a>
                    {else}
                        <div>
                            <span class="tool remove disabled"></span>
                            <span class="tool addcoupon disabled"></span>
                            <span class="tool addproduct disabled"></span>
                        </div>
                    {/if}
                    
                    {* Сюда будут вставлены элементы через "Добавить купон" и "Добавить товар" *}
                    <div class="added-items"></div>
                    
                </td>
                <td class="total">Вес: <span class="total_weight">{$order_data.total_weight}</span> {if !empty($catalog_config.default_weight_unit)}({$catalog_config.default_weight_unit}){/if}</td>
                <td></td>
                <td></td>
                <td class="total">
                    Итого: <span class="summary">{$order_data.total_cost}</span>
                    <a class="refresh" onclick="$.orderEdit('refresh')">пересчитать</a>
                </td>
            </tr>
        </tfoot>
     </table>
     
     {*  Блок-контейнер для инициализации диалога добавления товара  *}
     
     <div class="product-group-container hide-group-cb hidden" data-urls='{ "getChild": "{adminUrl mod_controller="catalog-dialog" do="getChildCategory"}", "getProducts": "{adminUrl mod_controller="catalog-dialog" do="getProducts"}", "getDialog": "{adminUrl mod_controller="catalog-dialog" do=false}" }'>
        <a href="JavaScript:;" class="select-button"></a><br>
        <div class="input-container"></div>
     </div>           
     <br><br>

     <div class="collapse-block{if $elem.user_text} open{/if}">
        <div class="collapse-title"><i class="icon"></i><strong>Текст для покупателя</strong> (будет виден покупателю на странице просмотра заказа)</div>
        <div class="collapse-content">
            {$elem.__user_text->formView()}
        </div>
     </div>
          
     {* Здесь отображаются поля с контекстом видимости footer *}
     {$order_footer_fields}
          
{if !$refresh_mode}
</form>
{/if}

