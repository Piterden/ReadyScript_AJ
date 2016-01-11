<p>Уважаемый, администратор! В поддержку поступило сообщение (отправленное на сайте {$url->getDomainStr()}).</p>
{assign var=topic value=$data->support->getTopic()}
{assign var=user value=$data->support->getUser()}
<p>Дата: {$data->support.dateof|date_format}<br>
Тема переписки: <strong>{$topic.title}</strong></p>

<h3>Пользователь</h3>
Ф.И.О.: <strong>{$user->getFio()}</strong><br>
Телефон: <strong>{$user.phone}</strong><br>
E-mail: <strong>{$user.e_mail}</strong><br>

<h3>Сообщение</h3>
{$data->support.message}

<p><a href="{$router->getAdminUrl(false, ['id' => $topic.id], 'support-supportctrl', true)}">Ответить</a></p>

<p>Автоматическая рассылка {$url->getDomainStr()}.</p>