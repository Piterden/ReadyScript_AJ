<h2><span>Подтверждение оплаты</span></h2>

{if $api->hasError()}
    <div class="pageError">
        {foreach from=$api->getErrors() item=item}
        <p>{$item}</p>
        {/foreach}
    </div>
{/if}

<table class="confirmPayTable">
    <tr>
        <td class="key">Сумма</td>
        <td class="val"><span class="scost">{$transaction->getCost(true, true)}</span></td>
    </tr>
    <tr>
        <td class="key">Назначение платежа</td>
        <td class="val">{$transaction->reason}</td>
    </tr>    
    <tr>
        <td class="key">Источник</td>
        <td class="val">Лицевой счет</td>
    </tr>        
</table>

{if !$api->hasError()}
<form method="post">
    <button type="submit" class="formSave">Оплатить</button>
</form>
{/if}
