<div class="widget-astable">
    <ul class="widget-tabs">
        <li{if $filter=='open'} class="act"{/if}><a data-update-url="{adminUrl mod_controller="shop-widget-reservation" filter="open"}" class="call-update">{t}Новые{/t}</a></li>
        <li{if $filter=='close'} class="act"{/if}><a data-update-url="{adminUrl mod_controller="shop-widget-reservation" filter="close"}" class="call-update">{t}Закрытые{/t}</a></li>
    </ul>
    {if count($list)}
        <table class="wtable">
            <thead>
                <tr>
                    <th class="statusth">№</th>
                    <th>Дата</th>
                    <th>Товар</th>
                </tr>
            </thead>
            <tbody class="overable">
                {foreach from=$list item=item}
                    <tr class="crud-edit" data-crud-options='{ "updateThis": true }' data-url="{adminUrl mod_controller="shop-reservationctrl" do="edit" id=$item.id}">
                        <td class="date">{$item.id}</td>
                        <td class="date">{$item.dateof|dateformat:"@date"} <span class="sep">|</span> <span class="time">{$item.dateof|dateformat:"@time"}</span></td>
                        <td class="total">
                            {$item.product_title}<br/>{$item.phone}
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    {else}
        <div class="empty-widget">
            {t}Нет ни одной предзаказа{/t}
        </div>
    {/if}
    {include file="%SYSTEM%/admin/widget/paginator.tpl"}
</div>