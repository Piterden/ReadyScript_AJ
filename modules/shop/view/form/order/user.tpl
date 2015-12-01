{if isset($user.id) && $user.id>0}                 
    <input type="hidden" name="user_id" value="{$user.id}"/>
    <table class="order-table">
        <tr class="{cycle values=$hl name="user"}">
            <td class="otitle">
                Фамилия Имя Отчество:
            </td>
            <td>
                <a href="{adminUrl mod_controller="users-ctrl" do="edit" id=$user.id}" target="_blank">{$user.surname} {$user.name} {$user.midname} ({$user.id})</a>&nbsp; 
                <a class="all-user-orders" href="{adminUrl mod_controller="shop-orderctrl" f=["user_id" => $user.id] do=false}">все заказы ({$user_num_of_order|default:0})</a>
            {if $user.is_company}<div class="company_info">{$user.company}, ИНН: {$user.company_inn}</div>{/if} <a class="crud-add all-user-orders" href="{adminUrl do=userDialog}">Указать другого</a>
            </td>
        </tr>
        <tr class="{cycle values=$hl name="user"}">
            <td class="otitle">
                Пол:
            </td>
            <td>{$user.__sex->textView()}</td>
        </tr>
        <tr class="{cycle values=$hl name="user"}">
            <td>Телефон:</td>
            <td>{$user.phone}</td>
        </tr>
        <tr class="{cycle values=$hl name="user"}">
            <td>E-mail:</td>
            <td>{$user.e_mail}</td>
        </tr>
        {foreach from=$user->getUserFields() item=item name=uf}
            <tr class="{cycle values=$hl name="user"}">
                <td>{$item.title}</td>
                <td>{$item.current_val}</td>
            </tr>                
        {/foreach}
    </table>
{else}
    <p class="emptyOrderBlock">Пользователь не указан. <a class="crud-add" href="{adminUrl do=userDialog}">Указать пользователя</a>.</p>
    <table class="order-table">
        <tr class="{cycle values=$hl name="user"}">
            <td class="otitle">
                Фамилия Имя Отчество:
            </td>
            <td>{$order.__user_fio->formView()}</td>
        </tr>
        <tr class="{cycle values=$hl name="user"}">
            <td class="otitle">
                E-mail:
            </td>
            <td>{$order.__user_email->formView()}</td>
        </tr>
        <tr class="{cycle values=$hl name="user"}">
            <td>Телефон:</td>
            <td>{$order.__user_phone->formView()}</td>
        </tr>
    </table>
{/if}