{if $elem->id}
    <b><a href="{$elem->getTargetHref()}" target="_blank">Ссылка на объект</a></b>
{else}
    Адрес будет доступен после сохранения
{/if}