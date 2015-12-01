{if $is_auth}
<div class="sign">
    <div class="icon"></div>
    {assign var=referer value=urlencode($url->server('REQUEST_URI'))}
    <a href="{$router->getUrl('users-front-profile')}" class="auth">{$current_user.name} {$current_user.surname}</a>
    {if $use_personal_account}
        <a href="{$router->getUrl('shop-front-mybalance')}" class="register">Баланс {$current_user->getBalance(true, true)}</a>
    {/if}
</div>
{else}
<div class="sign">
    <div class="icon"></div>
    {assign var=referer value=urlencode($url->server('REQUEST_URI'))}
    <a href="{$router->getUrl('users-front-auth', ['referer' => $referer])}" class="auth inDialog">Войти</a>
    <a href="{$router->getUrl('users-front-register', ['referer' => $referer])}" class="register inDialog">Зарегистрироваться</a>
</div>
{/if}