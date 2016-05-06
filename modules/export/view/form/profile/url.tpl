{if $elem->id}
    <b><a href="{$elem->getExportUrl()}">{$elem->getExportUrl()}</a></b>
{else}
    Адрес будет доступен после сохранения профиля
{/if}