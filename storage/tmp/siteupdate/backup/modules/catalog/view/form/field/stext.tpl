{assign var=values value=$elem->tableDataUnserialized()}

{if !empty($values)} 
    <table class="feedback_result_form">
        <tr>
            <th>
                Поле
            </th>
            <th>
                Значение
            </th>
        </tr>
        <tr>
            <td>
                Имя
            </td>
            <td>
                {$values.name}
            </td>
        </tr>
        <tr>
            <td>
                Телефон
            </td>
            <td>
                {$values.phone}
            </td>
        </tr>
        <tr>
            <td>
                Товар
            </td>
            <td>
                <a href="{adminUrl do="edit" id=$values.product.id mod_controller="catalog-ctrl"}" target="_blank">{$values.product.title}</a>
            </td>
        </tr>
        {* Сведения о товаре *}
        {if !empty($values.ext_fields)}
            {foreach from=$values.ext_fields item=data}
                
                <tr>
                    <td> 
                       {$data.title}
                    </td>
                    <td class="feedback_result_value"> 
                       {$data.current_val}
                    </td>
                </tr>
            {/foreach}
        {/if}
        {* Комплектации *}
        {if !empty($values.offer_fields)}
            {if isset($values.offer_fields.offer)}
                <tr>
                    <td> 
                       Комплектация
                    </td>
                    <td class="feedback_result_value"> 
                       {$values.offer_fields.offer}
                    </td>
                </tr>
            {/if}
            {if isset($values.offer_fields.multioffer)}
                {assign var=multioffers value=implode("<br/>", $values.offer_fields.multioffer)}
                <tr>
                    <td> 
                       Комплектация
                    </td>
                    <td class="feedback_result_value"> 
                       {$multioffers} 
                    </td>
                </tr>
            {/if}
            
        {/if}
        
    </table>
{/if}