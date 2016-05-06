{if !empty($recommended)}
    <section class="recommended">
        <div class="titleWrap col-sm-24 text-center">
            <h3>{$recommended_title|default:"Рекомендованные товары"}</h3>
        </div>
        <div class="clearfix"></div>
        <div class="productWrap">
            {foreach from=$recommended item=product}
                {include file="%THEME%/moduleview/catalog/product_card.tpl" cardWidth="6"}
                 {* {$main_image=$product->getMainImage()}
                <div class="col-sm-6 productItem" {$product->getDebugAttributes()}>
                    <div class="productItemWrap">
                        <div class="wishWrap">
                            {moduleinsert
                            name="\Wishlist\Controller\Block\WishActions"
                            product_id=$product.id
                            }
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
                                    <div class="brand">AutoJack</div>
                                    <h3 class="title">{$product.title}</h3>
                                    <!-- <div class="desc">{$product.short_description}</div> -->
                                    <div class="stars">
                                        <i class="fa fa-star-o act"></i>
                                        <i class="fa fa-star-o act"></i>
                                        <i class="fa fa-star-o act"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        (4)
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div> *}
            {/foreach}
        </div>
    </section>
{/if}