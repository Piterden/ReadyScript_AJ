{foreach from=$properties item=item name="plist"}
    {if $item.is_my }
        <tr class="property-item" data-property-id="{$item.id}" data-is-my="1">
            <td class="item-title">
                <input type="hidden" value="{$item.id}" name="prop[{$item.id}][id]" class="h-id">
                <input type="hidden" value="1" name="prop[{$item.id}][is_my]" class="h-product_id">
                <input type="hidden" value="{$item.xml_id}" name="prop[{$item.id}][xml_id]" class="h-xml_id">
                {$item.title}{if !empty($item.unit)}, {$item.unit}{/if}
            </td>
            <td class="item-info">
                <span class="hint help-icon" title="Тип: {$item.__type->textView()}">?</span>
            </td>
            <td class="item-useval"></td>
            <td class="item-val">
               {$item->valView()}
            </td>
            <td class="item-public">
                {if $owner_type == 'group'}
                <input type="checkbox" name="prop[{$item.id}][public]" value="1" class="h-public" title="Отображать в поиске на сайте" {if !empty($item.public)}checked{/if}>
                {/if}
            </td>
            <td class="item-tools">
                <a title="Редактировать параметры характеристики" class="p-edit"></a>
                <a title="Удалить характеристику" class="p-del" href="JavaScript:;">удалить</a>
            </td>
        </tr>
    {else}
        <tr class="property-item" data-property-id="{$item.id}" data-is-my="0">
            <td class="item-title">
                <input type="hidden" value="{$item.id}" name="prop[{$item.id}][id]" class="h-id">
                <input type="hidden" value="0" name="prop[{$item.id}][is_my]" class="h-group_id">
                <input type="hidden" value="{$item.xml_id}" name="prop[{$item.id}][xml_id]" class="h-xml_id">
                {$item.title}{if !empty($item.unit)}, {$item.unit}{/if}
            </td>
            <td class="item-info">
                <span class="hint help-icon" title="Тип: {$item.__type->textView()}">?</span>
            </td>        
            <td class="item-useval">
                <input type="checkbox" value="1" name="prop[{$item.id}][usevalue]" class="h-useval" {if $item.useval}checked{/if} title="Отметье, чтобы задать персональное значение, иначе будет использоваться значение категории товара">
            </td>
            <td class="item-val">
                {$item->valView()}
            </td>
            <td class="item-public">
                {if $owner_type == 'group'}
                <input type="checkbox" name="prop[{$item.id}][public]" value="1"  class="h-val-linked" title="Отображать в поиске на сайте" {if !empty($item.public)}checked{/if}>
                {/if}
            </td>
            <td class="item-tools">
                <a title="Редактировать параметры характеристики" class="p-edit"></a>
            </td>
        </tr>
    {/if}
{/foreach}