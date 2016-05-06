{extends file="%install%/wrap.tpl"}
{block name="content"}
<h2>{t}Проверка параметров сервера{/t}</h2>

<table class="check-options">
    <tr class="first">
        <td class="term">PHP информация<br>
            <span class="expected">Нажмите на ссылку, чтобы просмотреть все параметры PHP на вашем сервере</span>
        </td>
        <td width="100"></td>
        <td valign="bottom" width="150" class="conclusion"><a href="{$router->getUrl('install', ['Act' => 'phpinfo'])}" target="_blank" class="phpinfo">показать</a></td>
    </tr>
    <tr>
        <td class="term">Версия PHP<br>
            <span class="expected">Требуется версия PHP не ниже {$check.php_version.need}</span>
        </td>
        <td>{$check.php_version.server}</td>
        <td class="conclusion{if !$check.php_version.decision} fail{/if}">{if $check.php_version.decision}соответствует{else}не соответствует{/if}</td>
    </tr>
    <tr>
        <td class="term">MySQL<br>
            <span class="expected">Версия MySQL должна быть {$check.mysql_support.need} или выше</span>
        </td>
        <td></td>
        <td class="conclusion{if !$check.mysql_support.decision} fail{/if}">{if $check.mysql_support.decision}соответствует{else}не соответствует{/if}</td>
    </tr>
    <tr>
        <td class="term">SafeMode<br>
            <span class="expected">Защищенный режим должен быть отключен</span>
        </td>
        <td></td>
        <td class="conclusion{if !$check.safe_mode.decision} fail{/if}">{if $check.safe_mode.decision}соответствует{else}не соответствует{/if}</td>
    </tr>
    <tr>
        <td class="term">Модуль GD<br>
            <span class="expected">В системе должен быть установлен модуль GD для корректной работы с изображениями</span>
        </td>
        <td></td>
        <td class="conclusion{if !$check.gd.decision} fail{/if}">{if $check.gd.decision}соответствует{else}не соответствует{/if}</td>
    </tr>    
    <tr>
        <td class="term">ZIP архивы<br>
            <span class="expected">PHP должен поддерживать функции работы с zip архивами (Модуль ZIP)</span>
        </td>
        <td></td>
        <td class="conclusion{if !$check.zip.decision} fail{/if}">{if $check.zip.decision}соответствует{else}не соответствует{/if}</td>
    </tr>        
    <tr>
        <td class="term">Модуль MbString<br>
            <span class="expected">Модуль mbstring должен быть включен с параметром mbstring.func_overload = 0 или 1, для работы UTF-8</span>
        </td>
        <td></td>
        <td class="conclusion{if !$check.mbstring.decision} fail{/if}">{if $check.mbstring.decision}соответствует{else}не соответствует{/if}</td>
    </tr>            
    <tr>
        <td class="term">Модуль MCrypt<br>
            <span class="expected">Для корректной работы системы необходим модуль шифрования mcrypt с поддержкой алгоритма twofish</span>
        </td>
        <td></td>
        <td class="conclusion{if !$check.mcrypt.decision} fail{/if}">{if $check.mcrypt.decision}соответствует{else}не соответствует{/if}</td>
    </tr>                
    <tr>
        <td class="term">Загрузка файлов<br>
            <span class="expected">Необходимо, чтобы загрузка файлов была включена</span>
        </td>
        <td></td>
        <td class="conclusion{if !$check.upload_files.decision} fail{/if}">{if $check.upload_files.decision}соответствует{else}не соответствует{/if}</td>
    </tr>
    <tr>
        <td class="term">Поддержка CURL<br>
            <span class="expected">Необходимо для выполнения некоторых операций, например соединение с платежными системами.</span>
        </td>
        <td></td>
        <td class="conclusion{if !$check.curl.decision} fail{/if}">{if $check.curl.decision}соответствует{else}не соответствует{/if}</td>
    </tr>
</table>
<div class="hr"></div>
<h2>{t}Проверка прав доступа к файлам и папкам{/t}</h2>

<table class="check-options">
    {foreach from=$check.write_rights key=path item=data name="wr"}
    <tr {if $smarty.foreach.wr.first}class="first"{/if}>
        <td class="term">{$path} {if $path =='/'}(Корневая папка сайта){/if}<br>
            <span class="expected">{$data.description}</span>
        </td>
        <td width="150" class="conclusion{if !$data.decision} fail{/if}">{if $data.decision}запись возможна{else}нет прав на запись{/if}</td>        
    </tr>
    {/foreach}
</table>
<div class="button-line mtop30">
    <!--<a href="{$router->getUrl('install', ['Act' => 'step3'])}" class="next">далее</a>-->
    {if !$check.can_continue}    
        <a href="{$router->getUrl('install', ['step' => '2'])}" class="next">проверить еще раз</a>
        <span class="page-error"><i></i>{t}Продолжение установки невозможно, необходимо чтобы все параметры соответствовали требуемым{/t}</span>    
    {else}
        <a href="{$router->getUrl('install', ['step' => '3'])}" class="next">далее</a>
    {/if}
</div>
{/block}