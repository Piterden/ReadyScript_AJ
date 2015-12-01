<form method="POST" action="{$router->getUrl('users-front-auth')}" class="authorization formStyle container">
    <h1 data-dialog-options='{ "width": "460" }'>Авторизация</h1>
    {$this_controller->myBlockIdInput()}
    <div class="forms row">
        <input type="hidden" name="referer" value="{$data.referer}">
        {if !empty($status_message)}<div class="pageError">{$status_message}</div>{/if}
        {if !empty($error)}<div class="error">{$error}</div>{/if}
        <div class="col-md-16 col-md-offset-4">
            <div class="formLine">
                <label class="fieldName">E-mail</label>
                <input placeholder="E-mail" type="text" size="30" name="login" value="{$data.login|default:$Setup.DEFAULT_DEMO_LOGIN}" class="inp">
            </div>
            <div class="formLine">
                <label class="fieldName">Пароль</label>
                <input placeholder="Пароль" type="password" size="30" name="pass" value="{$Setup.DEFAULT_DEMO_PASS}" class="inp">
            </div>    
            <div class="formLine rem">
                <input type="checkbox" id="rememberMe" name="remember" value="1" {if $data.remember}checked{/if}> <label for="rememberMe">Запомнить меня</label>
            </div>
            <div class="buttons">
                <div class="fleft">
                    <button type="submit">Войти</button>                        
                </div>
                <div class="fright">
                    <a href="{$router->getUrl('users-front-auth', ["Act" => "recover"])}" class="recover inDialog">Забыли пароль?</a>
                </div>
            </div>
        </div>
        <div class="underLine col-md-20 col-md-offset-2">
            <p>Зарегистрировавшись у нас, Вы сможете хранить всю информацию о Ваших покупках, адресах доставок на нашем сайте, 
                а также видеть ход исполнения заказов. Регистрация займет не более 2-х минут.</p>
            <a href="{$router->getUrl('users-front-register')}" class="button color reg inDialog">Зарегистрироваться</a>
        </div>
    </div>                
</form>