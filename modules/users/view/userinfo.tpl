{if $user['id']<0}
    Неавторизованный пользователь
{else}
    <table class="sub-table" border="1">
        <tr>
            <td>ID:</td>
            <td>{$user.id} (<a href="/{$Setup.ADMIN_SECTION}/musers_adm_ctrl/?do=edit&id={$user.id}" target="_blank">перейти</a>)</td>
        </tr>    
        <tr>
            <td>Логин:</td>
            <td>{$user.login}</td>
        </tr>
        <tr>
            <td>Ф.И.О:</td>
            <td>{$user.surname} {$user.name} {$user.midname}</td>
        </tr>
        {if !empty($user.e_mail)}
        <tr>
            <td>E-mail:</td>
            <td>{$user.e_mail}</td>
        </tr>
        {/if}
        {if !empty($user.phone)}
        <tr>
            <td>Телефон:</td>
            <td>{$user.phone}</td>
        </tr>
        {/if}
        {if !empty($user.company)}
        <tr>
            <td>Компания:</td>
            <td>{$user.company}</td>
        </tr>        
        {/if}
    </table>
{/if}