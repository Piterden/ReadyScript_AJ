<p>Уважаемый клиент!</p>
<p>Скачать оплаченные файлы по заказу №{$data->order.order_num} от {$data->order.dateof|date_format:"%d.%m.%Y"}
можно по следующим ссылкам:</p>

<table style="border-collapse:collapse;">
    <thead>
        <tr>
            <td style="border-bottom:1px solid #aaa;padding:15px;background-color:#F5F5F5;">Файл</td>
            <td style="border-bottom:1px solid #aaa;padding:15px;background-color:#F5F5F5;">Описание</td>
            <td style="border-bottom:1px solid #aaa;padding:15px;background-color:#F5F5F5;">Ссылка</td>
        </tr>
    </thead>
    <tbody>
        {foreach $data->files as $file}
        <tr>
            <td style="border-bottom:1px solid #aaa;padding:15px;">{$file.name}</td>
            <td style="border-bottom:1px solid #aaa;padding:15px;">{$file.description|default:"нет"}</td>
            <td style="border-bottom:1px solid #aaa;padding:15px;"><a href="{$file->getUrl(true)}">скачать</a></td>
        </tr>
        {/foreach}
    </tbody>
</table>

<p>Ссылки на данные файлы также доступны в личном кабинете в разделе Мои заказы.</p>

<p>С Наилучшими пожеланиями,<br>
        Администрация интернет-магазина {$url->getDomainStr()}</p>