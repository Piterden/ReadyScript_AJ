<div class="widget-astable">
    <ul class="widget-tabs">
        <li{if $filter=='active'} class="act"{/if}><a data-update-url="{adminUrl mod_controller="shop-widget-lastorders" filter="active"}" class="call-update">{t}незавершенные{/t}</a></li>
        <li{if $filter=='all'} class="act"{/if}><a data-update-url="{adminUrl mod_controller="shop-widget-lastorders" filter="all"}" class="call-update">{t}все{/t}</a></li>
    </ul>
    {if count($orders)}
    <table class="wtable">
        <thead>
            <tr>
                <th class="statusth">№</th>
                <th>Дата</th>
                <th>Сумма</th>
            </tr>
        </thead>
        <tbody class="overable">
            {foreach from=$orders item=order}
            {$status=$order->getStatus()}
            <tr onclick="window.open('{adminUrl mod_controller="shop-orderctrl" do="edit" id=$order.id}', '_blank')">
                <td class="status" style="background:{$status->bgcolor}" title="{$status->title}">{$order.order_num}</td>
                <td class="date">{$order.dateof|dateformat:"@date"} <span class="sep">|</span> <span class="time">{$order.dateof|dateformat:"@time"}</span></td>
                <td class="total">{$order->getTotalPrice()}</td>
            </tr>
            {/foreach}
        </tbody>
    </table>
    {else}
    <div class="empty-widget">
        {t}Нет ни одного заказа{/t}
    </div>
    {/if}
    {include file="%SYSTEM%/admin/widget/paginator.tpl"}
</div>