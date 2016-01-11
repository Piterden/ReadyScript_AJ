<p>Уважаемый, администратор! На сайте {$url->getDomainStr()} оформлен предварительный заказ на товар.</p>

<p>Номер предварительного заказа: <a href="{$router->getAdminUrl('edit', ["id" => $data->reserve.id], 'shop-reservationctrl', true)}"><strong>{$data->reserve.id}</strong></a> от <strong>{$data->reserve.dateof|date_format:"%d.%m.%Y %H:%M:%S"}</strong></p>

{assign var=product value=$data->reserve->getProduct()}
<h3>Контакты заказчика</h3>
Телефон: {$data->reserve.phone}<br>
E-mail: {$data->reserve.email}

<h3>Заказан товар</h3>
<table cellpadding="5" border="1" bordercolor="#969696" style="border-collapse:collapse; border:1px solid #969696">
    <thead>
        <tr>
            <th>ID</th>
            <th>Наименование</th>
            {if $data->reserve.offer}
               <th>Комплектации</th> 
            {elseif !empty($data->reserve.multioffer)}
               <th>Комплектации</th> 
            {/if}
            <th>Код</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><a href="{$router->getAdminUrl('edit',["id" => $product.id], 'catalog-ctrl', true)}">{$product.id}</a></td>
            <td><a href="{$router->getAdminUrl('edit',["id" => $product.id], 'catalog-ctrl', true)}">{$product.title}</a></td>
            {if $data->reserve.offer}
                <td><a href="{$router->getAdminUrl('edit',["id" => $product.id], 'catalog-ctrl', true)}">{$data->reserve.offer}</a></td>
            {elseif !empty($data->reserve.multioffer)}
                {assign var=multioffers value=unserialize($data->reserve.multioffer)}
                <td>
                {foreach $multioffers as $offer}
                    {$offer}<br/>
                {/foreach}
                </td>     
            {/if}
            <td>{$product.barcode}</td>
        </tr>
    </tbody>
</table>


<p>Автоматическая рассылка {$url->getDomainStr()}.</p>