<h3>Настройка аккаунта Яндекс.Касса</h3>

<p>
<small>Для работы с Яндекс.Кассой необходимо наличие сертификата (как минимум самоподписного) 
у сайта для установления SSL соединения.</small>
</p>

<b>Кодировка: </b> Только UTF-8    <br>
<b>Тип оплаты: </b> Фиксированная оплата

<br><br>

<b>paymentAvisoUrl: </b><br>
https://{$Setup.DOMAIN}{$router->getUrl('shop-front-onlinepay', [Act=>result, PaymentType=>$payment_type->getShortName()])}

<br><br>

<b>checkUrl: </b><br>
https://{$Setup.DOMAIN}{$router->getUrl('shop-front-onlinepay', [Act=>result, PaymentType=>$payment_type->getShortName()])}

<br><br>

<b>shopSuccessURL: </b><br>
{$router->getUrl('shop-front-onlinepay', [Act=>success, PaymentType=>$payment_type->getShortName()], true)}

<br><br>

<b>shopFailURL: </b><br>
{$router->getUrl('shop-front-onlinepay', [Act=>fail, PaymentType=>$payment_type->getShortName()], true)}




