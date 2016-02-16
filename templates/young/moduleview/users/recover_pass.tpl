<form method="POST" action="{$router->getUrl('users-front-auth', ["Act" => "recover"])}" class="authorization recoverPassword">
    {$this_controller->myBlockIdInput()}
    <h2 data-dialog-options='{ "width": "450" }'>Восстановление пароля</h2>
    {if !empty($error)}<div class="error">{$error}</div>{/if}
    <div class="forms formStyle">
        <div class="center">            
            <div class="formLine">
                <label class="fielName">E-mail</label><br>
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
                <div class="floatWrap">
                    <input type="submit" value="Отправить">
                </div>
            {/if}
        </div>
    </div>        
    <div class="grayBlock">
        <i class="lines"></i>
        <a href="{$router->getUrl('users-front-register')}" class="reg inDialog">Зарегистрируйтесь</a>
        <a href="{$router->getUrl('users-front-auth')}" class="reg inDialog mleft30">Авторизуйтесь</a>
    </div>
</form>