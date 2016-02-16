<form method="POST" action="{$router->getUrl('users-front-auth', ["Act" => "recover"])}" class="authorization recoverPassword">
    {$this_controller->myBlockIdInput()}
    <h2 data-dialog-options='{ "width": "450" }'>Восстановление пароля</h2>
    <div class="forms formStyle">
        <div class="center">            
            {if !empty($error)}<div class="error">{$error}</div>{/if}        
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
                <div class="floatWrap oh">
                    <input type="submit" value="Отправить" class="fright">
                </div>
            {/if}
        </div>
        <div class="underLine">
            <a href="{$router->getUrl('users-front-register')}" class="recover inDialog">Зарегистрируйтесь</a>
            <a href="{$router->getUrl('users-front-auth')}" class="reg inDialog mleft30">Авторизуйтесь</a>
        </div>
    </div>
</form>