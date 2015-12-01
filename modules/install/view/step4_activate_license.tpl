{extends file="%install%/wrap.tpl"}
{block name="content"}
<h2>{t}Активация лицензии{/t}</h2>
{foreach from=$license->getNonFormErrors() item=error}
<p class="top-error">{$error}</p>
{/foreach}

<form method="POST" id="activation-form">
    <input type="submit" style="display:none">
    <table class="config-table">
        <tr class="first">
            <td class="key">Лицензия<br></td>
            <td class="value"><input type="text" disabled value="{$license.license}">
            </td>
        </tr>
        <tr>
            <td class="key">Контактное лицо<br>
            <span class="key-help">Например: Сидоров Петр Иванович</span>
            </td>
            <td class="value">
                {$license->getPropertyView('person', [], ['form' => true])}
                <span class="field-error" data-field="person" data-error="{$license->getErrorsByForm('person', ',')}"></span>
            </td>
        </tr>    
        <tr>
            <td class="key">Наименование компании<br>
            <span class="key-help">Например: ООО "Радуга"</span>
            </td>
            <td class="value">
                {$license->getPropertyView('company_name', [], ['form' => true])}
                <span class="field-error" data-field="company_name" data-error="{$license->getErrorsByForm('company_name', ',')}"></span>
            </td>
        </tr>        
        <tr>
            <td class="key">ИНН<br>
            <span class="key-help">10 или 12 цифр</span>
            </td>
            <td class="value">{$license->getPropertyView('inn', [], ['form' => true])}
            <span class="field-error" data-field="inn" data-error="{$license->getErrorsByForm('inn', ',')}">
            </td>
        </tr>
        <tr>
            <td class="key">Телефон</td>
            <td class="value">{$license->getPropertyView('phone', [], ['form' => true])}
            <span class="field-error" data-field="phone" data-error="{$license->getErrorsByForm('phone', ',')}">
            </td>
        </tr>
        <tr>
            <td class="key">E-mail</td>
            <td class="value">{$license->getPropertyView('email', [], ['form' => true])}
            <span class="field-error" data-field="email" data-error="{$license->getErrorsByForm('email', ',')}">
            </td>
        </tr>
        <tr>
            <td class="key">Домен<br>
            <span class="key-help">Основной домен, по которому будет доступен магазин</span>
            </td>
            <td class="value">{$license->getPropertyView('domain', [], ['form' => true])}
            <span class="field-error" data-field="domain" data-error="{$license->getErrorsByForm('domain', ',')}">
            </td>
        </tr>
        <tr>
            <td class="key">Проверить отклик магазина<br>
                <span class="key-help">Снимите этот флажок, если точно уверены в корректности написания домена</span>
            </td>
            <td class="value">{$license->getPropertyView('check_domain', [], ['form' => true])}
            </td>
        </tr>        
    </table>
</form>

<div class="button-line mtop30">
    <a class="next">далее</a>
    {if $license->hasError()}<span class="page-error fright"><i></i>{t}Некоторые поля заполнены некорректно{/t}</span>{/if}
    <a href="{$router->getUrl('install', ['step' => '4'])}" class="prev">назад</a>
</div>
{/block}
{block name="root"}

<div class="progress-window">
    <div id="progress-run">
        <h2>Установка системы</h2>
        <div class="progress">
            <div class="rails">
                <div class="border"></div>
                <div class="bar" style="width:0"><div class="percent"><span class="percent-value">0%</span><i></i></div></div>
            </div>
            <div class="status">
                {t}Подготовка к установке{/t}
            </div>
        </div>
    </div>

    <div id="progress-error">
        <h2>{t}Во время установки произошли ошибки{/t}</h2>
        <ul class="error-list">
            <li>
                <div class="field"><span class="module-title"></span><i class="cor"></i></div>
                <div class="text"></div>
            </li>
        </ul>
        <div class="buttons">
            <a class="button close-window">Закрыть</a>
        </div>
    </div>
</div>

{/block}