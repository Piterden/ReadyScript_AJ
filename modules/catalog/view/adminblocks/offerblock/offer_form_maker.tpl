{$groups=$prop->getGroups(false, $switch)}
<div class="virtual-form" data-has-validation="true" data-action="{$router->getAdminUrl(false, ['odo' => 'offerEdit'], 'catalog-block-offerblock')}">
    <div class="crud-form-error"></div>
    <input type="hidden" name="offer_id" value="{literal}{$elem.id}{/literal}">
    <input type="hidden" name="product_id" value="{literal}{$elem.product_id}{/literal}">
    <table class="table-inline-edit">
        {foreach from=$groups key=i item=data}
            {foreach from=$data.items key=name item=item}
                {if !$item->isHidden()}
                {literal}
                <tr>
                    <td class="key">{$elem.__{/literal}{$name}{literal}->getTitle()}</td>
                    <td>{include file=$elem.__{/literal}{$name}{literal}->getRenderTemplate() field=$elem.__{/literal}{$name}{literal}}</td>
                </tr>{/literal}
                {/if}
            {/foreach}
        {/foreach}
            <tr>
                <td class="key"></td>
                <td><a class="button save virtual-submit">Сохранить</a>
                <a class="button cancel">Отмена</a>
                </td>
            </tr>
    </table>
</div>