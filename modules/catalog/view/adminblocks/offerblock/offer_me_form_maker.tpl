{$groups=$prop->getGroups(false, $switch)}
<div class="virtual-form multiedit" data-has-validation="true" data-action="{$router->getAdminUrl(false, ['odo' => 'offerMultiEdit'], 'catalog-block-offerblock')}">
    {literal}
    <div class="me_info">
        Выбрано элементов: <strong>{$param.sel_count}</strong>
    </div>    
    {foreach $param.hidden_fields as $key=>$val}
    <input type="hidden" name="{$key}" value="{$val}">
    {/foreach}
    {/literal}
    <div class="crud-form-error"></div>    
    <input type="hidden" name="offer_id" value="{literal}{$elem.id}{/literal}">
    <input type="hidden" name="product_id" value="{literal}{$elem.product_id}{/literal}">
    <table class="table-inline-edit">
        {foreach from=$groups key=i item=data}
            {foreach from=$data.items key=name item=item}
                {literal}
                <tr class="editrow">
                    <td class="ochk" width="20">
                        <input title="{t}Отметьте, чтобы применить изменения по этому полю{/t}" type="checkbox" class="doedit" name="doedit[]" value="{$elem.__{/literal}{$name}{literal}->getName()}" {if in_array($elem.__{/literal}{$name}{literal}->getName(), $param.doedit)}checked{/if}></td>
                    <td class="key">{$elem.__{/literal}{$name}{literal}->getTitle()}</td>
                    <td><div class="multi_edit_rightcol coveron"><div class="cover"></div>{include file=$elem.__{/literal}{$name}{literal}->getRenderTemplate(true) field=$elem.__{/literal}{$name}{literal}}</div></td>
                </tr>{/literal}
            {/foreach}
        {/foreach}
            <tr>
                <td></td>
                <td class="key"></td>
                <td><a class="button save virtual-submit">Сохранить</a>
                    <a class="button cancel">Отмена</a>
                </td>
            </tr>
    </table>
</div>