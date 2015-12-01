{if $dynamics_arr}
    {addcss file="{$mod_css}sellchart.css?v=3" basepath="root"}
    {addjs file="flot/excanvas.js" basepath="common" before="<!--[if lte IE 8]>" after="<![endif]-->"}
    {addjs file="flot/jquery.flot.min.js" basepath="common"}
    {addjs file="flot/jquery.flot.resize.min.js" basepath="common"}
    {addjs file="{$mod_js}jquery.sellchart.js?v=3" basepath="root"}
    <div class="sellWidget">
        <ul class="widget-tabs">
            <li {if $range=='year'}class="act"{/if}><a data-update-url="{adminUrl mod_controller="shop-widget-sellchart" sellchart_range="year" sellchart_orders="{$orders}" sellchart_show_type="{$show_type}"}" class="call-update">По годам</a></li>
            <li {if $range=='month'}class="act"{/if}><a data-update-url="{adminUrl mod_controller="shop-widget-sellchart" sellchart_range="month" sellchart_orders="{$orders}" sellchart_show_type="{$show_type}"}" class="call-update">Последний месяц</a></li>
        </ul>
        <div class="statusSelector">
            <div class="sellShowType">
                {if $show_type == 'num'}
                    <a class="call-update" title="{t}нажмите, чтобы сменить показатель на сумму{/t}" data-update-url="{adminUrl mod_controller="shop-widget-sellchart" sellchart_range="{$range}" sellchart_orders="{$orders}" sellchart_show_type="summ"}">Кол-во</a>
                {else}
                    <a class="call-update" title="{t}нажмите, чтобы сменить показатель на количество{/t}" data-update-url="{adminUrl mod_controller="shop-widget-sellchart" sellchart_range="{$range}" sellchart_orders="{$orders}" sellchart_show_type="num"}">Сумма</a>
                {/if}
            </div>
            <a data-update-url="{adminUrl mod_controller="shop-widget-sellchart" sellchart_range="{$range}" sellchart_orders="success" sellchart_show_type="{$show_type}"}" class="call-update{if $orders=='success'} act{/if}">Завершенные заказы</a>
            <a data-update-url="{adminUrl mod_controller="shop-widget-sellchart" sellchart_range="{$range}" sellchart_orders="all" sellchart_show_type="{$show_type}"}" class="call-update{if $orders=='all'} act{/if}">Все</a>
        </div>
            <div id="sellChart">
                <div id="placeholder" style="height:300px;"></div>
                {if $range == 'year'}
                <div class="year-line-but">
                    <a href="#" onclick="$('.years-list').toggle(); return false;">фильтр</a>
                </div>
                <div class="years-list" style="display:none"></div>
                {/if}
            </div>
            
            {if $range == 'year'}
                <script type="text/javascript">
                    $.allReady(function() {
                        $('#sellChart').sellChart({
                            data: {$dynamics},
                            currency: {$currency},
                            tooltipDate: function(pointData) {
                                var 
                                    months = ['январе','феврале','марте','апреле', 'мае','июне','июле','августе', 'сентябре','октябре','ноябре','декабре'],
                                    pointDate = new Date(pointData.pointDate);
                                
                                return 'в '+months[pointDate.getMonth()]+' '+pointDate.getFullYear();
                                
                            }
                        })
                    });
                </script>        
            {else}
                <script type="text/javascript">
                    $.allReady(function() {
                        $('#sellChart').sellChart({
                            plotOptions: {
                                xaxis: {
                                    minTickSize: [1, "day"],
                                }
                            },
                            data: {$dynamics},
                            currency: {$currency},
                            tooltipDate: function(pointData) {
                                var 
                                    months = ['января','февраля','марта','апреля', 'мая','июня','июля','августа', 'сентября','октября','ноября','декабря'],
                                    pointDate = new Date(pointData.x);
                                
                                return pointDate.getDate()+' '+months[pointDate.getMonth()]+' '+pointDate.getFullYear();
                            }
                        })
                    });
                </script>        
            {/if}
    </div>
{else}
    <div class="empty-widget">
        {t}Нет ни одного заказа{/t}
    </div>
{/if}