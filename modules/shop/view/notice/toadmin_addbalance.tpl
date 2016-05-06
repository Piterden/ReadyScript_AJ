<p>Уважаемый, администратор! На сайте {$url->getDomainStr()} пополнен баланс пользователем.</p>

<p><strong>Сведения о клиенте:</strong></p>

<table cellpadding="5" border="1" bordercolor="#969696" style="border-collapse:collapse; border:1px solid #969696">
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

<p><a href="{$router->getAdminUrl(null, null,'shop-transactionctrl', true)}">Перейти к просмотру</a></p>
<p>Автоматическая рассылка {$url->getDomainStr()}.</p>