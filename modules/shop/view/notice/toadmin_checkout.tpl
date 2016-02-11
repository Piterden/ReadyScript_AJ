{assign var=order value=$data->order}
{assign var=delivery value=$order->getDelivery()}
{assign var=address value=$order->getAddress()}
{assign var=cart value=$order->getCart()}
{assign var=order_data value=$cart->getOrderData(true, false)}
{assign var=products value=$cart->getProductItems()}
{assign var=user value=$order->getUser()}
{assign var=pay value=$order->getPayment()}
{assign var=hl value=["n","hl"]}
<style type="text/css">
.order-table {
    border-collapse:collapse;
    border:1px solid #aaa;
}

.order-table td {
    padding:3px;
    border:1px solid #aaa;
}
</style>
Уважаемый, администратор! На сайте {$url->getDomainStr()} оформлен заказ.<br>
Номер заказа: <a href="{$router->getAdminUrl('edit', ["id" => $order.id], 'shop-orderctrl', true)}"><strong>{$order.order_num}</strong></a> от <strong>{$order.dateof|dateformat:"@date @time:@sec"}</strong>

<h3 style="margin:10px 0;">Покупатель</h3>
<table class="order-table">
        {if $user.is_company}
            <tr class="{cycle values=$hl name="user"}">
                <td class="otitle">
                    Наименование компании:
                </td>
                <td>{$user.company}</td>
            </tr>    
            <tr class="{cycle values=$hl name="user"}">
                <td class="otitle">
                    ИНН компании:
                </td>
                <td>{$user.company_inn}</td>
            </tr>   
        {/if}
        <tr class="{cycle values=$hl name="user"}">
            <td class="otitle">
                Фамилия Имя Отчество:
            </td>
            <td>{$user.surname} {$user.name} {$user.midname} ({if $order.user_id>0}{$user.id}{else}Без регистрации{/if})</td>
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

<h3 style="margin:10px 0;">Информация о заказе</h3>
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

{if $order.delivery}
    <h3 style="margin:10px 0;">Доставка</h3>
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
            {if $delivery->getTypeObject()->isMyselfDelivery()}
            {$warehouse=$data->order->getWarehouse()} 
            <tr class="{cycle values=$hl name="delivery"}">
                <td class="otitle">Склад самовывоза</td>
                <td class="d_address">"{$warehouse.title}" (Адрес: {$warehouse.adress})</td>
            </tr>
            {/if}
    </table>    
{/if}


{if $order.payment}
    <h3 style="margin:10px 0;">Оплата</h3>
    <table class="order-table">
            <tr class="{cycle values=$hl name="payment"}">
                <td width="20" class="otitle">
                    Тип
                </td>
                <td>{$pay.title}</td>
            </tr>
            {if $pay->hasDocs()}
            <tr class="{cycle values=$hl name="payment"}">
                <td width="20" class="otitle">
                    Документы на оплату
                </td>
                <td>
                    {assign var=type_object value=$pay->getTypeObject()}
                    {foreach from=$type_object->getDocsName() key=key item=doc}
                    <a href="{$type_object->getDocUrl($key, true)}" target="_blank">{$doc.title}</a><br>
                    {/foreach}
                </td>
            </tr>
            {/if}
            <tr class="{cycle values=$hl name="payment"}">
                <td class="otitle">
                    Заказ оплачен?
                </td>
                <td>
                    {if $order.is_payed}Да{else}Нет{/if}
                </td>
            </tr>                    
    </table>  
{/if}
<br><br>
<table cellpadding="5" border="1" bordercolor="#969696" style="border-collapse:collapse; border:1px solid #969696">
    <thead>
    <tr>
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
                    {$item.cartitem.title}
                    <br>
                    {if !empty($item.cartitem.model)}Модель: {$item.cartitem.model}{/if}
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
                <td colspan="5">{$item.cartitem.title}</td>
                <td>{if $item.total>0}{$item.total}{/if}</td>
            </tr>
        {/foreach}
    </tbody>
    <tfoot>
        <tr class="last">
            <td colspan="2" class="tools"></td>
            <td class="total">Вес: <span class="total_weight">{$order_data.total_weight}</span></td>
            <td></td>
            <td></td>
            <td class="total">
                Итого: <span class="summary">{$order_data.total_cost}</span>
            </td>
        </tr>
    </tfoot>
 </table>

 