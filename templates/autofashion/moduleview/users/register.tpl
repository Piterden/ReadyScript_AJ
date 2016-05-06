<form method="POST" action="{$router->getUrl('users-front-register')}" class="authorization register formStyle container">
    <input type="hidden" name="referer" value="{$referer}">
    {$this_controller->myBlockIdInput()}
    <h1 data-dialog-options='{ "width": "630" }'>Регистрация пользователя</h1>
    {if count($user->getNonFormErrors())>0}
        <div class="pageError">
            {foreach $user->getNonFormErrors() as $item}
                <p>{$item}</p>
            {/foreach}
        </div>
    {/if}
    <div class="userType">
        <div class="row">
            <div class="col-md-12">
                <input type="radio" id="ut_user" name="is_company" value="0" {if !$user.is_company}checked{/if}>
                <label for="ut_user">Частное лицо</label>
            </div>
            <div class="col-md-12">
                <input type="radio" id="ut_company" name="is_company" value="1" {if $user.is_company}checked{/if}>
                <label for="ut_company">Компания</label>
            </div>
        </div>
    </div>        
    <div class="forms row">                        
        <div class="oh{if $user.is_company} thiscompany{/if}" id="fieldsBlock">
            <div class="col-md-16 col-md-offset-4">
                <div class="companyFields">
                    <div class="formLine">
                        <label class="fieldName">Название организации</label>
                        {$user->getPropertyView('company', ['size'=>'0','placeholder'=>'Название организации'])}
                    </div>                            
                </div>
                <div class="formLine">
                    <label class="fieldName">Имя</label>
                    {$user->getPropertyView('name', ['size'=>'0','placeholder'=>'Имя'])}
                </div>            
                <div class="formLine">
                    <label class="fieldName">Фамилия</label>
                    {$user->getPropertyView('surname', ['size'=>'0','placeholder'=>'Фамилия'])}
                </div>             
                <div class="formLine">
                    <label class="fieldName">Отчество</label>
                    {$user->getPropertyView('midname', ['size'=>'0','placeholder'=>'Отчество'])}
                </div>                                
                <div class="formLine">
                    <label class="fieldName">Телефон</label>
                    {$user->getPropertyView('phone', ['size'=>'0','placeholder'=>'Телефон'])}
                </div>                        
                <div class="companyFields">
                    <div class="formLine">
                        <label class="fieldName">ИНН</label>
                        {$user->getPropertyView('company_inn', ['size'=>'0','placeholder'=>'ИНН'])}
                    </div>                                
                </div>        
                <div class="formLine">
                    <label class="fieldName">E-mail</label>
                    {$user->getPropertyView('e_mail', ['size'=>'0','placeholder'=>'E-mail'])}
                </div>
                <div class="formLine">
                    <label class="fieldName">Пароль</label>
                    <input type="password" name="openpass" placeholder="Пароль" {if count($user->getErrorsByForm('openpass'))}class="has-error"{/if}>
                    <div class="formFieldError">{$user->getErrorsByForm('openpass', ',')}</div>                    
                </div>            
                <div class="formLine">
                    <label class="fieldName">Повтор пароля</label>
                    <input type="password" name="openpass_confirm" placeholder="Повторите пароль">
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
                {if $user.__captcha->isEnabled()}
                    <div class="formLine captcha">
                        <label class="fieldName">&nbsp;</label>
                        {$user->getPropertyView('captcha', ['size'=>'0','placeholder'=>'Защитный код'])}
                    </div>               
                {/if}            
            </div>
        </div>
        <div class="buttons cboth text-center col-md-16 col-md-offset-4">
            <button type="submit">Зарегистрироваться</button>
        </div> 
    </div>   

    <script type="text/javascript">
        $(function() {
            $('.userType input').click(function() {
                $('#fieldsBlock').toggleClass('thiscompany', $(this).val() == 1);
                if ($(this).closest('#colorbox')) $.colorbox.resize();
            });
        });
    </script>    
</form>