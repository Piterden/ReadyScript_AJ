<form method="POST" class="authorization formStyle">
    <div class="forms">
        <h2>Восстановление пароля</h2>
        <div class="center">
            <div class="formLine">
                <label class="fielName">Новый пароль</label><br>
                <input type="password" size="30" name="new_pass" class="inp{if !empty($error)} has-error{/if}">
                <span class="formFieldError">{$error}</span>
            </div>
            <div class="formLine">
                <label class="fielName">Повтор нового пароля</label><br>
                <input type="password" size="30" name="new_pass_confirm" class="inp">
            </div>            
            <input type="submit" value="Сменить пароль">
        </div>
    </div>
</form>