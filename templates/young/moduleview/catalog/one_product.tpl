{$check_quantity=$shop_config.check_quantity}
<li {$product->getDebugAttributes()} data-id="{$product.id}">
    <div class="hoverLayer">
        <div class="underMain">
            {if $shop_config}
                {if $product->shouldReserve()}
                    <a data-href="{$router->getUrl('shop-front-reservation', ["product_id" => $product.id])}" class="redButton inDialog reservation">Заказать</a>
                {else}        
                    {if $check_quantity && $product.num<1}
                        <span class="noProduct visible">Нет в наличии</span>
                    {else}
                        {if $product->isOffersUse() || $product->isMultiOffersUse()}
                            <span data-href="{$router->getUrl('shop-front-multioffers', ["product_id" => $product.id])}" class="showMultiOffers inDialog noShowCart">В корзину</span>
                        {else}
                            <a data-href="{$router->getUrl('shop-front-cartpage', ["add" => $product.id])}" class="addToCart noShowCart" data-add-text="Добавлено">В корзину</a>
                        {/if}
                    {/if}
                {/if}
            {/if}                        
            <a class="compare {if $product->inCompareList()} inCompare{/if}"><span>Сравнить</span><span class="already">Добавлено<br><i class="ext doCompare">Сравнить</i></span></a>
        </div>
    </div>
    <div class="mainLayer">
        {if $product->inDir('new')}<i class="new"></i>{/if}
        {$main_image=$product->getMainImage()}
        <a href="{$product->getUrl()}" class="image"><img src="{$main_image->getUrl(220,220)}" alt="{$main_image.title|default:"{$product.title}"}"/></a>
        <a href="{$product->getUrl()}" class="title">{$product.title}</a>
        <div class="price">{$product->getCost()} {$product->getCurrency()} 
        {$last_price=$product->getOldCost()}
        {if $last_price>0}<span class="last">{$last_price} {$product->getCurrency()}</span>{/if}</div>
    </div>                
</li>