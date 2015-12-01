<h3>Настройка аккаунта Assist</h3>

URL для отправки результатов:<br>
<a target="_blank" href="{$router->getUrl('shop-front-onlinepay', [Act=>result, PaymentType=>$payment_type->getShortName()], true)}">
    <b>{$router->getUrl('shop-front-onlinepay', [Act=>result, PaymentType=>$payment_type->getShortName()], true)}</b>
</a>

<br><br>

URL_RETURN:<br>
<a target="_blank" href="{$SITE->getRootUrl(true)}">
    <b>{$SITE->getRootUrl(true)}</b>
</a>

<br><br>

URL_RETURN_OK:<br>
<a target="_blank" href="{$router->getUrl('shop-front-onlinepay', [Act=>success, PaymentType=>$payment_type->getShortName()], true)}">
    <b>{$router->getUrl('shop-front-onlinepay', [Act=>success, PaymentType=>$payment_type->getShortName()], true)}</b>
</a>

<br><br>
<table>
    <tr>
        <td>Тип протокола:&nbsp;&nbsp;</td>
        <td><b>POST</b></td>
    </tr>
    <tr>
        <td>Тип подписи:</td>
        <td><b>MD5</b></td>
    </tr>
</table>




