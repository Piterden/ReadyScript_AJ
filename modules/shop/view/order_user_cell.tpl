{$order=$cell->getRow()}
{if $order.user_id>0}
    {$user=$order->getUser()}
    {$user->getFio()} <span class="cell-sgray">({$cell->getValue()})</span>
    {if $user.is_company}<div class="cell-sgray">{$user.company}, ИНН: {$user.company_inn}</div>{/if}
{else}
    {if !empty($order.user_fio)}{$order.user_fio}{else}Пользователь не указан{/if}  <span class="cell-sgray">(Без регистрации)</span>
{/if}
