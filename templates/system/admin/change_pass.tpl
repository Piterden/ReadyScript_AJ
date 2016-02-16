{strip}
{addcss file="admin/style.css" basepath="common"}
{addcss file="admin/framework.css" basepath="common"}
{addjs file="jquery.min.js" basepath="common"}
{addjs file="jquery.form.js" basepath="common"}
{addjs file="jquery.tooltip.js" basepath="common"}
{addjs file="admin.js" basepath="common"}
{addjs file="jquery.rsframework.js" basepath="common"}
{/strip}
{$app->setBodyClass('authbody')}
<div class="win">
    <div class="title-box">
        <img class="logo" src="{$Setup.IMG_PATH}/adminstyle/logo.gif">
        
        <div class="select-lang rs-list-button">
            <span class="rs-active rs-dropdown-ext-handler" title="{t}Выбор языка{/t}">{$locale_list[$current_lang]}</span>{if count($locale_list)>1}<a class="rs-dropdown-handler">&nbsp;</a>
            <ul class="rs-dropdown">
                {assign var=i value="0"}
                {foreach from=$locale_list key=locale_key item=locale name="loc"}
                    {if $current_lang != $locale_key}
                    <li {if !$i}class="first"{/if}><a href="{adminUrl do=false mod_controller=false Act=changeLang lang=$locale_key referer=$url->getSelfUrl()}">{$locale}</a></li>
                    {assign var=i value=$i+1}
                    {/if}                    
                {/foreach}
            </ul>
            {/if}
        </div>
    </div>
    <div class="form-box{if !empty($err)} has-error{/if}{if !empty($data.successText)} success{/if}">
        <div class="error-message">{$err}</div>
        <div class="success-message">{$data.successText}</div>
        
        <form method="POST" id="auth" action="{adminUrl mod_controller=false do=false Act="changePassword" uniq=$uniq}" class="body-box" {if $data.do == 'recover'}style="display:none"{/if}>
            {csrf form="change_password"}
            <input type="hidden" name="lang" value="ru">
            <input type="hidden" name="referrer" value="{$referrer|escape}">
            <h1>{t}Смена пароля{/t}</h1>
            <div class="height-saver">
                <p class="login"><label>{t}Новый пароль{/t}</label>
                    <input type="password" id="login" name="new_pass" value="{$data.login|escape}"></p>
                    
                <p class="pass"><label>{t}Повтор нового пароля{/t}</label>
                    <input type="password" id="pass" name="new_pass_confirm"></p>
            </div>
            <p class="buttons">
                <button type="submit">{t}сменить пароль{/t}</button>
            </p>
        </form>
    </div>
    
</div>