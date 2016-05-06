{addjs file="jquery.changeoffer.js"}
{addjs file="jcarousel/jquery.jcarousel.min.js"}
{addjs file="list_product.js"}
{assign var=shop_config value=ConfigLoader::byModule('shop')}
{assign var=check_quantity value=$shop_config->check_quantity}
{$list = $this_controller->api->addProductsMultiOffersInfo($list)}
{if $dirs}
    {$shop_config=ConfigLoader::byModule('shop')}
    <div class="topProducts">
        <div class="tabs mt40 tabProducts">
            <ul class="tabList">
                {foreach $dirs as $dir}
                    <li ><a {if $dir@first}class="act"{/if} data-tab=".frame{$dir@iteration}">{$dir.name}</a></li>
                {/foreach}
            </ul>
            <div class="botLine"></div>
            {foreach $dirs as $dir}
                <div class="productWrap tabFrame {if $dir@first}act{/if} frame{$dir@iteration}">
                    <ul class="productList">
                        {foreach $products_by_dirs[$dir.id] as $product}
                        {assign var=imagelist value=$product->getImages(false)}   
                        <li {$product->getDebugAttributes()} data-id="{$product.id}" class="{if count($imagelist)>1}photoView{/if}{if $product->isOffersUse() || $product->isMultiOffersUse()} showOfferSelect{/if}">
                            <div class="hoverBlock">
                                <div class="galleryWrap{if count($imagelist)>4} scrollable{/if}">
                                    <a class="control up"></a>
                                    <div class="gallery">
                                        <ul class="list" id="list">
                                            {foreach from=$imagelist key=n item=image name="allphotos"}
                                            <li data-change-preview="{$image->getUrl(141,185,'xy')}" {if $smarty.foreach.allphotos.first}class="act"{/if}><a href="{$product->getUrl()}#photo-{$n}" class="imgWrap"><img src="{$image->getUrl(64, 64, 'xy')}" alt="{$image.title} фото-{$n}"/></a></li>
                                            {/foreach}                            
                                        </ul>
                                    </div>
                                    <a class="control down"></a>
                                </div>
                            </div>                       
                            <div class="dataBlock">
                                <a href="{$product->getUrl()}" class="pic">
                                <span class="labels">
                                    {foreach from=$product->getMySpecDir() item=spec}
                                    {if $spec.image}
                                        <img src="{$spec->__image->getUrl(62,62, 'xy')}">
                                    {/if}
                                    {/foreach}
                                </span>
                                {$main_image=$product->getMainImage()}
                                <img src="{$main_image->getUrl(141, 185, 'xy')}" class="middlePreview" alt="{$main_image.title|default:"{$product.title}"}"/></a>
                                <div class="info extra">
                                    <a href="{$product->getUrl()}" class="titleGroup">
                                        <h3>{$product.title}</h3>
                                    </a>
                                    <p class="group">
                                        <span class="scost">{$product->getCost()} {$product->getCurrency()}</span>
                                        <span class="rating" title="рейтинг: {$product->getRatingBall()}"><span class="value" style="width:{$product->getRatingPercent()}%"></span></span>
                                        <br><span class="comments">оценок {$product->getCommentsNum()}</span>
                                    </p>
                                </div>
                                
                                {if $shop_config}
                                    {if $product->shouldReserve()}
                                        <a data-href="{$router->getUrl('shop-front-reservation', ["product_id" => $product.id])}" class="cartButton inDialog reserv" title="Заказать">&nbsp;</a>
                                    {else}        
                                        {if $check_quantity && $product.num<1}
                                            <span class="cartButton unobt" title="Нет в наличии">&nbsp;</span>
                                        {else}
                                            <span data-href="{$router->getUrl('shop-front-multioffers', ["product_id" => $product.id])}" class="cartButton showComplekt inDialog" title="В корзину">&nbsp;</span>
                                            <a data-href="{$router->getUrl('shop-front-cartpage', ["add" => $product.id])}" class="cartButton addToCart noShowCart" title="В корзину">&nbsp;</a>
                                        {/if}
                                    {/if}
                                {/if}
                                <a class="compare{if $product->inCompareList()} inCompare{/if}"><span>сравнить</span></a>
                            </div>
                        </li>                                          
                        {/foreach}
                    </ul>
                    <a class="onemore" href="{$dir->getUrl()}">Посмотреть все товары</a>
                </div>
                
            {/foreach}
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.tabProducts').activeTabs();    
        });
    </script>
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