{addjs file="flot/excanvas.js" basepath="common" before="<!--[if lte IE 8]>" after="<![endif]-->"}
{addjs file="flot/jquery.flot.min.js" basepath="common"}
{addjs file="flot/jquery.flot.resize.js" basepath="common" waitbefore=true}
{addjs file="flot/jquery.flot.pie.js" basepath="common" waitbefore=true}
{addjs file="{$mod_js}orderstatuses.js" basepath="root" waitbefore=true}

<div class="order-statuses">
    {if $total}
    <div id="orderStatusesGraph" class="graph" style="height:300px"></div>
    <div style="background: #efefef; padding:10px;">
        <table width="100%">
            <tr align="center" style="font-weight:bold">
                <td width="33%">Всего</td>
                <td width="33%">Открыто</td>
                <td width="33%">Завершено</td>
            </tr>
            <tr align="center">
                <td>{$total}</td>
                <td>{$inwork}</td>
                <td>{$finished}</td>
            </tr>            
        </table>
    </div>
    <script>
        $.allReady(function() {
            var data = {$json_data};
            initOrderStatusesWidget(data);
        });
    </script>    
    {else}
        <div class="empty-widget">
            {t}Нет ни одного заказа{/t}
        </div>
    {/if}
</div>