<div class="col-md-12 col-md-offset-6">
    <form method="POST" class="formStyle profile">
        {csrf}
        {$this_controller->myBlockIdInput()}
        <div class="userType">
            <input type="radio" id="ut_user" name="is_company" value="0" {if !$user.is_company}checked{/if}><label for="ut_user" class="userLabel f14">Частное лицо</label>
            <input type="radio" id="ut_company" name="is_company" value="1" {if $user.is_company}checked{/if}><label for="ut_company" class="f14">Компания</label>
        </div>        
        
        {if count($user->getNonFormErrors())>0}
            <div class="pageError">
                {foreach $user->getNonFormErrors() as $item}
                <p>{$item}</p>
                {/foreach}
            </div>
        {/if}    

        {if $result}
            <div class="formResult success">{$result}</div>
        {/if}           
        <div class="oh">
            <div class="{if $user.is_company} thiscompany{/if}" id="fieldsBlock">
                <div class="companyFields">
                    <div class="formLine">
                        <label class="fieldName">Название организации</label>
                        {$user->getPropertyView('company')}
                    </div>                            
                    <div class="formLine">
                        <label class="fieldName">ИНН</label>
                        {$user->getPropertyView('company_inn')}
                    </div>                                
                </div>
                <div class="formLine">
                    <label class="fieldName">Фамилия</label>
                    {$user->getPropertyView('surname', ['size'=>'0','placeholder'=>'Фамилия'])}
                </div>                    
                <div class="formLine">
                    <label class="fieldName">Имя</label>
                    {$user->getPropertyView('name', ['size'=>'0','placeholder'=>'Имя'])}
                </div>
                <div class="formLine">
                    <label class="fieldName">Отчество</label>
                    {$user->getPropertyView('midname', ['size'=>'0','placeholder'=>'Отчество'])}
                </div>
                <div class="formLine">
                    <label class="fieldName">Телефон</label>
                    {$user->getPropertyView('phone', ['size'=>'0','placeholder'=>'Телефон'])}
                </div>
                <div class="formLine">
                    <label class="fieldName">E-mail</label>
                    {$user->getPropertyView('e_mail', ['size'=>'0','placeholder'=>'E-mail'])}
                </div>
                {if $conf_userfields->notEmpty()}
                    {foreach $conf_userfields->getStructure() as $fld}
                    <div class="formLine">
                    <label class="fieldName">{$fld.title}</label>
                        {$conf_userfields->getForm($fld.alias)}
                        {$errname=$conf_userfields->getErrorForm($fld.alias)}
                        {$error=$user->getErrorsByForm($errname, ', ')}
                        {if !empty($error)}
                            <span class="formFieldError">{$error}</span>
                        {/if}
                    </div>
                    {/foreach}
                {/if}                 
            </div>
            <div class="changePassWrap">
                <div class="formLine">
                    <input type="checkbox" id="changePass" name="changepass" value="1" {if $user.changepass}checked{/if}><label for="changePass" class="ft14">Сменить пароль</label>
                    <div class="changePass {if !$user.changepass}hidden{/if}">
                        <div class="formLine">
                            <label class="fieldName">Текущий пароль</label>
                            <input placeholder="Текущий пароль" type="password" name="current_pass" {if count($user->getErrorsByForm('pass'))}class="has-error"{/if}>
                            <span class="formFieldError">{$user->getErrorsByForm('pass', ',')}</span>
                        </div>
                        <div class="formLine">
                            <label class="fieldName">Новый пароль</label>
                            <input placeholder="Новый пароль" type="password" name="openpass" {if count($user->getErrorsByForm('pass'))}class="has-error"{/if}>
                            <span class="formFieldError">{$user->getErrorsByForm('openpass', ',')}</span>
                        </div>                        
                        <div class="formLine">
                            <label class="fieldName">Повторить пароль</label>
                            <input placeholder="Повторите пароль" type="password" name="openpass_confirm" {if count($user->getErrorsByForm('openpass'))}class="has-error"{/if}>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="buttons">
            <button type="submit">Сохранить</button>
        </div>
    </form>  
</div>  
<script type="text/javascript">
    $(function() {
        $('#changePass').change(function() {
            $('.changePass').toggleClass('hidden', !this.checked);
        });            
        
        $('.profile .userType input').click(function() {
            $('#fieldsBlock').toggleClass('thiscompany', $(this).val() == 1);
        });
    });        
</script>    