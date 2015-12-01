{static_call var=warehouses callback=['\Catalog\Model\WareHouseApi', 'getWarehousesList']}
<div class="offer-stock-num">
    {foreach $warehouses as $warehouse}
        <p class="label">{$warehouse.title}</p>
        <input name="stock_num[{$warehouse.id}]" type="text" value="{$elem.stock_num[$warehouse.id]}"/><br/>
    {/foreach}
</div>