<div class="widget-astable">
    <ul class="widget-tabs">
        <li{if $filter=='new'} class="act"{/if}><a data-update-url="{adminUrl mod_controller="catalog-widget-oneclick" filter="new"}" class="call-update">{t}Новые{/t}</a></li>
        <li{if $filter=='viewed'} class="act"{/if}><a data-update-url="{adminUrl mod_controller="catalog-widget-oneclick" filter="viewed"}" class="call-update">{t}Закрытые{/t}</a></li>
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
                    <tr class="crud-edit" data-crud-options='{ "updateThis": true }' data-url="{adminUrl mod_controller="catalog-oneclickctrl" do="edit" id=$item.id}">
                        <td class="date">{$item.id}</td>
                        <td class="date">{$item.dateof|dateformat:"@date"} <span class="sep">|</span> <span class="time">{$item.dateof|dateformat:"@time"}</span></td>
                        {$data=$item->tableDataUnserialized()}
                        <td class="total">{$data.product.title}<br/>
                        {$data.phone}</td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    {else}
        <div class="empty-widget">
            {t}Нет ни одной покупки в 1 клик{/t}
        </div>
    {/if}
    {include file="%SYSTEM%/admin/widget/paginator.tpl"}
</div>