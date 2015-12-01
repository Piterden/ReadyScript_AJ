<pre>
Уважаемый, администратор! На сайте {$url->getDomainStr()} инициирован платеж.

<p>Сведения о клиенте:</p>

<table>
    <tr>
        <td>
           id транзакции 
        </td>
        <td>
           <b>{$data->transaction.id}</b>  
        </td>
    </tr>
    <tr>
        <td>
           Имя 
        </td>
        <td>
           <b>{$data->user.name}</b>  
        </td>
    </tr>
    <tr>
        <td>
           Фамилия 
        </td>
        <td>
           <b>{$data->user.surname}</b>  
        </td>
    </tr>
    <tr>
        <td>
           Сумма пополнения баланса 
        </td>
        <td>
           <b>{$data->transaction.cost}</b> 
        </td>
    </tr>
</table>

<div>
    <a href="{$router->getAdminUrl(null, null,'shop-transactionctrl', true)}">Перейти к просмотру</a>
</div>

Автоматическая рассылка {$url->getDomainStr()}.
</pre>