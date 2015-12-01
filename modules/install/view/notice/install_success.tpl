{t alias="Уведомление об успешной установке"}
<pre>ReadyScript успешно установлен на сайте {$url->getDomainStr()}!

Ваши данные для доступа в административную часть сайта:

E-mail (Логин): {$data->data.supervisor_email}
Пароль: {$data->data.supervisor_password}

Перейдите по ссылке, чтобы попасть в административную панель <a href="http://{$data->data.domain}/{$data->data.admin_section}/">http://{$data->data.domain}/{$data->data.admin_section}/</a>
Перейдите по ссылке, чтобы попасть в клиентскую часть сайта <a href="http://{$data->data.domain}">http://{$data->data.domain}</a>

Спасибо, что выбрали ReadyScript.</pre>{/t}