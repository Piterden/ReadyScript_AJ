{if !$cell->getRow()->checkSign()}
    <b style="color:red">Неверная подпись</b>
{else}
    {if $cell->getRow()->personal_account && $cell->getRow()->status == 'new' && $cell->getRow()->order_id == 0}
        <a class="crud-get uline" href="{$router->getAdminUrl('setTransactionSuccess', ['id' => $cell->getRow()->id])}">начислить средства</a>
    {/if}
{/if}


