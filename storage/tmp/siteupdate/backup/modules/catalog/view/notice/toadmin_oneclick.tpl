<p>Уважаемый, администратор! На сайте {$url->getDomainStr()} хотят купить в 1 клик товар:</p>

{assign var=product value=$data->oneclick.product}
{assign var=offers_info value=$data->oneclick.offer_fields}

<p>{$product.title}</p>

<p><a href="{$product->getUrl(true)}">Перейти к товару</a></p>

<h3>Контакты заказчика</h3>
<p>Имя заказчика: {$data->oneclick.name}</p>
<p>Телефон: {$data->oneclick.phone}</p>

<h3>Заказан товар</h3>
<table cellpadding="5" border="1" bordercolor="#969696" style="border-collapse:collapse; border:1px solid #969696">
    <thead>
        <tr>
            <th>ID</th>
            <th>Наименование</th>
            {if !empty($offers_info.offer) || !empty($offers_info.multioffer)}
                <th>Комплектация</th>  
            {/if}
            <th>Код</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><a href="{$router->getAdminUrl('edit',["id" => $product.id], 'catalog-ctrl', true)}">{$product.id}</a></td>
            <td><a href="{$router->getAdminUrl('edit',["id" => $product.id], 'catalog-ctrl', true)}">{$product.title}</a></td>
            {if !empty($offers_info.offer)}
                <h3>Сведения о комплектации</h3>
                <td><a href="{$router->getAdminUrl('edit',["id" => $product.id], 'catalog-ctrl', true)}">{$offers_info.offer}</a></td>
            {elseif !empty($offers_info.multioffer)}
                <h3>Сведения о комплектации</h3>
                <td>
                {foreach $offers_info.multioffer as $offer}
                    {$offer}<br/>
                {/foreach}
                </td>     
            {/if}
            <td>{$product.barcode}</td>
        </tr>
    </tbody>
</table>




{if $data->oneclick.ext_fields}
<h3>Дополнительные сведения</h3>
    <table cellpadding="5" border="1" bordercolor="#969696" style="border-collapse:collapse; border:1px solid #969696">
       <tbody>
          {foreach from=$data->oneclick.ext_fields item=field}  
              <tr>
                 <td><b>{$field.title}</b></td>
                 <td>{$field.current_val}</td>
              </tr>
          {/foreach}
       </tbody>
    </table>
{/if}


<p>Автоматическая рассылка {$url->getDomainStr()}.</p>