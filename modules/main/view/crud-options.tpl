{$app->autoloadScripsAjaxBefore()}
<div class="crud-ajax-group">
    {if !$url->isAjax()}
    <div id="content-layout">
        <div class="viewport">
    {/if}
            <div class="titlebox">{$elements.formTitle}</div>
            {if !$url->isAjax()}<div class="sepline sep-top-form"></div>{/if}
            <div class="middlebox">
                <div class="crud-form-error">
                    {if count($elements.formErrors)}
                        <ul class="error-list">
                            {foreach from=$elements.formErrors item=data}
                                <li>
                                    <div class="{$data.class|default:"field"}">{$data.fieldname}<i class="cor"></i></div>
                                    <div class="text">
                                        {foreach from=$data.errors item=error}
                                        {$error}
                                        {/foreach}
                                    </div>
                                </li>
                            {/foreach}
                        </ul>
                    {/if}
                </div>
                <div class="crud-form-success text-success"></div>
                <div class="columns">
                    <div class="tools-column fullheight">
                        <div class="controller_info">
                            <h3>Разработчикам</h3>
                            <ul class="content">
                                <li><a href="{adminUrl mod_controller="main-routes"}" style="text-decoration:underline">Маршруты в системе</a>
                                <div class="tool-descr">Позволяет проверить разработчику, какой маршрут откликается на заданный URL</div>
                                </li>
                                <li><a class="crud-get" href="{adminUrl do="syncDb"}" style="text-decoration:underline">Исправить структуру БД</a>
                                    <div class="tool-descr">Приводит структуру БД в соответствии со всеми ORM объектами в системе</div>
                                </li>
                                <li><a class="crud-get" href="{adminUrl do="testMail"}" style="text-decoration:underline">Проверить отправку писем</a>
                                    <div class="tool-descr">Будет отправлено тестовое сообщение администратору сайта</div>
                                </li>
                                <li><a href="{adminUrl do=false mod_controller="main-blockedip"}" style="text-decoration:underline">Блокировка IP-адресов</a>
                                    <div class="tool-descr">Переход в раздел управления списком заблокированных IP или диапазонов IP</div>
                                </li>
                                <li><a class="crud-get" href="{adminUrl do="unlockCron"}" style="text-decoration:underline">Разблокировать Cron</a>
                                    <div class="tool-descr">Удалить файл блокировки планировщика заданий Cron<br>(/storage/locks/cron)</div>
                                </li>
                            </ul>
                        </div>    
                    
                    </div>
                    <div class="form-column">
                        {$elements.form}
                    </div>
                    <div class="clearboth"></div>
                </div>
            </div>
        {if !$url->isAjax()}
            <div class="footerspace"></div>
        </div> <!-- .viewport -->
    </div> <!-- .content -->
    {/if}
    <div class="bottomToolbar zindex-dlg">
        <div class="viewport">
            <div class="common-column">
                {if isset($elements.bottomToolbar)}
                    {$elements.bottomToolbar->getView()}
                {/if}
            </div>
        </div>
    </div>    
</div>
{$app->autoloadScripsAjaxAfter()}