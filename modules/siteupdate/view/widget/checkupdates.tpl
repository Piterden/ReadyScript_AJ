{addcss file="{$mod_css}checkupdates.css" basepath="root"}
{addjs file="{$mod_js}checkupdates.js" basepath="root"}

<div class="checkUpdatesWidget" data-checkupdate-url="{adminUrl mod_controller="siteupdate-widget-checkupdates" cudo="checkUpdates"}">
    {if $state=='nolicense'}
        <div class="nolicense">
            <p>{t}Лицензионный ключ не установлен. Получение обновлений недоступно{/t}</p>
            <a class="greenButton" href="{adminUrl do=false mod_controller="main-license"}">{t}установить лицензию{/t}</a>
        </div>
    {elseif $state=='actual'}
        <div class="padd5">
            <table class="frame60 allok">
                <tr>
                    <td class="checkForUpdates">{t}Система обновлена до последней версии{/t}</td>
                </tr>
            </table>
            <table class="frame40 timeleft">
                <tr>
                    <td>
                        <a href="{adminUrl do=false mod_controller="main-license"}"><span class="l1">{t expire=$expire_days}%expire [plural:%expire:день|дня|дней]{/t}</span><br>
                            <span class="l2">{t}срок подписки на обновления{/t}</span></a>
                    </td>                            
                </tr>
            </table>
        </div>
    {elseif $state=='needupdate'}
        <div class="padd5">
            <div class="frame60">
                <p class="updateText checkForUpdates">{t}Доступны новые обновления{/t}</p>
                <table class="needUpdate">
                    <tr>
                        <td class="gotoUpdate">
                            <a href="{adminUrl do=false mod_controller="siteupdate-wizard"}#start" class="hasUpdate"><img src="{$mod_img}/checkupdates.png"><span>выполнить обновление</span></a>
                        </td>
                    </tr>
                </table>
            </div>
            <table class="frame40 timeleft">
                <tr>
                    <td>
                        <a href="{adminUrl do=false mod_controller="main-license"}"><span class="l1">{t expire=$expire_days}%expire [plural:%expire:день|дня|дней]{/t}</span><br>
                            <span class="l2">{t}срок подписки на обновления{/t}</span></a>
                    </td>
                </tr>
            </table>
        </div>
    {elseif $state=='expirelicense'}
        <div class="padd5">
            <table class="frame60">
                <tr>
                    <td><p>{t}Срок доступных обновлений истек. <br>Приобретите лицензию на обновление продукта.{/t}</p>
                        <a class="greenButton" href="{adminUrl do=false mod_controller="main-license"}">{t}приобрести лицензию{/t}</a>
                    </td>
                </tr>
            </table>
            <table class="frame40 timeleft">
                <tr>
                    <td>
                        <a href="{adminUrl do=false mod_controller="main-license"}"><span class="l1">{t}Лицензия истекла{/t}</span></a>
                    </td>
                </tr>
            </table>
        </div>
    {elseif $state=='error'}
        <div class="errors">
            <p class="msg">{$msg}</p>
            <a class="checkForUpdates">{t}повторить попытку{/t}</a>
        </div>
    {else}
        <div class="checking">
            <img src="{$Setup.IMG_PATH}/adminstyle/ajax-loader.gif"><br>
            <p>{t}Идет проверка обновлений{/t}</p>
        </div>
    {/if}
</div>

<script>
    $.allReady(function() {
        $('.checkUpdatesWidget').checkUpdates();
    });
</script>