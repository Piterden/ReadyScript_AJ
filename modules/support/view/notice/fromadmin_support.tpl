<p>Уважаемый, пользователь! Из службы поддержки поступило сообщение (отправленное с сайта {$url->getDomainStr()}).</p>
{assign var=topic value=$data->support->getTopic()}
{assign var=user value=$data->user}
<p>Дата: {$data->support.dateof}<br>
Тема переписки: <strong>{$topic.title}</strong></p>


<h3>Сообщение</h3>
{$data->support.message}

<p><a href="{$router->getUrl('support-front-support', ['Act' => 'ViewTopic', 'id' => $topic.id], true)}">Ответить</a></p>

<p>Автоматическая рассылка {$url->getDomainStr()}.</p>