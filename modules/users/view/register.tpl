{addjs file="jquery.activetabs.js"}

{if $url->request('dialogWrap', $smarty.const.TYPE_INTEGER)}
    <h2 data-dialog-options='{ "width": "755" }'>Регистрация пользователя</h2>
{/if}

{if count($user->getNonFormErrors())>0}
    <div class="pageError">
        {foreach from=$user->getNonFormErrors() item=item}
        <p>{$item}</p>
        {/foreach}
    </div>
{/if}    

<form method="POST" action="{$router->getUrl('users-front-register')}">
    {$this_controller->myBlockIdInput()}
    <input type="hidden" name="referer" value="{$referer}">
    <input type="hidden" name="is_company" value="{$user.is_company}">
    <div class="userProfile activeTabs" data-input-name="is_company">
        <div class="formSection">
            <span class="sectionListBlock">
                <ul class="lineList tabList">
                    <li><a class="item {if !$user.is_company}act{/if}" data-input-val="0" data-tab="#profile"><i>частное лицо</i></a></li>
                    <li><a class="item {if $user.is_company}act{/if}" data-class="thiscompany" data-input-val="1" data-tab="#profile"><i>компания</i></a></li>
                </ul>
            </span>
        </div>
        
        <table class="formTable tabFrame{if $user.is_company} thiscompany{/if}" id="profile">
            <tbody class="organization">
                <tr>
                    <td class="key">Название организации:</td>
                    <td class="value">
                        {$user->getPropertyView('company')}
                        <div class="help">Например: ООО "Аудитор"</div>
                    </td>
                </tr>
                <tr>
                    <td class="key">ИНН:</td>
                    <td class="value">
                        {$user->getPropertyView('company_inn')}
                        <div class="help">10 или 12 цифр</div>
                    </td>
                </tr>                
            </tbody>                  
            <tbody>
            <tr>
                <td class="key">Имя</td>
                <td class="value">
                    {$user->getPropertyView('name')}
                    <div class="help">Может состоять только из букв, знака тире. Например: Иван</div>
                </td>
            </tr>
            <tr>
                <td class="key">Фамилия</td>
                <td class="value">
                    {$user->getPropertyView('surname')}
                    <div class="help">Может состоять только из букв, знака тире. Например: Петров</div>
                </td>    
            </tr>
            <tr>
                <td class="key">Отчество</td>
                <td class="value">
                    {$user->getPropertyView('midname')}
                    <div class="help">Может состоять только из букв, знака тире. Например: Иванович</div>
                </td>    
            </tr>
            <tr>
                <td class="key">Телефон</td>
                <td class="value">
                    {$user->getPropertyView('phone')}
                    <div class="help">Например: +7 918 00011222. Можно указывать несколько номеров, разделяя их запятой</div>
                </td>    
            </tr>    
            <tr>
                <td class="key">E-mail</td>
                <td class="value">
                    {$user->getPropertyView('e_mail')}
                </td>    
            </tr>   
            <tr>
                <td class="key">Пароль</td>
                <td class="value">
                    <div class="ib">
                        <input type="password" name="openpass" {if count($user->getErrorsByForm('openpass'))}class="has-error"{/if}>
                        <div class="help">Пароль</div>
                    </div>
                    <div class="ib ml10">
                        <input type="password" name="openpass_confirm">
                        <div class="help">Повтор пароля</div>
                    </div>
                    <div class="formFieldError">
                        {$user->getErrorsByForm('openpass', ',')}
                    </div>
                </td>    
            </tr>        
            
            {if $conf_userfields->notEmpty()}
                {foreach from=$conf_userfields->getStructure() item=fld}
                <tr>
                    <td class="key">{$fld.title}</td>
                    <td class="value">
                        {$conf_userfields->getForm($fld.alias)}
                        {assign var=errname value=$conf_userfields->getErrorForm($fld.alias)}
                        {assign var=error value=$user->getErrorsByForm($errname, ', ')}
                        {if !empty($error)}
                            <span class="formFieldError">{$error}</span>
                        {/if}                        
                    </td>
                </tr>
                {/foreach}
            {/if}   
            {if $user.__captcha->isEnabled()}
                <tr class="captcha">
                    <td class="key">Защитный код</td>
                    <td class="value">
                        {$user->getPropertyView('captcha')}
                    </td>    
                </tr>
            {/if}
            </tbody>
        </table>
    </div>

    <button type="submit" class="formSave">Сохранить</button>
</form>