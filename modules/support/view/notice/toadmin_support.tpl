<pre>
Уважаемый, администратор! В поддержку поступило сообщение (отправленное на сайте {$url->getDomainStr()}).
{assign var=topic value=$data->support->getTopic()}
{assign var=user value=$data->support->getUser()}
Дата: {$data->support.dateof|date_format}
Тема переписки: <strong>{$topic.title}</strong>

<h3>Пользователь</h3>
Ф.И.О.: <strong>{$user->getFio()}</strong>
Телефон: <strong>{$user.phone}</strong>
E-mail: <strong>{$user.e_mail}</strong>

<h3>Сообщение</h3>
{$data->support.message}

<a href="{$router->getAdminUrl(false, ['id' => $topic.id], 'support-supportctrl', true)}">Ответить</a>

Автоматическая рассылка {$url->getDomainStr()}.
</pre>