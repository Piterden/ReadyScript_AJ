{addjs file="{$mod_js}widgetengine.js" basepath="root"}
{addcss file="{$mod_css}widgetstyle.css?v=2" basepath="root"}
<div class="viewport" id="widgets-block" data-widget-urls='{ "widgetList": "{adminUrl do="GetWidgetList"}", "addWidget":"{adminUrl do="ajaxAddWidget"}", "removeWidget":"{adminUrl do="ajaxRemoveWidget"}", "moveWidget": "{adminUrl do="ajaxMoveWidget"}" }'>
    <div class="widget-buttons">
        <a class="addwidget" title="{t}Добавить виджет{/t}"></a>
    </div>

    <div id="noWidgetText" class="{if !$total}visible{/if}">
        <p class="text">Настройте<br><span class="small">свой рабочий стол</span></p>
        <div class="welcome-disk">
            <span class="dynamic">динамика<br>продаж</span>
            <span class="stat">статистика<br>магазина</span>
            <span class="lastviewed">что смотрят на сайте</span>
            <span class="welcome">приветствие</span>
            <span class="lastcomments">последние<br>комментарии</span>
            <span class="orders">недавние заказы</span>
            <span class="support">поддержка клиентов</span>
            <span class="security">безопасность</span>
            <a class="add addwidget">добавить виджет</a>
        </div>
    </div>
    <div id="contentblock-widget">
        <div id="widget-zone">
            {foreach from=$widgetsByCol key=col_name item=widgets}
                <div id="col_{$col_name}" colname="{$col_name}">
                    {foreach from=$widgets item=widget}
                        {moduleinsert name=$widget->getFullClass() id=$widget.id}
                    {/foreach}
                </div>
            {/foreach}    
        </div>
    </div>
</div>