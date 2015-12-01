{if $is_auth}
<a href="{$router->getUrl('users-front-profile')}" class="userAuthButton">
    <img src="{$THEME_IMG}/user.png" alt="User">
</a>
<div class="authorized">
    <div class="top">
        <div class="my">
            <div class="dropblock">
                <div class="user">
                    <a  href="{$router->getUrl('users-front-profile')}" class="username">{$current_user.name} {$current_user.surname}</a>
                </div>
                <ul class="dropdown">
                    <li>
                        <a href="{$router->getUrl('users-front-profile')}">Профиль</a>
                    </li>
                    <li>
                        <a href="{$router->getUrl('shop-front-myorders')}">Мои заказы</a>
                    </li>
                    <li>
                        <a href="{$router->getUrl('support-front-support')}">Сообщения</a>
                    </li>
                    <li class="exitWrap">
                        <a href="{$router->getUrl('users-front-auth', ['Act' => 'logout'])}" class="exit">Выход</a>    
                    </li>
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
    
    {if ModuleManager::staticModuleExists('support')}
        {moduleinsert name="\Support\Controller\Block\NewMessages"}
    {/if}

</div>
{else}
<div class="auth">
    {assign var=referer value=urlencode($url->server('REQUEST_URI'))}
    <a href="{$router->getUrl('users-front-auth', ['referer' => $referer])}" class="first inDialog"><span>Войти</span></a>
    <a href="{$router->getUrl('users-front-register', ['referer' => $referer])}" class="inDialog"><span>Зарегистрироваться</span></a>
</div>
{/if}