<p>Вы успешно зарегистрированы! Мы рады приветствовать Вас на сайте {$url->getDomainStr()}.</p>

<p>Логин: {$data->user.login}<br>
Пароль: {$data->password}</p>

<p>Используйте этот логин и пароль для входа в личный кабинет по адресу <a href="{$router->getUrl('users-front-profile', [], true)}">{$router->getUrl('users-front-profile', [], true)}</a>.<br>
В личном кабинете можно посмотреть историю Ваших заказов, их текущие статусы, а также написать письмо в службу поддержки клиентов.</p>

<p>Чтобы сократить время оформления заказа, Вы можете использовать этот логин и пароль при следующем оформлении заказа.<br>
Пожалуйста обратите внимание, что Вы можете изменять пароль в любое время редактируя ваш Профиль.</p>

<p>С наилучшими пожеланиями, <br>
     администрация интернет магазина {$url->getDomainStr()}.</p>