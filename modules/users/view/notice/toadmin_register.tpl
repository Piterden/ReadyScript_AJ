<p>Уважаемый, администратор!</p>
<p>На сайте {$url->getDomainStr()} зарегистрирован пользователь!</p>

<p>ID: {$data->user.id}<br>
Ф.И.О.: {$data->user.name} {$data->user.surname} {$data->user.midname}<br>
E-mail: {$data->user.e_mail}<br>
Телефон: {$data->user.phone}<br>
{if $data->user.is_company}Название организации: {$data->user.company}<br>
ИНН: {$data->user.company_inn}<br>
{/if}
-------------------------------------<br>
Логин: {$data->user.login}<br>
Пароль: {$data->password}<br>

<p>Автоматическая рассылка {$url->getDomainStr()}.</p>