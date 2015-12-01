<pre>
Уважаемый, пользователь! Из службы поддержки поступило сообщение (отправленное с сайта {$url->getDomainStr()}).
{assign var=topic value=$data->support->getTopic()}
{assign var=user value=$data->user}
Дата: {$data->support.dateof}
Тема переписки: <strong>{$topic.title}</strong>


<h3>Сообщение</h3>
{$data->support.message}

<a href="{$router->getUrl('support-front-support', ['Act' => 'ViewTopic', 'id' => $topic.id], true)}">Ответить</a>

Автоматическая рассылка {$url->getDomainStr()}.
</pre>