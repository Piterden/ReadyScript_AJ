{if $elem->id && $elem->user_id}
    {$user=$elem->getUser()}
    <b><a href="{$elem->getUserAdminHref()}" target="_blank">{$user.surname} {$user.name} {$user.midname}</a></b>
{else}
    Пользователь не зарегистрирован
{/if}