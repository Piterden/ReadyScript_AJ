<form method="POST">
    <table class="formTable">
        <tr>
            <td class="key">Новый пароль</td>
            <td class="value"><input type="password" size="30" name="new_pass" {if !empty($error)}class="has-error"{/if}>
                <span class="formFieldError">{$error}</span>
                <div class="help">Пароль должен содержать не менее 6-ти знаков</div>
            </td>
        </tr>
        <tr>
            <td class="key">Повтор нового пароля</td>
            <td class="value"><input type="password" size="30" name="new_pass_confirm"></td>
        </tr>        
    </table>
    <button type="submit" class="formSave">Сменить пароль</button>    
</form>