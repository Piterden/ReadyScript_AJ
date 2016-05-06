{$shop_config=ConfigLoader::byModule('shop')}
{$check_quantity=$shop_config.check_quantity}
{$list = $this_controller->api->addProductsMultiOffersInfo($list)}
{$list = $this_controller->api->addProductsDirs($list)}

{if $no_query_error}
<div class="noQuery">
    Не задан поисковый запрос
</div>      
{else}
<div id="products" {if $shop_config}class="shopVersion"{/if}>
    <h1 class="catTitle">{$category.name}</h1>
    {if $category.description}<div class="categoryDescription">{$category.description}</div>{/if}
    {if count($sub_dirs)}{assign var=one_dir value=reset($sub_dirs)}{/if}
    {if empty($query) || (count($sub_dirs) && $dir_id != $one_dir.id)}
    <nav class="subCategory">
        {foreach $sub_dirs as $item}
        <a href="{urlmake category=$item._alias p=null f=null bfilter=null}">{$item.name}</a>
        {/foreach}
    </nav>
    {/if}

    {if count($list)}
    <div class="viewOptions">
        <a href="{urlmake viewAs=table}" class="viewAs table{if $view_as == 'table'} act{/if}" rel="nofollow"></a>
        <a href="{urlmake viewAs=blocks}" class="viewAs blocks{if $view_as == 'blocks'} act{/if}" rel="nofollow"></a>                
        Сортировать по:&nbsp;&nbsp;                  
        <div class="lineListBlock sortBlock">
            <a class="lineTrigger rs-parent-switcher">{if $cur_sort=='dateof'}по дате
                                                      {elseif $cur_sort=='rating'}популярности
                                                      {elseif $cur_sort=='title'}названию
                                                      {elseif $cur_sort=='num'}наличию
                                                      {elseif $cur_sort=='rank'}релевантности
                                                      {else}цене{/if}</a>
            <ul class="lineList">
                <li><a href="{urlmake sort="cost" nsort=$sort.cost escape=true}" class="item{if $cur_sort=='cost'} {$cur_n}{/if}" rel="nofollow"><i>цене</i></a></li>
                <li><a href="{urlmake sort="rating" nsort=$sort.rating escape=true}" class="item{if $cur_sort=='rating'} {$cur_n}{/if}" rel="nofollow"><i>популярности</i></a></li>
                <li><a href="{urlmake sort="dateof" nsort=$sort.dateof escape=true}" class="item{if $cur_sort=='dateof'} {$cur_n}{/if}" rel="nofollow"><i>дате</i></a></li>
                <li><a href="{urlmake sort="num" nsort=$sort.num escape=true}" class="item{if $cur_sort=='num'} {$cur_n}{/if}" rel="nofollow"><i>наличию</i></a></li>
                <li><a href="{urlmake sort="title" nsort=$sort.title}" class="item{if $cur_sort=='title'} {$cur_n}{/if}" rel="nofollow"><i>названию</i></a></li>
                {if $can_rank_sort}
                <li><a href="{urlmake sort="rank" nsort=$sort.rank escape=true}" class="item{if $cur_sort=='rank'} {$cur_n}{/if}" rel="nofollow"><i>релевантности</i></a></li>
                {/if}                                                    
            </ul>
        </div>
    </div>

    <div class="pagesLine before">
        <div class="pageSizeBlock">
            Показывать по:&nbsp;&nbsp; 
            <div class="lineListBlock collapse720">
                <a class="lineTrigger rs-parent-switcher">{$page_size}</a>
                <ul class="lineList">
                    {foreach $items_on_page as $item}
                    <li><a href="{urlmake pageSize=$item}" class="item{if $page_size==$item} act{/if}"><i>{$item}</i></a></li>
                    {/foreach}
                </ul>
            </div>
        </div>
        {include file="%THEME%/paginator.tpl"}
        <div class="clearboth"></div>
    </div>

    <section class="catalog">
        <div class="productWrap">
            {if $view_as == 'blocks'}
            <ul class="products">
                {foreach $list as $product}
                    {include file="%catalog%/one_product.tpl" shop_config=$shop_config product=$product}
                {/foreach}
            </ul>
            {else}
            <table class="productTable">
                {foreach $list as $product}
                <tr {$product->getDebugAttributes()} data-id="{$product.id}">
                    {$main_image=$product->getMainImage()}
                    <td class="image"><a href="{$product->getUrl()}"><img src="{$main_image->getUrl(100,100)}"  alt="{$main_image.title|default:"{$product.title}"}"/></a></td>
                    <td class="info">
                        <a href="{$product->getUrl()}" class="title">{$product.title}</a>
                        <p class="descr">{$product.short_description}</p>
                    </td>
                    <td class="price">{$product->getCost()} {$product->getCurrency()}</td>
                    <td class="actions">
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
                   
                    </td>
                </tr>                       
                {/foreach}
            </table>
            {/if}
            
        <div class="clear"></div>
        {include file="%THEME%/paginator.tpl"}
    </div>
    </section>
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