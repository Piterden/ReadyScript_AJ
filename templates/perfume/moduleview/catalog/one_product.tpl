{assign var=check_quantity value=$shop_config->check_quantity}
{assign var=imagelist value=$product->getImages(false)}
<li {$product->getDebugAttributes()} data-id="{$product.id}" {if count($imagelist)>1}class="photoView"{/if}>
    <div class="hoverLayer">
        <div class="gallery{if count($imagelist)>3} scrollable{/if}">
            <a class="control up"></a>
            <a class="control down"></a>
            <div class="scrollBox">
                <ul class="items">
                    {foreach $imagelist as $n=>$image}
                    <li data-change-preview="{$image->getUrl(226,236)}" {if $image@first}class="act"{/if}><a href="{$product->getUrl()}#photo-{$n}" class="imgWrap"><img src="{$image->getUrl(56, 56)}" alt="{$image.title}"/></a></li>
                    {/foreach}
                </ul>
            </div>
        </div>
        <div class="underMain">
            {if $shop_config}
                {if $product->shouldReserve()}
                    <a data-href="{$router->getUrl('shop-front-reservation', ["product_id" => $product.id])}" class="inDialog reserve">Заказать</a>
                {else}        
                    {if $check_quantity && $product.num<1}
                        <span class="unobtainable">Нет в наличии</span>
                    {else}
                        {if $product->isOffersUse() || $product->isMultiOffersUse()}
                            <span data-href="{$router->getUrl('shop-front-multioffers', ["product_id" => $product.id])}" class="cartButton showMultiOffers inDialog noShowCart">В корзину</span>
                        {else}
                            <a data-href="{$router->getUrl('shop-front-cartpage', ["add" => $product.id])}" class="cartButton addToCart noShowCart" data-add-text="Добавлено">В корзину</a>
                        {/if}                        
                    {/if}
                    
                {/if}
            {/if}
            <a class="compare{if $product->inCompareList()} inCompare{/if}"><span>Сравнить</span><span class="already">Добавлено</span></a>
        </div>
    </div>
    <div class="mainLayer">
        {$main_image=$product->getMainImage()}
        <a href="{$product->getUrl()}" class="image"><span class="markers">{if $product->inDir('new')}<img src="{$THEME_IMG}/newest.png" alt=""/>{/if}</span>
        <img src="{$main_image->getUrl(226, 236)}" class="middlePreview" alt="{$main_image.title|default:"{$product.title}"}"/></a>
        <a href="{$product->getUrl()}" class="title">{$product.title}</a>
        <p class="price">{$product->getCost()} {$product->getCurrency()}</p>
        <div class="starsLine">
            <span class="stars" title="рейтинг: {$product->getRatingBall()}"><i style="width:{$product->getRatingPercent()}%"></i></span>
            <a href="{$product->getUrl()}#comments" class="comments">{$product->getCommentsNum()}</a>
        </div>        
    </div>
</li>