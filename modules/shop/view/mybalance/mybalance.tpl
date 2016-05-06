<ul class="balanceLine">
    <li class="balance">
        <span class="cap">Баланс:</span> <strong>{$current_user->getBalance(true, true)}</strong>
    </li>
    <li class="addfunds">
        <a href="{$router->getUrl('shop-front-mybalance', [Act=>addfunds])}"><img src="{$THEME_IMG}/addfunds.png">Пополнить баланс</a>
    </li>
</ul>

<br><br>
<h2><span>История операций</span></h2>

<table class="orderList balanceTable">
<thead>
    <tr>
        <th></th>
        <th></th>
        <th class="addFundsHead">Приход</th>
        <th class="takeFundsHead">Расход</th>
    </tr>
</thead>
<tbody>
{foreach from=$list item=item}
    <tr>
        <td class="date">№ {$item.id}<br>{$item.dateof|date_format:"d.m.Y H:i"}</td>
        <td class="reason">{$item->reason}</td>
        <td>
            {* Приход *}
            {if !$item->order_id && $item->cost > 0}
                <span class="scost">{$item->getCost(true, true)}</span>
            {/if}
        </td>
        <td>
            {* Расход *}
            
            {if $item->order_id}
                <span class="tcost">-{$item->getCost(true, true)}</span>
            {else}
                {if $item->cost < 0}
                    <span class="tcost">{$item->getCost(true, true)}</span>
                {/if}
            {/if}
        </td>
    </tr>
{/foreach}
</tbody>
</table>
<br><br>
{include file="%THEME%/paginator.tpl"}

