{if !$refresh_mode}
{addcss file="{$mod_css}orderview.css" basepath="root"}
{addcss file="common/jquery.lightbox.packed.css" basepath="common"}
{addjs file="jquery.lightbox.min.js"}
{addjs file="{$mod_js}jquery.orderedit.js" basepath="root"}
<form id="orderForm" class="crud-form" method="post" action="{urlmake}">
{/if}
{assign var=delivery value=$elem->getDelivery()}
{assign var=address value=$elem->getAddress()}
{assign var=cart value=$elem->getCart()}
{assign var=order_data value=$cart->getOrderData(true, false)}
{assign var=products value=$cart->getProductItems()}
{assign var=hl value=["n","hl"]}
    <input type="hidden" name="order_id" value="{$elem.id}">
    <input type="hidden" name="delivery" value="{$elem.delivery}">
    <input type="hidden" name="use_addr" value="{$elem.use_addr}">
    <input type="hidden" name="address[zipcode]" value="{$address.zipcode}">
    <input type="hidden" name="address[country]" value="{$address.country}">
    <input type="hidden" name="address[region]" value="{$address.region}">
    <input type="hidden" name="address[city]" value="{$address.city}">
    <input type="hidden" name="address[address]" value="{$address.address}">
    <input type="hidden" name="address[region_id]" value="{$address.region_id}">
    <input type="hidden" name="address[country_id]" value="{$address.country_id}">
    <input type="hidden" name="user_delivery_cost" value="{$elem.user_delivery_cost}">
    <input type="hidden" name="payment" value="{$elem.payment}">
    <input type="hidden" name="status" id="status" value="{$elem.status}">

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
            <div class="bordered">
                <h3>Покупатель</h3>
                {assign var=user value=$elem->getUser()}
                <table class="order-table">
                        <tr class="{cycle values=$hl name="user"}">
                            <td class="otitle">
                                Фамилия Имя Отчество:
                            </td>
                            <td><a href="{adminUrl mod_controller="users-ctrl" do="edit" id=$user.id}" target="_blank">{$user.surname} {$user.name} {$user.midname} ({$user.id})</a></td>
                        </tr>
                        <tr class="{cycle values=$hl name="user"}">
                            <td class="otitle">
                                Пол:
                            </td>
                            <td>{$user.__sex->textView()}</td>
                        </tr>
                        <tr class="{cycle values=$hl name="user"}">
                            <td>Телефон:</td>
                            <td>{$user.phone}</td>
                        </tr>
                        <tr class="{cycle values=$hl name="user"}">
                            <td>E-mail:</td>
                            <td>{$user.e_mail}</td>
                        </tr>
                        {foreach from=$user->getUserFields() item=item name=uf}
                        <tr class="{cycle values=$hl name="user"}">
                            <td>{$item.title}</td>
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
                            <td>{$elem.id}</td>
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
                        <tr class="status-bar {cycle values=$hl name="order"}">
                            <td class="otitle">
                                Заказ оформлен в валюте:
                            </td>
                            <td>{$elem.currency}</td>
                        </tr>
                        <tr class="status-bar {cycle values=$hl name="order"}">
                            <td class="otitle">
                                Комментарий к заказу:
                            </td>
                            <td>{$elem.comments}</td>
                        </tr>                        
                        {foreach from=$elem->getExtraInfo() item=item}
                        <tr class="status-bar {cycle values=$hl name="order"}">
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
            <br>
            <div><strong>Комментарий администратора</strong> (не отображается у покупателя)</div>
            {$elem.__admin_comments->formView()}
            <br>
            <br>
            <input type="checkbox" name="notify_user" value="1" id="notify_user" checked>
            <label for="notify_user">Уведомить пользователя об изменениях в заказе.</label>
            
        </div> <!-- leftcol -->

        <div class="o-rightcol">
            <div class="padd">
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
            <div class="bordered">
                <h3>Доставка <a data-href="{adminUrl do=deliveryDialog order_id=$elem.id}" class="tool-edit" id="editDelivery" title="редактировать"></a></h3>
                <table class="order-table delivery-params">
                        <tr class="{cycle values=$hl name="delivery"}">
                            <td width="20">
                                Тип
                            </td>
                            <td class="d_title">{$delivery.title}</td>
                        </tr>
                        <tr class="{cycle values=$hl name="delivery"}">
                            <td>
                                Индекс
                            </td>
                            <td class="d_zipcode">{$address.zipcode}</td>
                        </tr>
                        <tr class="{cycle values=$hl name="delivery"}">
                            <td>Страна</td>
                            <td class="d_country">{$address.country}</td>
                        </tr>
                        <tr class="{cycle values=$hl name="delivery"}">
                            <td>Край/область</td>
                            <td class="d_region">{$address.region}</td>
                        </tr>
                        <tr class="{cycle values=$hl name="delivery"}">
                            <td>Город</td>
                            <td class="d_city">{$address.city}</td>
                        </tr>
                        <tr class="{cycle values=$hl name="delivery"}">
                            <td>Адрес</td>
                            <td class="d_address">{$address.address}</td>
                        </tr>
                </table>            
            </div>
            <br>
            {assign var=pay value=$elem->getPayment()}
            <div class="bordered">
                <h3>Оплата <a data-href="{adminUrl do=paymentDialog order_id=$elem.id}" class="tool-edit" id="editPayment" title="редактировать"></a></h3>
                <table class="order-table">
                        <tr class="{cycle values=$hl name="payment"}">
                            <td width="20">
                                Тип
                            </td>
                            <td>{$pay.title}</td>
                        </tr>
                        <tr class="{cycle values=$hl name="payment"}">
                            <td>
                                Заказ оплачен?
                            </td>
                            <td>
                                {$elem.__is_payed->formView()}
                            </td>
                        </tr>
                        <tr class="{cycle values=$hl name="payment"}">
                            <td>
                                Документы покупателя
                            </td>
                            <td>
                                {assign var=type_object value=$elem->getPayment()->getTypeObject()}
                                {foreach from=$type_object->getDocsName() key=key item=doc name="docs"}
                                    <a href="{$type_object->getDocUrl($key)}" class="underline" target="_blank">{$doc.title}</a>{if !$smarty.foreach.docs.last},{/if}
                                {/foreach}
                            </td>
                        </tr>                    
                </table>
            </div>
            <br>        
            </div>
        </div> <!-- right col -->
        
    </div> <!-- -->

    {*
    <div class="currency-select">
        Валюта: 
        <select name="use_currency">
            {foreach from=$elem->getAllowCurrencies() key=key item=value}
            <option value="{$key}" {if $use_currency==$key}selected{/if}>{$value}</option>
            {/foreach}
        </select>
    </div>
    *}
    
    <table class="pr-table">
        <thead>
        <tr>
            <th class="chk" style="text-align:center" width="20">
                <input type="checkbox" data-name="chk[]" class="chk_head select-page" alt="">
            </th>
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
                <td class="chk">
                    <input type="checkbox" name="chk[]" value="{$n}">
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
                </td>
                <td>{$item.cartitem.barcode}</td>
                <td>{$item.cartitem.single_weight}</td>
                <td><input type="text" name="items[{$n}][single_cost]" class="invalidate" value="{$item.single_cost_noformat}" size="10"></td>
                <td><input type="text" name="items[{$n}][amount]" class="invalidate" value="{$item.cartitem.amount}" size="4" class="num"></td>
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
                {if $item.cartitem.type=='coupon'}
                <td class="chk">
                    <input type="checkbox" name="chk[]" value="{$n}">
                    <input type="hidden" name="items[{$n}][entity_id]" value="{$item.entity_id}">
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
                    <a class="tool remove" title="{t}Удалить выбранное{/t}"></a>
                    {*
                    <a class="tool addcoupon" title="{t}Добавить купон на скидку{/t}"></a>
                    <a class="tool addproduct" title="{t}Добавить товар{/t}"></a>
                    *}
                </td>
                <td class="total">Вес: <span class="total_weight">{$order_data.total_weight}</span></td>
                <td></td>
                <td></td>
                <td class="total">
                    Итого: <span class="summary">{$order_data.total_cost}</span>
                    <a class="refresh" onclick="$.orderEdit('refresh')">пересчитать</a>
                </td>
            </tr>
        </tfoot>
     </table>
     <br><br>
     <label><strong>Текст для покупателя</strong> (данный текст будет виден покупателю на странице просмотра заказа)</label><br>
     {$elem.__user_text->formView()}
{if !$refresh_mode}
</form>
{/if}