{$catalog_config=ConfigLoader::byModule('catalog')}
{if $catalog_config.use_offer_unit}
    {* Подгрузим единицы измерения *}
    {static_call var=units callback=['\Catalog\Model\UnitApi','selectList']}
{/if}
{static_call var=warehouses callback=['\Catalog\Model\WareHouseApi', 'getWarehousesList']}

<div class="offer-container">
    {include file="%catalog%/adminblocks/offerblock/offer_main.tpl"}
</div>

<div id="external-offers">
    <h3>Дополнительные комплектации</h3>
    <div id="ext-offers">
        {include file="%catalog%/adminblocks/offerblock/offer_ext.tpl"}
    </div>
</div>