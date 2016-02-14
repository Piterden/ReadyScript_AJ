{extends file="%THEME%/wrapper.tpl"}
{block name="content"}
    {$shop_config=ConfigLoader::byModule('shop')}
    {$route_id=$router->getCurrentRoute()->getId()}
    <div class="box profile container">
        <div class="row">
            <h1 class="col-md-24 text-center"><span class="whiteBack">Личный кабинет</span></h1>
        </div>
        <div class="rel row userMenu">
            <div class="col-md-10 col-md-offset-7 text-center">
                <ul class="profileMenu">

                    <li {if $route_id=='users-front-profile'}class="act"{/if}><a href="{$router->getUrl('users-front-profile')}">Профиль</a></li>

                    <li {if in_array($route_id, ['shop-front-myorders', 'shop-front-myorderview'])}class="act"{/if}><a href="{$router->getUrl('shop-front-myorders')}">Мои заказы</a></li>

                    {if $shop_config.use_personal_account}
                        <li {if $route_id=='shop-front-mybalance'}class="act"{/if}><a href="{$router->getUrl('shop-front-mybalance')}">Лицевой счет</a></li>
                        {/if}

                    <!-- {if ModuleManager::staticModuleExists('support')}
                    <li {if $route_id=='support-front-support'}class="act"{/if}><a href="{$router->getUrl('support-front-support')}">Сообщения</a></li>
                    {/if} -->

                        {if ModuleManager::staticModuleExists('partnership')}
                            {static_call var="is_partner" callback=['Partnership\Model\Api', 'isUserPartner'] params=$current_user.id}
                            {if $is_partner}
                                <li {if $route_id=='partnership-front-profile'}class="act"{/if}><a href="{$router->getUrl('partnership-front-profile')}">Профиль партнера</a></li>
                                {/if}
                            {/if}

                        {if ModuleManager::staticModuleExists('wishlist')}
                            <li {if $route_id=='wishlist-front-wishlist'}class="act"{/if}><a href="{$router->getUrl('wishlist-front-wishlist')}">Избранное</a></li>
                            {/if}

                <!-- <li class="exitButton"><a href="{$router->getUrl('users-front-auth', ['Act' => 'logout'])}">Выход</a></li> -->
                    </ul>
                </div>
            </div>
            <div class="rel row userContent">
                {$app->blocks->getMainContent()}
            </div>
            <div class="clearfix"></div>
        </div>
        {/block}
