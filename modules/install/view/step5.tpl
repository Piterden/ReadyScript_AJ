{extends file="%install%/wrap.tpl"}
{block name="content"}
<div class="congratulation">Поздравляем! ReadyScript успешно установлен.</div>
<h3>Ваши данные</h3>
<div class="authdata">
    <span class="key">E-mail (Логин):</span> <span class="value">{$email}</span><br>
    <span class="key">Пароль:</span> <span class="value password" style="display:none">{$password}</span><a class="show-password">показать пароль</a>
</div>

<br class="clearboth">
<br>
<br>
<p>Перейдите по ссылке, чтобы попасть в административную панель <a href="http://{$Setup.DOMAIN}{$Setup.FOLDER}/{$admin_section}/">http://{$Setup.DOMAIN}{$Setup.FOLDER}/{$admin_section}/</a></p>
<p>Перейдите по ссылке, чтобы попасть в клиентскую часть сайта <a href="http://{$Setup.DOMAIN}{$Setup.FOLDER}/">http://{$Setup.DOMAIN}{$Setup.FOLDER}/</a></p>
<p>Эти сведения также отправлены на {$email}</p>
<br>
<p>Спасибо, что выбрали ReadyScript</p>
<p class="center-box">
    <a class="complete" href="http://{$Setup.DOMAIN}{$Setup.FOLDER}/">Перейти на сайт</a>
</p>
{/block}