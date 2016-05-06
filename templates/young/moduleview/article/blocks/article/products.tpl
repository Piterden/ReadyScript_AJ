{if !empty($products)}
    {$shop_config=ConfigLoader::byModule('shop')}
    {$products = $this_controller->api->addProductsMultiOffersInfo($products)}

    <h3>Прикреплённые товары</h3>
    <ul class="products">
        {foreach $products as $product}
            {include file="%catalog%/one_product.tpl" shop_config=$shop_config product=$product}
        {/foreach}    
    </ul>
    <div class="clearLeft"></div>
{/if}