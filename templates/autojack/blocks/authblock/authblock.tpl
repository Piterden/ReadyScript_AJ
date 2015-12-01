{if $is_auth}
<div class="authorized">
    <div class="top">
        <div class="user">
            {if $use_personal_account}
            <a class="balance" href="{$router->getUrl('shop-front-mybalance')}" title="Средства на лицевом счете">{$current_user->getBalance(true, true)} </a>
            {/if}
            <a  href="{$router->getUrl('users-front-profile')}" class="username">{$current_user.name} {$current_user.surname}</a>
            </div>
        <div class="my">
            <div class="dropblock">
                <a class="dropdown-handler">Личный кабинет</a>
                <ul class="dropdown">
                    <li><a href="{$router->getUrl('users-front-profile')}">профиль</a></li>
                    <li><a href="{$router->getUrl('shop-front-myorders')}">мои заказы</a></li>
                    {if $use_personal_account}
                        <li><a href="{$router->getUrl('shop-front-mybalance')}">лицевой счет</a></li>
                    {/if}
                    {if ModuleManager::staticModuleExists('partnership')}
                        {static_call var="is_partner" callback=['Partnership\Model\Api', 'isUserPartner'] params=$current_user.id}
                        {if $is_partner}
                        <li><a href="{$router->getUrl('partnership-front-profile')}">профиль партнера</a></li>                    
                        {/if}                    
                    {/if}
                </ul>
            </div>
        </div>
    </div>
    <div class="bottom">    
        <a href="{$router->getUrl('users-front-auth', ['Act' => 'logout'])}" class="exit">Выход</a>    
        {if ModuleManager::staticModuleExists('support')}
            {moduleinsert name="\Support\Controller\Block\NewMessages"}
        {/if}
        
    </div>
</div>
{else}
<div class="auth alignright">
    {assign var=referer value=urlencode($url->server('REQUEST_URI'))}
    <a href="{$router->getUrl('users-front-auth', ['referer' => $referer])}" class="first inDialog"><span>Войти</span></a>
    <a href="{$router->getUrl('users-front-register', ['referer' => $referer])}" class="inDialog"><span>Зарегистрироваться</span></a>
</div>
{/if}