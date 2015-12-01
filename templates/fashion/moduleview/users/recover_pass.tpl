<form method="POST" action="{$router->getUrl('users-front-auth', ["Act" => "recover"])}" class="authorization recoverPassword">
    {$this_controller->myBlockIdInput()}
    <h1 data-dialog-options='{ "width": "450" }'>Восстановление пароля</h1>
    <div class="forms formStyle">
        <div class="center">            
            {if !empty($error)}<div class="error">{$error}</div>{/if}        
            <div class="formLine">
                <label class="fieldName">E-mail</label>
                <input type="text" name="login" value="{$data.login}" data-deftext="e-mail" class="inp" value="{$data.login}" {if $send_success}readonly{/if}>
            </div>
            
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
                <div class="floatWrap oh">
                    <input type="submit" value="Отправить">
                </div>
            {/if}
        </div>
        <div class="underLine links">
            <a href="{$router->getUrl('users-front-register')}" class="linkreg inDialog">Зарегистрируйтесь</a>
            <a href="{$router->getUrl('users-front-auth')}" class="linkauth inDialog">Авторизуйтесь</a>
        </div>
    </div>
</form>