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
        {foreach from=$values item=data}
            {assign var=field value=$data.field}
            {assign var=value value=$data.value}
            <tr>
                <td> 
                   {$field.title}
                </td>
                <td class="feedback_result_value"> 
                   {if $data.field.show_type=='file'}
                      {if empty($value)}
                         Файл не загружен
                      {else}
                         <a href="{$value.real_file_name}">Ссылка на файл</a>
                      {/if}
                   {else} 
                      {$value}
                   {/if}
                </td>
            </tr>
        {/foreach}
    </table>
{/if}