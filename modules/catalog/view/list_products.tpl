{addjs file="jquery.changeoffer.js?v=2"}
{addjs file="jcarousel/jquery.jcarousel.min.js"}
{addjs file="list_product.js"}
{assign var=shop_config value=ConfigLoader::byModule('shop')}
{assign var=check_quantity value=$shop_config->check_quantity}
{$list = $this_controller->api->addProductsMultiOffersInfo($list)}
{$list = $this_controller->api->addProductsDirs($list)}

{if $no_query_error}
<div class="noQuery">
    Не задан поисковый запрос
</div>      
{else}
<div id="products" {if $shop_config}class="shopVersion"{/if}>
    {if $category.description}<div class="categoryDescription">{$category.description}</div>{/if}
    {if count($sub_dirs)}{assign var=one_dir value=reset($sub_dirs)}{/if}
    {if empty($query) || (count($sub_dirs) && $dir_id != $one_dir.id)}
    <nav class="subCategory">
        {foreach from=$sub_dirs item=item}
        <a href="{urlmake category=$item._alias p=null f=null bfilter=null}">{$item.name}</a>
        {/foreach}
    </nav>
    {/if}

    {if count($list)}
    <div class="viewOptions">
        <a href="{urlmake viewAs=table}" class="viewAs table{if $view_as == 'table'} act{/if}" rel="nofollow"></a>
        <a href="{urlmake viewAs=blocks}" class="viewAs blocks{if $view_as == 'blocks'} act{/if}" rel="nofollow"></a>                
        Сортировать по:&nbsp;&nbsp;                  
        <span class="lineListBlock sortBlock">
            <a class="lineTrigger rs-parent-switcher">{if $cur_sort=='dateof'}по дате
                                                      {elseif $cur_sort=='rating'}популярности
                                                      {elseif $cur_sort=='title'}названию
                                                      {elseif $cur_sort=='num'}наличию
                                                      {elseif $cur_sort=='rank'}релевантности
                                                      {else}цене{/if}</a>
            <ul class="lineList">
                <li><a href="{urlmake sort="cost" nsort=$sort.cost}" class="item{if $cur_sort=='cost'} {$cur_n}{/if}" rel="nofollow"><i>цене</i></a></li>
                <li><a href="{urlmake sort="rating" nsort=$sort.rating}" class="item{if $cur_sort=='rating'} {$cur_n}{/if}" rel="nofollow"><i>популярности</i></a></li>
                <li><a href="{urlmake sort="dateof" nsort=$sort.dateof}" class="item{if $cur_sort=='dateof'} {$cur_n}{/if}" rel="nofollow"><i>дате</i></a></li>
                <li><a href="{urlmake sort="num" nsort=$sort.num}" class="item{if $cur_sort=='num'} {$cur_n}{/if}" rel="nofollow"><i>наличию</i></a></li>
                <li><a href="{urlmake sort="title" nsort=$sort.title}" class="item{if $cur_sort=='title'} {$cur_n}{/if}" rel="nofollow"><i>названию</i></a></li>
                {if $can_rank_sort}
                <li><a href="{urlmake sort="rank" nsort=$sort.rank}" class="item{if $cur_sort=='rank'} {$cur_n}{/if}" rel="nofollow"><i>релевантности</i></a></li>
                {/if}
            </ul>
        </span>
    </div>

    <div class="pages-line before">
        <div class="pageSizeBlock">
            Показывать по:&nbsp;&nbsp; 
            <span class="lineListBlock collapse720">
                <a class="lineTrigger rs-parent-switcher">{$page_size}</a>
                <ul class="lineList">
                    {foreach from=$items_on_page item=item}
                    <li><a href="{urlmake pageSize=$item}" class="item{if $page_size==$item} act{/if}"><i>{$item}</i></a></li>
                    {/foreach}
                </ul>
            </span>
        </div>
        {include file="%THEME%/paginator.tpl"}
        <div class="clearboth"></div>
    </div>

    <section class="topProducts">
        <div class="productWrap">
            {if $view_as == 'blocks'}
            <ul class="productList">
                {foreach from=$list item=product}
                    {assign var=imagelist value=$product->getImages(false)}                
                    <li {$product->getDebugAttributes()} data-id="{$product.id}" class="{if count($imagelist)>1}photoView{/if}{if $product->isOffersUse() || $product->isMultiOffersUse()} showOfferSelect{/if}">
                        <div class="hoverBlock">
                            <div class="galleryWrap{if count($imagelist)>4} scrollable{/if}">
                                <a class="control up"></a>
                                <div class="gallery">
                                    <ul class="list" id="list">
                                        {foreach from=$imagelist key=n item=image name="allphotos"}
                                        <li data-change-preview="{$image->getUrl(141,185,'xy')}" {if $smarty.foreach.allphotos.first}class="act"{/if}><a href="{$product->getUrl()}#photo-{$n}" class="imgWrap"><img src="{$image->getUrl(64, 64, 'xy')}" alt="{$image.title|default:"{$product.title} фото {$n}"}"/></a></li>
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
                                    <img src="{$spec->__image->getUrl(62,62, 'xy')}" alt=""/>
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
            {else}
            <ul class="productListTable">
                {foreach from=$list item=product}
                    <li {$product->getDebugAttributes()} data-id="{$product.id}">
                        {$main_image=$product->getMainImage()}
                        <a href="{$product->getUrl()}" class="pic"><img src="{$main_image->getUrl(74, 66, 'xy')}" alt="{$main_image.title|default:"{$product.title}"}"/></a>
                        <div class="info extra">
                            <a href="{$product->getUrl()}" class="titleGroup">
                                <h3>{$product.title}</h3>
                                <p class="descr">{$product.short_description}</p>
                            </a>
                            <span class="scost">{$product->getCost()} {$product->getCurrency()}</span>

                            {if $product->shouldReserve()}
                                <a href="{$router->getUrl('shop-front-reservation', ["product_id" => $product.id])}" class="cartButton inDialog reserv" title="Заказать">&nbsp;</a>
                            {else}        
                                {if $check_quantity && $product.num<1}
                                    <span class="cartButton unobt" title="Нет в наличии">&nbsp;</span>
                                {else}
                                    {if $product->isOffersUse() || $product->isMultiOffersUse()}
                                        <span data-href="{$router->getUrl('shop-front-multioffers', ["product_id" => $product.id])}" class="cartButton showComplekt inDialog noShowCart" title="В корзину">&nbsp;</span>
                                    {else}
                                        <a href="{$router->getUrl('shop-front-cartpage', ["add" => $product.id])}" class="cartButton addToCart noShowCart" title="В корзину">&nbsp;</a>
                                    {/if}
                                {/if}
                            {/if}                        
                            
                            <a class="compare{if $product->inCompareList()} inCompare{/if}"><span>сравнить</span></a>
                        </div>
                    </li>
                {/foreach}
            </ul>
            {/if}
        </div>
    {else}    
        <div class="noProducts">
            {if !empty($query)}
            Извините, ничего не найдено
            {else}
            В данной категории нет ни одного товара
            {/if}
        </div>
    {/if}
    <div class="clear"></div>
    </section> <!-- .topProducts -->

    <div class="pages-line">
        {include file="%THEME%/paginator.tpl"}
        <div class="clearboth"></div>
    </div>
</div>
{/if}