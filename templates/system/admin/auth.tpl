{strip}
{addcss file="admin/style.css" basepath="common"}
{addcss file="admin/framework.css" basepath="common"}
{addjs file="jquery.min.js" basepath="common"}
{addjs file="jquery.form.js" basepath="common"}
{addjs file="jquery.tooltip.js" basepath="common"}
{addjs file="jquery.deftext.js" basepath="common"}
{addjs file="admindebug.js" basepath="common"}
{addjs file="admin.js" basepath="common"}
{addjs file="jquery.rsframework.js" basepath="common"}
{/strip}
{$app->setBodyClass('admin-style authbody')}
{if $js}{addjs file="auth.js" basepath="common"}{/if}
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
        
        <form method="POST" id="auth" action="{adminUrl mod_controller=false do=false Act="auth"}" class="body-box" {if $data.do == 'recover'}style="display:none"{/if}>
            <input type="hidden" name="lang" value="ru">
            <input type="hidden" name="referer" value="{$referer|escape}">
            <h1>{t}Авторизация{/t}</h1>
            <div class="height-saver">
                <p class="login"><label>{t}E-mail{/t}</label>
                    <input type="text" id="login" name="login" value="{$data.login|default:$Setup.DEFAULT_DEMO_LOGIN}"></p>
                    
                <p class="pass"><label>{t}Пароль{/t}</label>
                    <input type="password" id="pass" name="pass" value="{$Setup.DEFAULT_DEMO_PASS}"></p>
                    
                <p class="remember"><input type="checkbox" name="remember" value="1" id="remember">
                    <label for="remember">{t}Запомнить меня{/t}</label></p>
            </div>
            <p class="buttons">
                <button type="submit">{t}войти{/t}</button>
                <a href="{adminUrl mod_controller=false do="recover" Act="auth"}" class="forget-pass to-recover"><span>{t}Забыли пароль?{/t}</span></a>
            </p>
        </form>
        
        <form method="POST" id="recover" action="{adminUrl mod_controller=false do="recover" Act="auth"}" class="body-box recover" {if $data.do == 'recover'}style="display:block"{/if}>
            <h1>{t}Восстановить пароль{/t}</h1>
            <div class="height-saver">
                <p class="login"><label>{t}E-mail{/t}</label>
                    <input type="text" id="login" name="login" value="{$data.login|escape}"></p>        
                
                <p class="help">
                    <i class="corner"></i>
                    На указанный E-mail будет отправлено письмо с дальнейшими инструкциями по восстановленю пароля
                </p>    
            </div>
            <p class="buttons">
                <button type="submit">{t}отправить{/t}</button>
                <a href="{adminUrl mod_controller=false Act="auth" do=false}" class="forget-pass back-to-auth"><span>{t}авторизация{/t}</span></a>
            </p>
        </form>
    </div>
    
</div>