{t alias="Уведомление об успешной установке"}
<p>ReadyScript успешно установлен на сайте {$url->getDomainStr()}!</p>

<p>Ваши данные для доступа в административную часть сайта:</p>

<p>E-mail (Логин): {$data->data.supervisor_email}<br>
Пароль: {$data->data.supervisor_password}</p>

<p>Перейдите по ссылке, чтобы попасть в административную панель <a href="http://{$data->data.domain}/{$data->data.admin_section}/">http://{$data->data.domain}/{$data->data.admin_section}/</a><br>
Перейдите по ссылке, чтобы попасть в клиентскую часть сайта <a href="http://{$data->data.domain}">http://{$data->data.domain}</a></p>

<p>Спасибо, что выбрали ReadyScript.</p>{/t}