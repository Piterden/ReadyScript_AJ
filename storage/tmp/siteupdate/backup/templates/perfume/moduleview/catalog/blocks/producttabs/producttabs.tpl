{if $dirs}
{$shop_config=ConfigLoader::byModule('shop')}
{$check_quantity=$shop_config.check_quantity}
<div class="productList rsTabs">
    <ul class="tabList">
        {foreach $dirs as $dir}
        <li {if $dir@first}class="act"{/if} data-href=".frame{$dir@iteration}"><a>{$dir.name}</a></li>
        {/foreach}
    </ul>
    {foreach $dirs as $dir}
    <div class="tab {if $dir@first}act{/if} frame{$dir@iteration}">
        <div class="tabCaption"><span class="sel">{$dir.name}</span></div>
        <div class="scrollBlock">
            <ul class="scrollItems products">
                {$products_by_dirs[$dir.id]=$this_controller->api->addProductsMultiOffersInfo($products_by_dirs[$dir.id])}
                {foreach $products_by_dirs[$dir.id] as $product}
                {assign var=imagelist value=$product->getImages(false)}                
                <li {$product->getDebugAttributes()} data-id="{$product.id}" {if count($imagelist)>1}class="photoView"{/if}>
                    <div class="hoverLayer">
                        <div class="gallery{if count($imagelist)>3} scrollable{/if}">
                            <a class="control up"></a>
                            <a class="control down"></a>
                            <div class="scrollBox">
                                <ul class="items">
                                    {foreach $imagelist as $n=>$image}
                                    <li data-change-preview="{$image->getUrl(226,236)}" {if $image@first}class="act"{/if}><a href="{$product->getUrl()}#photo-{$n}" class="imgWrap"><img src="{$image->getUrl(56, 56)}" alt="{$image.title|default:"{$product.title}"} фото-{$n}"/></a></li>
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
                        <a href="{$product->getUrl()}" class="image"><span class="markers">{if $product->inDir('new')}<img src="{$THEME_IMG}/newest.png">{/if}</span><img src="{$main_image->getUrl(226, 236)}" class="middlePreview" alt="{$main_image.title|default:"{$product.title}"}"/></a>
                        <a href="{$product->getUrl()}" class="title">{$product.title}</a>
                        <p class="price">{$product->getCost()} {$product->getCurrency()}</p>
                    </div>
                </li>
                {/foreach}
            </ul>
        </div>
    </div>
    {/foreach}
    <br class="clear">    
</div>
{else}
    {include file="%THEME%/block_stub.tpl"  class="blockProductTabs" do=[
        [
            'title' => t("Добавьте категории с товарами"),
            'href' => {adminUrl do=false mod_controller="catalog-ctrl"}
        ],
        [
            'title' => t("Настройте блок"),
            'href' => {$this_controller->getSettingUrl()},
            'class' => 'crud-add'
        ]
    ]}
{/if}