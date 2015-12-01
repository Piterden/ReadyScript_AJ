<form method="POST" action="{$router->getUrl('users-front-auth')}" class="authorization formStyle">
    <h2 data-dialog-options='{ "width": "460" }'>Войти</h2>
    {$this_controller->myBlockIdInput()}
        <div class="forms">
            <input type="hidden" name="referer" value="{$data.referer}">
            {if !empty($status_message)}<div class="pageError">{$status_message}</div>{/if}
            {if !empty($error)}<div class="error">{$error}</div>{/if}
            <div class="center">
                <div class="formLine">
                    <label class="fielName">E-mail</label><br>
                    <input type="text" size="30" name="login" value="{$data.login|default:$Setup.DEFAULT_DEMO_LOGIN}" class="inp">
                </div>
                <div class="formLine">
                    <label class="fielName">Пароль</label><br>
                    <input type="password" size="30" name="pass" value="{$Setup.DEFAULT_DEMO_PASS}" class="inp">
                </div>    
                <div class="oh">
                    <div class="fleft rem">
                        <input type="checkbox" id="rememberMe" name="remember" value="1" {if $data.remember}checked{/if}> <label for="rememberMe">Запомнить меня</label>
                    </div>
                    <div class="fright">
                        <input type="submit" value="Войти">                        
                    </div>
                </div>
            </div>
            <div class="underLine">
                <a href="{$router->getUrl('users-front-auth', ["Act" => "recover"])}" class="recover inDialog">Забыли пароль?</a>
                <a href="{$router->getUrl('users-front-register')}" class="reg inDialog">Зарегистрироваться</a>
            </div>
        </div>                
</form>