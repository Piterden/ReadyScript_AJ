{addjs file="jquery.changeoffer.js"}
{$shop_config=ConfigLoader::byModule('shop')}
{$check_quantity=$shop_config.check_quantity}

<div id="products" class="wishlist productList{if $shop_config} shopVersion{/if} container">
    {if count($products)}
        <div class="row productsBlock">
            <!-- Sortline -->
            <div class="sortLine col-md-24">
                <span class="total h4">{verb item=$total values="модель,модели,моделей"}</span>
                <span class="sort">
                    Сортировать по
                    <span class="ddList">
                        <span class="value h4">{if $cur_sort=='dateof'}дате{elseif $cur_sort=='rating'}популярности{else}цене{/if} <i class="fa fa-sort-{$cur_n}"></i></span>
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
                        <span class="value h4">{$page_size}</span>
                        <ul>
                            {foreach $items_on_page as $item}
                                <li class="{if $page_size==$item} act{/if}"><a href="{urlmake pageSize=$item}">{$item}</a></li>
                                {/foreach}
                        </ul>
                    </span>
                </span>
            </div>
            <!-- /Sortline -->
            {foreach $products as $product}
                {$main_image=$product->getMainImage()}
                <div class="col-sm-6 productItem" {$product->getDebugAttributes()} data-id="{$product.id}">
                    <div class="productItemWrap">
                        <div class="specCategoriesBlockWrap">
                            {foreach $product->getSpecDirs() as $specCategory}
                                {if $specCategory.image != '' && $product->inDir($specCategory.alias)}
                                    <div class="specCategory">
                                        <img src="{$specCategory->__image->getLink()}" alt="{$specCategory.name}" title="{$specCategory.name}">
                                    </div>
                                {/if}
                            {/foreach}
                        </div>
                        <div class="wishWrap">
                            <div class="wishBox">
                                {moduleinsert
									name="\Wishlist\Controller\Block\WishActions"
									product_id=$product.id
                                }
                                <img src="{$THEME_IMG}/wishlist.png" alt="Add to Wishlist">
                            </div>
                            <div class="wishDesc">
                                {if in_array($product.id, $added_ids)}
                                    Удалить из списка<br>желаемых покупок

                                {else}
                                    Добавить в список<br>желаемых покупок
                                {/if}
                            </div>
                        </div>
                        <div class="toCartWrap">
                            {if $product->isOffersUse() || $product->isMultiOffersUse()}
                                <a data-href="{$router->getUrl('shop-front-multioffers', ["product_id" => $product.id])}" class="button showMultiOffers inDialog noShowCart">
                                    <img src="{$THEME_IMG}/cart.png" alt="Add to Cart" width="20px" height="20px">
                                    <div class="toCartText">
                                        В корзину
                                    </div>
                                </a>
                            {else}
                                <a data-href="{$router->getUrl('shop-front-cartpage', ["add" => $product.id])}" class="button addToCart noShowCart" data-add-text="Добавлено">
                                    <img src="{$THEME_IMG}/cart.png" alt="Add to Cart" width="20px" height="20px">
                                    <div class="toCartText">
                                        В корзину
                                    </div>
                                </a>
                            {/if}
                        </div>
                        <a href="{$product->getUrl()}" class="mainLink">
                            <div class="pic text-center">
                                <img src="{$main_image->getUrl(185,300,'axy')}" alt="{$main_image.title|default:"{$product.title}"}"/>
                            </div>
                            <div class="infoWrap">
                                <div class="info">
                                    <div class="price">{$product->getCost()} {$product->getCurrency()}</div>
                                    <div class="old-price">
                                        {if $product->getCost(2) > 0}
                                            {$product->getCost(2)} {$product->getCurrency()}
                                        {/if}
                                    </div>
                                    <h3 class="title">{$product.title}</h3>
                                    <div class="desc">{$product.short_description}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            {/foreach}
        </div>
        {include file="%THEME%/paginator.tpl"}
    {else}
        <div class="container noProducts">
            <div class="row">
                <div class="col-md-24">
                    {if !empty($query)}
                        Извините, ничего не найдено
                    {else}
                        В данной категории нет ни одного товара
                    {/if}
                </div>
            </div>
        </div>
    {/if}
</div>
