{assign var=shop_config value=ConfigLoader::byModule('shop')}
{assign var=check_quantity value=$shop_config.check_quantity}

<div class="authorization formStyle multiComplectations">
    <h2 data-dialog-options='{ "width": "765" }'>Выбор комплектации</h2>
    
    <div class="{if !$product->isAvailable()} notAvaliable{/if}{if $product->canBeReserved()} canBeReserved{/if}{if $product.reservation == 'forced'} forcedReserve{/if}" data-id="{$product.id}">
        <h4 class="fn">{$product.title}</h4>
        <div class="leftColumn">
            <div class="image">
                {$main_image=$product->getMainImage()}
                <img src="{$main_image->getUrl(344, 344)}" class="photo" alt="{$main_image.title|default:"{$product.title}"}"/>
            </div>
            {if $product.barcode}
                <p class="barcode"><span class="cap">Артикул:</span> <span class="offerBarcode">{$product.barcode}</span></p>
            {/if}
            {if $product.short_description}
                <p class="descr">{$product.short_description|nl2br}</p>
            {/if}
            <div class="fcost">
                {assign var=last_price value=$product->getOldCost()}
                {if $last_price>0}<div class="lastPriceWrap"><span class="lastPrice">{$last_price}</span> {$product->getCurrency()}</div><br>{/if}
                <span class="price"><strong class="myCost">{$product->getCost()}</strong> {$product->getCurrency()}</span>
            </div>
        </div>
        <div class="information">
            {if $product->isMultiOffersUse()}
                {* Многомерные комплектации *}
                <span class="pname">{$product.offer_caption|default:'Комплектация'}</span>
                {* Подгрузим у многомерных комплектаций фото к их вариантам *}
                {$product->fillMultiOffersPhotos()}
                {* Переберём доступные многомерные комплектации *}
                <div class="multiOffers">
                    {foreach $product.multioffers.levels as $level}
                        {if !empty($level.values)}
                            <div class="title">{if $level.title}{$level.title}{else}{$level.prop_title}{/if}</div>
                            {if !$level.is_photo && !isset($level.values_photos)} {* Если отображать не как фото (выпадающим списком)*}
                                <select name="multioffers[{$level.prop_id}]" data-prop-title="{if $level.title}{$level.title}{else}{$level.prop_title}{/if}">
                                    {foreach $level.values as $value}
                                        <option value="{$value.val_str}">{$value.val_str}</option>   
                                    {/foreach}
                                </select>
                            {else} {* Как фото *}
                                <div class="multiOfferValues">
                                    <input type="hidden" name="multioffers[{$level.prop_id}]" data-prop-title="{if $level.title}{$level.title}{else}{$level.prop_title}{/if}"/>
                                    {foreach $level.values as $value}
                                        {if isset($level.values_photos[$value.val_str])}
                                            <a class="multiOfferValueBlock {if $value@first}sel{/if}" data-value="{$value.val_str}" data-image="{$level.values_photos[$value.val_str]->getUrl(310,310,'axy')}" data-is-dialog="1" title="{$value.val_str}"><img src="{$level.values_photos[$value.val_str]->getUrl(30,30,'axy')}" alt="{$value.val_str}"/></a>
                                        {else}
                                            <a class="multiOfferValueBlock likeString {if $value@first}sel{/if}" data-value="{$value.val_str}" data-is-dialog="1" title="{$value.val_str}">{$value.val_str}</a>
                                        {/if}
                                    {/foreach}
                                </div>
                            {/if}
                        {/if}
                    {/foreach}
                </div>
                {if $product->isOffersUse()}
                    {foreach from=$product.offers.items key=key item=offer name=offers}
                        <input value="{$key}" type="hidden" name="hidden_offers" class="hidden_offers" {if $smarty.foreach.offers.first}checked{/if} id="offer_{$key}" data-info='{$offer->getPropertiesJson()}' {if $check_quantity}data-num="{$offer.num}"{/if} data-change-cost='{ ".offerBarcode": "{$offer.barcode|default:$product.barcode}", ".myCost": "{$product->getCost(null, $key)}", ".lastPrice": "{$product->getOldCost($key)}"}' data-images='{$offer->getPhotosJson()}' data-sticks='{$offer->getStickJson()}'/>
                    {/foreach}
                    <input type="hidden" name="offer" value="0"/>
                {/if}
            {elseif $product->isOffersUse()}
                {* Простые комплектации *}
                <div class="packages">
                    <div class="package">
                        <span class="pname">{$product.offer_caption|default:'Комплектация'}</span>
                        <div class="values">
                            {if count($product.offers.items)>5}
                                <select name="offer">
                                    {foreach from=$product.offers.items key=key item=offer name=offers}
                                    <option value="{$key}" {if $smarty.foreach.offers.first}checked{/if} {if $check_quantity}data-num="{$offer.num}"{/if} data-change-cost='{ ".offerBarcode": "{$offer.barcode|default:$product.barcode}", ".myCost": "{$product->getCost(null, $key)}", ".lastPrice": "{$product->getOldCost($key)}"}'  data-images='{$offer->getPhotosJson()}' data-sticks='{$offer->getStickJson()}'>{$offer.title}</option>
                                    {/foreach}
                                </select>
                            {else}
                                {foreach from=$product.offers.items key=key item=offer name=offers}
                                    <div class="packageItem">
                                        <input value="{$key}" type="radio" name="offer" {if $smarty.foreach.offers.first}checked{/if} id="offer_{$key}" {if $check_quantity}data-num="{$offer.num}"{/if} data-change-cost='{ ".offerBarcode": "{$offer.barcode|default:$product.barcode}", ".myCost": "{$product->getCost(null, $key)}", ".lastPrice": "{$product->getOldCost($key)}"}' data-images='{$offer->getPhotosJson()}' data-sticks='{$offer->getStickJson()}'>
                                        <label for="offer_{$key}">{$offer.title}</label><br>
                                    </div>
                                {/foreach}
                            {/if}
                        </div>
                    </div>
                </div>
            {/if}
            
            {if $shop_config}
                {* Блок с сопутствующими товарами *}
                {moduleinsert name="\Shop\Controller\Block\Concomitant"}
            {/if}
            
            <div class="buttons">
                <a data-href="{$router->getUrl('shop-front-reservation', ["product_id" => $product.id])}" class="redButton inDialog reservation">заказать</a>
                <span class="noProduct">Нет в наличии</span>                
                <a data-href="{$router->getUrl('shop-front-cartpage', ["add" => $product.id])}" class="button addToCart noShowCart">В корзину</a>      
            </div>
        </div>            
    </div>
</div>

{literal}
    <script type="text/javascript">
        $(function() {
            $('[name="offer"]').changeOffer();
        });
        $('.multiComplectations .addToCart').on('click',function(){
            $.colorbox.close();
        });
    </script>
{/literal}