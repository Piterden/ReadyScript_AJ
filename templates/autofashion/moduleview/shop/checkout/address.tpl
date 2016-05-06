<form method="POST" class="checkoutBox formStyle {$order.user_type|default:"authorized"}" id="order-form">
    {if !$is_auth}
        <div class="userType">
            <input type="radio" id="type-user" name="user_type" value="person" {if $order.user_type=='person'}checked{/if}><label for="type-user">Частное лицо</label>
            <input type="radio" id="type-account" name="user_type" value="user" {if $order.user_type=='user'}checked{/if}><label for="type-account">Я регистрировался раннее</label>
        </div>
    {/if}

    {$errors=$order->getNonFormErrors()}
    {if $errors}
        <div class="pageError">
            {foreach $errors as $item}
                <p>{$item}</p>
            {/foreach}
        </div>
    {/if}
    <div class="newAccount">
        {if $is_auth}
            <div class="row contactData">
                <div class="titleWrap col-sm-24 text-center">
                    <h3>Контактные данные</h3>
                </div>
                <div class="col-sm-24 text-center">
                    <div class="formLine changeUser h4">
                        <a href="{urlmake logout=true}" class="link">Сменить пользователя</a>
                    </div>
                </div>
                <div class="col-sm-12 col-sm-offset-5">
                    <div class="themeTable companyTable">
                        <div class="tableRow">
                            <div class="key surname">Фамилия</div>
                            <div class="value surname">
                                <input disabled type="text" value="{$user.surname}">
                            </div>
                        </div>
                        <div class="tableRow">
                            <div class="key name">Имя</div>
                            <div class="value name">
                                <input disabled type="text" value="{$user.name}">
                            </div>
                        </div>
                        <div class="tableRow">
                            <div class="key midname">Отчество</div>
                            <div class="value midname">
                                <input disabled type="text" value="{$user.midname}">
                            </div>
                        </div>
                        <div class="tableRow">
                            <div class="key phone">Телефон</div>
                            <div class="value phone">
                                <input disabled type="text" value="{$user.phone}">
                            </div>
                            <div class="key e_mail">E-mail</div>
                            <div class="value e_mail">
                                <input disabled type="text" value="{$user.e_mail}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {else}
            <div class="row contactData">
                <div class="titleWrap col-sm-24 text-center">
                    <h3>Контактные данные</h3>
                </div>
                <div class="col-sm-24 text-center">
                    <div class="changeUser h4">
                        <a href="#" class="toggleView" id="hasAccount">Уже регистрировались</a>
                    </div>
                </div>
                <div class="col-sm-12 col-sm-offset-5">
                    <div class="themeTable">
                        <div class="tableRow">
                            <div class="key">
                                <label class="fieldName">Фамилия</label>
                            </div>
                            <div class="value">
                                {$order->getPropertyView('reg_surname')}
                                <div class="help">Фамилия покупателя, владельца аккаунта</div>
                            </div>
                        </div>
                        <div class="tableRow">
                            <div class="key">
                                <label class="fieldName">Имя</label>
                            </div>
                            <div class="value">
                                {$order->getPropertyView('reg_name')}
                                <div class="help">Имя покупателя, владельца аккаунта</div>
                            </div>
                        </div>
                        <div class="tableRow">
                            <div class="key">
                                <label class="fieldName">Отчество</label>
                            </div>
                            <div class="value">
                                {$order->getPropertyView('reg_midname')}
                            </div>
                        </div>
                        <div class="tableRow">
                            <div class="key">
                                <label class="fieldName">Телефон</label>
                            </div>
                            <div class="value">
                                {$order->getPropertyView('reg_phone')}
                                <div class="help">В формате: +7(123)9876543</div>
                            </div>
                        </div>
                        <div class="tableRow">
                            <div class="key">
                                <label class="fieldName">E-mail</label>
                            </div>
                            <div class="value">
                                {$order->getPropertyView('reg_e_mail')}
                            </div>
                        </div>
                        <div class="tableRow">
                            <div class="key">
                                <label class="fieldName">Пароль</label>
                            </div>
                            <div class="value password">
                                <input type="checkbox" name="reg_autologin" {if $order.reg_autologin}checked{/if} value="1" id="reg-autologin">
                                <label for="reg-autologin">Получить автоматически на e-mail</label>
                                <div class="help">Нужен для проверки статуса заказа, обращения в поддержку, входа в кабинет</div>
                            </div>
                        </div>
                        {foreach $reg_userfields->getStructure() as $fld}
                            <div class="tableRow">
                                <div class="key">
                                    <label class="fieldName">{$fld.title}</label>
                                </div>
                                <div class="value">
                                    {$reg_userfields->getForm($fld.alias)}
                                    {$errname=$reg_userfields->getErrorForm($fld.alias)}
                                    {$error=$order->getErrorsByForm($errname, ', ')}
                                    {if !empty($error)}
                                        <span class="formFieldError">{$error}</span>
                                    {/if}
                                </div>
                            </div>
                        {/foreach}
                        <div class="tableRow" id="manual-login" {if $order.reg_autologin}style="display:none"{/if}>
                            <div class="key inputPass">Пароль</div>
                            <div class="value inputPass">
                                {$order.__reg_openpass->formView(['form'])}
                            </div>
                            <div class="key repeatPass">Повтор пароля</div>
                            <div class="value repeatPass">
                                {$order.__reg_pass2->formView()}
                                <div class="formFieldError">{$order->getErrorsByForm('reg_openpass', ', ')}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {/if}
        <div class="row hasAccount hide">
            <div class="titleWrap col-sm-24 text-center">
                <h3>Вход</h3>
            </div>
            <div class="col-sm-24 text-center">
                <div class="changeUser h4">
                    <a href="#" class="toggleView" id="contactData">Зарегистрироваться</a>
                </div>
            </div>
            <div class="col-sm-12 col-sm-offset-5">
                <div class="themeTable">
                    <input type="hidden" name="ologin" value="1" id="doAuth" {if $order.user_type!='user'}disabled{/if}>
                    <div class="tableRow">
                        <div class="key">
                            <label class="fieldName">E-mail</label>
                        </div>
                        <div class="value">
                            {$order->getPropertyView('login')}
                        </div>
                    </div>
                    <div class="tableRow">
                        <div class="key">
                            <label class="fieldName">Пароль</label>
                        </div>
                        <div class="value">
                            {$order->getPropertyView('password')}
                        </div>
                    </div>
                    <div class="tableRow">
                        <div class="key"></div>
                        <div class="value text-center">
                            <input type="submit" value="Войти">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row address">
            <div class="titleWrap col-sm-24 text-center">
                <h3>Адрес</h3>
            </div>
            <div class="col-sm-12 col-sm-offset-5 lastAddress">
                <div class="themeTable">
                    {if count($address_list)>0}
                        {foreach $address_list as $address}
                            <div class="tableRow">
                                <div class="key">
                                    <input type="radio" name="use_addr" value="{$address.id}" id="adr_{$address.id}" {if $order.use_addr == $address.id}checked{/if}>
                                </div>
                                <div class="value">
                                    <label for="adr_{$address.id}">{$address->getLineView()}</label>
                                </div>
                                <div class="delete">
                                    <a href="{$router->getUrl('shop-front-checkout', ['Act' =>'deleteAddress', 'id' => $address.id])}" class="deleteAddress" title="Удалить адрес из списка" /><i class="fa fa-times"></i></a>
                                </div>
                            </div>
                        {/foreach}
                    {/if}
                    <div class="tableRow">
                        <div class="key">
                            <input type="radio" name="use_addr" value="-1" id="use_addr_sd" {if $order.use_addr == -1}checked{/if}>
                        </div>
                        <div class="value">
                            <label for="use_addr_sd">Я буду использовать пункт самовывоза</label><br>
                        </div>
                    </div>
                    <div class="tableRow">
                        <div class="key">
                            <input type="radio" name="use_addr" value="0" id="use_addr_new" {if $order.use_addr == 0}checked{/if}>
                        </div>
                        <div class="value">
                            <label for="use_addr_new">Другой адрес</label><br>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-sm-offset-5 newAddress{if $order.use_addr!=0} hide{/if}">
                <div class="themeTable">
                    <div class="tableRow">
                        <input type="hidden" name="addr_country_id" value="1">
                        {*<div class="key district">
                            <label class="fieldName">Регион</label>
                        </div>
                        <div class="value district">
                            <select id="sd_region" name="order_extra[region_id_to]">
                                {foreach $regions_sd_list as $id => $name}
                                    <option value="{$id}"
                                            {if ($order.use_addr > 0)}
                                                {if $order->getAddress()->region == $id} selected{/if}
                                            {else}
                                                {if ($order_extra.address['region_id_to'] && $id == $order_extra.address['region_id_to'])
                                		|| (!$order_extra.address['region_id_to'] && $id == 'САНКТ-ПЕТЕРБУРГ')} selected{/if}
                                            {/if}>{$name}</option>
                                {/foreach}
                            </select>
                            <input id="sd_region_name" type="hidden" name="order_extra[region_to]" value="{$regions_sd_list[$order_extra.address['region_id_to']]}">
                            <input id="addr_region" type="hidden" name="addr_region" value="{$regions_sd_list[$order_extra.address['region_id_to']]}">
                        </div>*}
                        <div class="key district">
	                        <label class="fieldName">Область/край</label>
                        </div>
                        <div class="value district">
	                        {assign var=regcount value=$order->regionList()}
	                        <span {if count($regcount) == 0}style="display:none"{/if} id="region-select">
		                        {$order.__addr_region_id->formView()}
	                        </span>

	                        <span {if count($regcount) > 0}style="display:none"{/if} id="region-input">
		                        {$order.__addr_region->formView()}
	                        </span>
                        </div>
                        <div class="key index">
                            <label class="fieldName">Индекс</label>
                        </div>
                        <div class="value index">
                            {$order.__addr_zipcode->formView()}
                        </div>
                    </div>
                    <div class="tableRow">
                        <div class="key">
                            <label class="fieldName">Город</label>
                        </div>
                        <div class="value">
                            {$order->getPropertyView('addr_city')}
                        </div>
                    </div>
                    <div class="tableRow">
                        <div class="key">
                            <label class="fieldName">Адрес</label>
                        </div>
                        <div class="value">
                            {$order->getPropertyView('addr_address')}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-sm-offset-5 sdAddress hide">
	            {*static_call var=regions_sd_list callback=['\Imldelivery\Model\DeliveryType\Iml', 'staticGetRegions']}
                <div class="themeTable">
                    <div class="tableRow">
                        <div class="key">
                            <label class="fieldName">Город</label>
                        </div>
                        <div class="value">
                            <select id="sd_region" name="order_extra[region_id_to]">
                                {foreach $regions_sd_list as $id => $name}
                                    <option value="{$id}"{if ($order_extra.address['region_id_to'] && $id == $order_extra.address['region_id_to']) || (!$order_extra.address['region_id_to'] && $id == 'САНКТ-ПЕТЕРБУРГ')} selected{/if}>{$name}</option>
                                {/foreach}
                            </select>
                            <input id="sd_region_name" type="hidden" name="order_extra[region_to]" value="{$regions_sd_list[$order_extra.address['region_id_to']]}">
                        </div>
                    </div>
                </div>*}
                <input type="hidden" name="order_extra[region_id_to]" value="{$order['order_extra']['delivery']['region_id_to']}">
            </div>
            {if !$is_auth}
                <div class="col-sm-12 col-sm-offset-5 additional">
                    <div class="themeTable">
                        {if $order.__code->isEnabled()}
                            <div class="tableRow captcha">
                                <div class="key">
                                    <label class="fieldName">Защитный код</label>
                                </div>
                                <div class="value">
                                    {$order->getPropertyView('code')}
                                    <div class="help">Необходим для защиты от спам роботов</div>
                                </div>
                            </div>
                        {/if}
                    </div>
                </div>
            {/if}
            <div class="col-sm-12 col-sm-offset-5 additional">
                <div class="themeTable">
                    {if $conf_userfields->notEmpty()}
                        <div class="additional">
                            <h2>Дополнительные сведения</h2>
                            {foreach $conf_userfields->getStructure() as $fld}
                                <div class="tableRow">
                                    <div class="key">
                                        <label class="fieldName">{$fld.title}</label>
                                    </div>
                                    <div class="value">
                                        {$conf_userfields->getForm($fld.alias)}
                                        {assign var=errname value=$conf_userfields->getErrorForm($fld.alias)}
                                        {assign var=error value=$order->getErrorsByForm($errname, ', ')}
                                        {if !empty($error)}
                                            <span class="formFieldError">{$error}</span>
                                        {/if}
                                    </div>
                                </div>
                            {/foreach}
                        </div>
                    {/if}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="buttons col-sm-24 text-center">
                <button type="submit" class="button cornered">Далее</button>
            </div>
        </div>
    </div>
</form>
