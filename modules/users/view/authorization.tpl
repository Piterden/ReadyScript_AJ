{if !empty($status_message)}<div class="pageError">{$status_message}</div>{/if}
<form method="POST" action="{$router->getUrl('users-front-auth')}" class="authorization">
    {$this_controller->myBlockIdInput()}
    <input type="hidden" name="referer" value="{$data.referer}">
    {if $url->request('dialogWrap', $smarty.const.TYPE_INTEGER)}
        <h2 data-dialog-options='{ "width": "360" }'>Авторизация</h2>
        <div class="dialogForm">
            {if !empty($error)}<div class="error">{$error}</div>{/if}
            <input type="text" name="login" value="{$data.login|default:$Setup.DEFAULT_DEMO_LOGIN}" data-deftext="e-mail" class="login">
            <input type="text" value="пароль" class="def-value password" id="emptyPassword" {if $Setup.DEFAULT_DEMO_PASS}style="display:none"{/if}>
            <input type="password" name="pass" class="password" id="realPassword" {if !$Setup.DEFAULT_DEMO_PASS}style="display:none"{/if} value="{$Setup.DEFAULT_DEMO_PASS}">
        
            <div class="floatWrap">
                <div class="rememberBlock">
                    <input type="checkbox" id="rememberMe" name="remember" value="1" {if $data.remember}checked{/if}> <label for="rememberMe">Запомнить меня</label>
                </div>
                <button type="submit">Войти</button>
            </div>
            
            <div class="noAccount">
                Нет аккаунта? &nbsp;&nbsp;&nbsp;<a href="{$router->getUrl('users-front-register')}" class="inDialog">Зарегистрируйтесь</a><br>
                Забыли пароль? &nbsp;&nbsp;&nbsp;<a href="{$router->getUrl('users-front-auth', ["Act" => "recover"])}" class="inDialog">Восстановить пароль</a>            
            </div>
        </div>
        <script type="text/javascript">
            $(function() {
                $('#emptyPassword').focus(function() {
                    $(this).hide();
                    $('#realPassword').show().focus();
                });
                $('#realPassword').blur(function() {
                    if ($(this).val() == '') {
                        $(this).hide();
                        $('#emptyPassword').show();
                    }
                })
            });
        </script>
    {else}
        <table class="formTable">
            <tr>
                <td class="key">E-mail</td>
                <td class="value"><input type="text" size="30" name="login" value="{$data.login}" {if !empty($error)}class="has-error"{/if}>
                <span class="formFieldError">{$error}</span>
                </td>
            </tr>
            <tr>
                <td class="key">Пароль</td>
                <td class="value"><input type="password" size="30" name="pass" {if !empty($error)}class="has-error"{/if}>
                    <div class="rememberBox">
                        <input type="checkbox" id="rememberMe" name="remember" value="1" {if $data.remember}checked{/if}> <label for="rememberMe">Запомнить меня</label>
                    </div>
                </td>
            </tr>        
        </table>
        <a href="{$router->getUrl('users-front-auth', ["Act" => "recover"])}" class="forgotPassword"><span>Забыли пароль?</span></a>    
        <button type="submit" class="formSave">Войти</button>    
    {/if}
</form>