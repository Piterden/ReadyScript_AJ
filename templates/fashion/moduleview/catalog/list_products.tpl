{addjs file="jquery.changeoffer.js"}
{$shop_config=ConfigLoader::byModule('shop')}
{$check_quantity=$shop_config.check_quantity}

{if $no_query_error}
<div class="noQuery">
    Не задан поисковый запрос
</div>      
{else}
<div id="products" {if $shop_config}class="shopVersion"{/if}>
    {if !empty($query)}
        <h1>Результаты поиска</h1>
    {else}
        <h1>{$category.name}</h1>
    {/if}
    {if $category.description}<article class="categoryDescription">{$category.description}</article>{/if}
    {if count($sub_dirs)}{assign var=one_dir value=reset($sub_dirs)}{/if}
    {if empty($query) || (count($sub_dirs) && $dir_id != $one_dir.id)}
    <nav class="subCategory">
        {foreach $sub_dirs as $item}
        <a href="{urlmake category=$item._alias p=null f=null bfilter=null}">{$item.name}</a>
        {/foreach}
    </nav>
    {/if}

    {if count($list)}
    <div class="sortLine">
        <div class="viewAs">
            <a href="{urlmake viewAs=table}" class="viewAs table{if $view_as == 'table'} act{/if}" rel="nofollow"></a>
            <a href="{urlmake viewAs=blocks}" class="viewAs blocks{if $view_as == 'blocks'} act{/if}" rel="nofollow"></a>                
        </div>
        <span class="sort">
            Сортировать по 
            <span class="ddList">
                <span class="value">{if $cur_sort=='dateof'}по дате{elseif $cur_sort=='rating'}популярности{else}цене{/if} {$cur_n_sort}</span>
                <ul>
                    <li><a href="{urlmake sort="cost" nsort=$sort.cost}" class="item{if $cur_sort=='cost'} {$cur_n}{/if}" rel="nofollow">цене</a></li>                
                    <li><a href="{urlmake sort="rating" nsort=$sort.rating}" class="item{if $cur_sort=='rating'} {$cur_n}{/if}" rel="nofollow">популярности</a></li>                    
                    <li><a href="{urlmake sort="dateof" nsort=$sort.dateof}" class="item{if $cur_sort=='dateof'} {$cur_n}{/if}" rel="nofollow">дате</a></li>
                    <li><a href="{urlmake sort="num" nsort=$sort.num}" class="item{if $cur_sort=='num'} {$cur_n}{/if}" rel="nofollow">наличию</a></li>
                    {if $can_rank_sort}
                    <li><a href="{urlmake sort="rank" nsort=$sort.rank}" class="item{if $cur_sort=='rank'} {$cur_n}{/if}" rel="nofollow"><i>релевантности</i></a></li>
                    {/if}                    
                </ul>
            </span>
        </span>
        
        <span class="pageSize">
            Показывать по 
            <span class="ddList">
                <span class="value">{$page_size}</span>
                <ul>
                    {foreach $items_on_page as $item}
                    <li class="{if $page_size==$item} act{/if}"><a href="{urlmake pageSize=$item}">{$item}</a></li>
                    {/foreach}
                </ul>
            </span>
        </span>                    
    </div>    
    
    {if $view_as == 'blocks'}
        <ul class="products">
            {foreach $list as $product}
                <li {$product->getDebugAttributes()} data-id="{$product.id}">
                    {$main_image=$product->getMainImage()}
                    <a href="{$product->getUrl()}" class="image">{if $product->inDir('new')}<i class="new"></i>{/if}<img src="{$main_image->getUrl(188,258)}" alt="{$main_image.title|default:"{$product.title}"}"/></a>
                    <a href="{$product->getUrl()}" class="title">{$product.title}</a>
                    <p class="price">{$product->getCost()} {$product->getCurrency()} 
                        {$last_price=$product->getCost('Зачеркнутая цена')}
                        {if $last_price>0}<span class="last">{$last_price} {$product->getCurrency()}</span>{/if}</p>
                    <div class="hoverBlock">
                        <div class="back"></div>
                        <div class="main">
                            {if $shop_config}
                                {if $product->shouldReserve()}
                                    <a data-href="{$router->getUrl('shop-front-reservation', ["product_id" => $product.id])}" class="button reserve inDialog">Заказать</a>
                                {else}        
                                    {if $check_quantity && $product.num<1}
                                        <span class="noAvaible">Нет в наличии</span>
                                    {else}
                                        {if $product->isOffersUse() || $product->isMultiOffersUse()}
                                            <span data-href="{$router->getUrl('shop-front-multioffers', ["product_id" => $product.id])}" class="button showMultiOffers inDialog noShowCart">В корзину</span>
                                        {else}
                                            <a data-href="{$router->getUrl('shop-front-cartpage', ["add" => $product.id])}" class="button addToCart noShowCart" data-add-text="Добавлено">В корзину</a>
                                        {/if}                                                            
                                    {/if}
                                {/if}
                            {/if}                            
                            <a class="compare{if $product->inCompareList()} inCompare{/if}"><span>К сравнению</span><span class="already">Добавлено</span></a>
                        </div>
                    </div>                        
                </li>                   
            {/foreach}
        </ul>
    {else}
        <table class="productTable">
            {foreach $list as $product}
            <tr {$product->getDebugAttributes()} data-id="{$product.id}">
                {$main_image=$product->getMainImage()}
                <td class="image"><a href="{$product->getUrl()}"><img src="{$main_image->getUrl(100,100)}" alt="{$main_image.title|default:"{$product.title}"}"/></a></td>
                <td class="info">
                    <a href="{$product->getUrl()}" class="title">{$product.title}</a>
                    {if $product.barcode}<p class="barcode">Артикул: {$product.barcode}</p>{/if}
                    <p class="descr">{$product.short_description}</p>
                </td>
                <td class="price">{$product->getCost()} {$product->getCurrency()}</td>
                <td class="actions">
                    {if $shop_config}
                        {if $product->shouldReserve()}
                            <a href="{$router->getUrl('shop-front-reservation', ["product_id" => $product.id])}" class="button reserve inDialog">Заказать</a>
                        {else}        
                            {if $check_quantity && $product.num<1}
                                <div class="noAvaible">Нет в наличии</div>
                            {else}
                                {if $product->isOffersUse() || $product->isMultiOffersUse()}
                                    <span data-href="{$router->getUrl('shop-front-multioffers', ["product_id" => $product.id])}" class="button showMultiOffers inDialog noShowCart">В корзину</span>
                                {else}
                                    <a data-href="{$router->getUrl('shop-front-cartpage', ["add" => $product.id])}" class="button addToCart noShowCart" data-add-text="Добавлено">В корзину</a>
                                {/if}                                                                                        
                            {/if}
                        {/if}
                    {/if}
                    <a class="compare{if $product->inCompareList()} inCompare{/if}"><span>Сравнить</span><span class="already">Добавлено</span></a>
                </td>
            </tr>                       
            {/foreach}
        </table>
    {/if}
    {include file="%THEME%/paginator.tpl"}
    
    {else}    
        <div class="noProducts">
            {if !empty($query)}
            Извините, ничего не найдено
            {else}
            В данной категории нет ни одного товара
            {/if}
        </div>
    {/if}

</div>
{/if}