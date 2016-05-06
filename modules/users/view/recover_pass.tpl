<form method="POST" action="{$router->getUrl('users-front-auth', ["Act" => "recover"])}" class="authorization">
    {$this_controller->myBlockIdInput()}
    {if $url->request('dialogWrap', $smarty.const.TYPE_INTEGER)}
        <h2 data-dialog-options='{ "width": "360" }'>Восстановление пароля</h2>
        <div class="dialogForm">
            {if !empty($error)}<div class="error">{$error}</div>{/if}
            <input type="text" name="login" value="{$data.login}" data-deftext="e-mail" class="login" value="{$data.login}" {if $send_success}readonly{/if}>
            {if $send_success}
                <div class="recoverText success">
                    <i></i>
                    Письмо успешно отправлено. Следуйте инструкциям в письме
                </div>            
            {else}
                <div class="recoverText">
                    <i></i>
                    На указанный E-mail будет отправлено письмо с дальнейшими инструкциями по восстановлению пароля
                </div>
                <div class="floatWrap">
                    <button type="submit">Отправить</button>
                </div>
            {/if}
            
            <div class="noAccount">
                Нет аккаунта? &nbsp;&nbsp;&nbsp;<a href="{$router->getUrl('users-front-register')}" class="inDialog">Зарегистрируйтесь</a><br>
                Вспомнили пароль? &nbsp;&nbsp;&nbsp;<a href="{$router->getUrl('users-front-auth')}" class="inDialog">Авторизуйтесь</a>
            </div>
        </div>
    {else}
        {if $send_success}
            <div class="formSuccessText">E-mail: {$data.login}<br>
                Письмо успешно отправлено. Следуйте инструкциям в письме</div>
        {else}
        <table class="formTable">
            <tr>
                <td class="key">E-mail</td>
                <td class="value"><input type="text" size="30" name="login" value="{$data.login}" {if !empty($error)}class="has-error"{/if}>
                <span class="formFieldError">{$error}</span>
                <div class="help">На указанный E-mail будет отправлено письмо с дальнейшими инструкциями по восстановлению пароля</div>
                </td>
            </tr>
        </table>
        <button type="submit" class="formSave">Отправить</button>    
        {/if}
    {/if}
</form>