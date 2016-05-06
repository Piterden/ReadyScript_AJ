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
        
<form method="POST" class="formStyle profile">
    {csrf}
    {$this_controller->myBlockIdInput()}
    <div class="rsTabs">
        <ul class="tabList">
            <li class="act"><a href="">Персональные данные</a></li>
        </ul>
        
        <div class="tab act{if $user.is_company} thiscompany{/if}" id="fieldsBlock">
                <div class="half fleft">
                    <div class="formLine companyType mt30">
                        <input type="radio" id="ut_user" name="is_company" value="0" {if !$user.is_company}checked{/if}><label for="ut_user" class="userLabel f14">ЧАСТНОЕ ЛИЦО</label>
                        <input type="radio" id="ut_company" name="is_company" value="1" {if $user.is_company}checked{/if}><label for="ut_company" class="f14">КОМПАНИЯ</label>
                    </div>
                   <div class="companyFields">
                        <div class="formLine">
                            <label class="fielName">Название организации</label><br>
                            {$user->getPropertyView('company')}
                        </div>                            
                        <div class="formLine">
                            <label class="fielName">ИНН</label><br>
                            {$user->getPropertyView('company_inn')}
                        </div>                                
                    </div>
                    <div class="formLine">
                        <label class="fielName">Фамилия</label><br>
                        {$user->getPropertyView('surname')}
                    </div>                    
                    <div class="formLine">
                        <label class="fielName">Имя</label><br>
                        {$user->getPropertyView('name')}
                    </div>
                    <div class="formLine">
                        <label class="fielName">Отчество</label><br>
                        {$user->getPropertyView('midname')}
                    </div>
                    <div class="formLine">
                        <label class="fielName">Телефон</label><br>
                        {$user->getPropertyView('phone')}
                    </div>
                    <div class="formLine">
                        <label class="fielName">E-mail</label><br>
                        {$user->getPropertyView('e_mail')}
                    </div>
                    {if $conf_userfields->notEmpty()}
                        {foreach $conf_userfields->getStructure() as $fld}
                        <div class="formLine">
                        <label class="fielName">{$fld.title}</label><br>
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
                <div class="half fright">
                    <div class="formLine mt30">
                        <div class="alignRight mb20">
                            <input type="checkbox" id="changePass" name="changepass" value="1" {if $user.changepass}checked{/if}><label for="changePass" class="ft14">СМЕНИТЬ ПАРОЛЬ</label>
                        </div>
                        <div class="changePass {if !$user.changepass}hidden{/if}">
                            <div class="formLine">
                                <label class="fielName">Текущий пароль</label><br>
                                <input type="password" name="current_pass" {if count($user->getErrorsByForm('pass'))}class="has-error"{/if}>
                                <span class="formFieldError">{$user->getErrorsByForm('pass', ',')}</span>
                            </div>
                            <div class="formLine">
                                <label class="fielName">Новый пароль</label><br>
                                <input type="password" name="openpass" {if count($user->getErrorsByForm('pass'))}class="has-error"{/if}>
                                <span class="formFieldError">{$user->getErrorsByForm('openpass', ',')}</span>
                            </div>                        
                            <div class="formLine">
                                <label class="fielName">Повторить пароль</label><br>
                                <input type="password" name="openpass_confirm" {if count($user->getErrorsByForm('openpass'))}class="has-error"{/if}>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="half cleft">
                    <div class="formLine alignRight">
                        <input type="submit" value="Сохранить">
                    </div>
                </div>
        </div>            
    </div>    
</form>    
    
    
    
{*    
    
    
    
    
    <div class="userType">
        <input type="radio" id="ut_user" name="is_company" value="0" {if !$user.is_company}checked{/if}><label for="ut_user">Частное лицо</label>
        <input type="radio" id="ut_company" name="is_company" value="1" {if $user.is_company}checked{/if}><label for="ut_company">Компания</label>
    </div>    
    <div class="oh">
        <div class="half fleft{if $user.is_company} thiscompany{/if}" id="fieldsBlock">
            <div class="companyFields">
                <div class="formLine">
                    <label class="fielName">Название организации</label><br>
                    {$user->getPropertyView('company')}
                </div>                            
                <div class="formLine">
                    <label class="fielName">ИНН</label><br>
                    {$user->getPropertyView('company_inn')}
                </div>                                
            </div>
            <div class="formLine">
                <label class="fielName">Фамилия</label><br>
                {$user->getPropertyView('surname')}
            </div>                    
            <div class="formLine">
                <label class="fielName">Имя</label><br>
                {$user->getPropertyView('name')}
            </div>
            <div class="formLine">
                <label class="fielName">Отчество</label><br>
                {$user->getPropertyView('midname')}
            </div>
            <div class="formLine">
                <label class="fielName">Телефон</label><br>
                {$user->getPropertyView('phone')}
            </div>
            <div class="formLine">
                <label class="fielName">E-mail</label><br>
                {$user->getPropertyView('e_mail')}
            </div>
            {if $conf_userfields->notEmpty()}
                {foreach $conf_userfields->getStructure() as $fld}
                <div class="formLine">
                <label class="fielName">{$fld.title}</label><br>
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
        <div class="half fright">
            <div class="formLine alignRight">
                <br>
                <span class="inputHeight">
                    <label for="changepass">Сменить пароль</label><input type="checkbox" name="changepass" id="changepass" value="1" {if $user.changepass}checked{/if}>
                </span>
            </div>
            <div class="changePass {if !$user.changepass}hidden{/if}">
                <div class="formLine">
                    <label class="fielName">Текущий пароль</label><br>
                    <input type="password" name="current_pass" {if count($user->getErrorsByForm('pass'))}class="has-error"{/if}>
                    <span class="formFieldError">{$user->getErrorsByForm('pass', ',')}</span>
                </div>
                <div class="formLine">
                    <label class="fielName">Новый пароль</label><br>
                    <input type="password" name="openpass" {if count($user->getErrorsByForm('pass'))}class="has-error"{/if}>
                    <span class="formFieldError">{$user->getErrorsByForm('openpass', ',')}</span>
                </div>                        
                <div class="formLine">
                    <label class="fielName">Повторить пароль</label><br>
                    <input type="password" name="openpass_confirm" {if count($user->getErrorsByForm('openpass'))}class="has-error"{/if}>
                </div>
            </div>
        </div>
    </div>
    <div class="buttons cboth">
        <input type="submit" value="Сохранить">
    </div>    
</form>
*}
<script type="text/javascript">
    $(function() {
        $('#changePass').change(function() {
            $('.changePass').toggleClass('hidden', !this.checked);
        });            
        
        $('.profile .companyType input').click(function() {
            $('#fieldsBlock').toggleClass('thiscompany', $(this).val() == 1);
        });
    });        
</script>    