{if $is_auth}
<div class="personal">
    <p class="username rs-parent-switcher">{$current_user.name} {$current_user.surname} <i class="down"></i></p>
    {if $use_personal_account}
    <p class="balance">Баланс: <a class="underline" href="{$router->getUrl('shop-front-mybalance')}">{$current_user->getBalance(true, true)}</a></p>
    {/if}
    <ul class="userMenu">
        <li><a href="{$router->getUrl('users-front-profile')}">Профиль</a></li>
        <li><a href="{$router->getUrl('shop-front-myorders')}">Мои заказы</a></li>
        {if $use_personal_account}
        <li><a href="{$router->getUrl('shop-front-mybalance')}">Лицевой счет</a></li>
        {/if}
        {if ModuleManager::staticModuleExists('support')}
        <li><a href="{$router->getUrl('support-front-support')}">Сообщения</a></li>
        {/if}
        {if ModuleManager::staticModuleExists('partnership')}
        {static_call var="is_partner" callback=['Partnership\Model\Api', 'isUserPartner'] params=$current_user.id}
            {if $is_partner}
                <li><a href="{$router->getUrl('partnership-front-profile')}">Профиль партнера</a></li>
            {/if}
        {/if}        
        <li><a href="{$router->getUrl('users-front-auth', ['Act' => 'logout'])}">Выход</a></li>
    </ul>
</div>
{else}
<div class="guest">
    {assign var=referer value=urlencode($url->server('REQUEST_URI'))}
    <a href="{$router->getUrl('users-front-auth', ['referer' => $referer])}" class="join inDialog">Войти</a>
    <a href="{$router->getUrl('users-front-register', ['referer' => $referer])}" class="reg inDialog">Зарегистрироваться</a>
</div>
{/if}