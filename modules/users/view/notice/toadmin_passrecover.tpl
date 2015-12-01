<pre>
Уважаемый, администратор!

На сайте {$url->getDomainStr()} пользователь восстановил пароль!

ID: {$data->user.id}
Ф.И.О.: {$data->user.name} {$data->user.surname} {$data->user.midname}
E-mail: {$data->user.e_mail}
Телефон: {$data->user.phone}
{if $data->user.is_company}Название организации: {$data->user.company}
ИНН: {$data->user.company_inn}
{/if}
-------------------------------------
Логин: {$data->user.login}
Пароль: {$data->password}

Автоматическая рассылка {$url->getDomainStr()}.
</pre>