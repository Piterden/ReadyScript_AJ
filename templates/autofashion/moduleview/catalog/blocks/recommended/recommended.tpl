{if !empty($recommended)}
<section class="recommended">
    <div class="titleWrap col-sm-24 text-center">
        <h3>{$recommended_title|default:"Рекомендованные товары"}</h3>            
    </div>
    <div class="clearfix"></div>
    <div class="productWrap">
        {foreach from=$recommended item=product}
        {$main_image=$product->getMainImage()}
        <div class="col-sm-6 productItem" {$product->getDebugAttributes()}>
            <div class="productItemWrap">
                <div class="wishWrap">
                    <div class="wishBox">
                        {if in_array($product.id, $added_ids)}
                            {moduleinsert 
                                name="\Wishlist\Controller\Block\WishActions" 
                                product_id=$product.id 
                                indexTemplate="blocks/actions/formdelwish.tpl"
                            }
                        {else}
                            {moduleinsert 
                                name="\Wishlist\Controller\Block\WishActions" 
                                product_id=$product.id 
                                indexTemplate="blocks/actions/formaddwish.tpl"
                            }
                        {/if}
                        <img src="{$THEME_IMG}/wishlist.png" alt="Add to Wishlist">
                    </div>
                    <div class="wishDesc">
                        {if in_array($product.id, $added_ids)}
                            Добавить в список<br>желаемых покупок
                        {else}
                            Удалить из списка<br>желаемых покупок
                        {/if}
                    </div>
                </div>
                <div class="toCartWrap">
                    <img src="{$THEME_IMG}/cart.png" alt="Wishlist">
                    <div class="toCart">
                        В корзину
                    </div>
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
</section>
{/if}