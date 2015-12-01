{addjs file="jcarousel/jquery.jcarousel.min.js"}
{addjs file="jquery.changeoffer.js?v=2"}
{addjs file="product.js"}
{assign var=shop_config value=ConfigLoader::byModule('shop')}
{assign var=check_quantity value=$shop_config.check_quantity}
{assign var=catalog_config value=$this_controller->getModuleConfig()}

<div class="container">
    <div class="row">
        <div class="col-md-6 back"></div>
        <div class="col-md-12 breadcrumbs text-center">
            {moduleinsert name="\Main\Controller\Block\Breadcrumbs"}
        </div>
    </div>
    <div class="row title">
        <div class="col-md-14 col-md-offset-5">
            <h2 class="text-center">{$product.title}</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            {* Подгружаем остатки по складам, т.к. при смене комплектации 
        будет изменяться и отображение остатков *}
        {$product->fillOffersStockStars()}
        
        {* Подгружаем остатки по складам*}
        {if $product->isMultiOffersUse()}
            {* Многомерные комплектации *}
            <div class="multiOffers">
                <span class="pname">{$product.offer_caption|default:'Комплектация'}</span>
                {* Подгрузим у многомерных комплектаций фото к их вариантам *}
                {$product->fillMultiOffersPhotos()}
                {* Переберём доступные многомерные комплектации *}
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
                                        <a class="multiOfferValueBlock {if $value@first}sel{/if}" data-value="{$value.val_str}" title="{$value.val_str}"><img src="{$level.values_photos[$value.val_str]->getUrl(40,40,'axy')}"/></a>
                                    {else}
                                        <a class="multiOfferValueBlock likeString {if $value@first}sel{/if}" data-value="{$value.val_str}" title="{$value.val_str}">{$value.val_str}</a>
                                    {/if}
                                {/foreach}
                            </div>
                        {/if}
                    {/if}
                {/foreach}
            </div>
            {if $product->isOffersUse()}
                {foreach from=$product.offers.items key=key item=offer name=offers}
                    <input value="{$key}" type="hidden" name="hidden_offers" class="hidden_offers" {if $smarty.foreach.offers.first}checked{/if} id="offer_{$key}" data-info='{$offer->getPropertiesJson()}' {if $check_quantity}data-num="{$offer.num}"{/if} {if $catalog_config.use_offer_unit}data-unit="{$offer->getUnit()->stitle}"{/if} data-change-cost='{ ".offerBarcode": "{$offer.barcode|default:$product.barcode}", ".myCost": "{$product->getCost(null, $key)}", ".lastPrice": "{$product->getCost('Зачеркнутая цена', $key)}"}' data-images='{$offer->getPhotosJson()}' data-sticks='{$offer->getStickJson()}'/>
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
                                <option value="{$key}" {if $smarty.foreach.offers.first}checked{/if} {if $check_quantity}data-num="{$offer.num}"{/if} {if $catalog_config.use_offer_unit}data-unit="{$offer->getUnit()->stitle}"{/if} data-change-cost='{ ".offerBarcode": "{$offer.barcode|default:$product.barcode}", ".myCost": "{$product->getCost(null, $key)}", ".lastPrice": "{$product->getCost('Зачеркнутая цена', $key)}"}' data-images='{$offer->getPhotosJson()}' data-sticks='{$offer->getStickJson()}'>{$offer.title}</option>
                                {/foreach}
                            </select>
                        {else}
                            {foreach from=$product.offers.items key=key item=offer name=offers}
                                <input value="{$key}" type="radio" name="offer" {if $smarty.foreach.offers.first}checked{/if} id="offer_{$key}" {if $check_quantity}data-num="{$offer.num}"{/if} {if $catalog_config.use_offer_unit}data-unit="{$offer->getUnit()->stitle}"{/if} data-change-cost='{ ".offerBarcode": "{$offer.barcode|default:$product.barcode}", ".myCost": "{$product->getCost(null, $key)}", ".lastPrice": "{$product->getCost('Зачеркнутая цена', $key)}"}' data-images='{$offer->getPhotosJson()}' data-sticks='{$offer->getStickJson()}'>
                                <label for="offer_{$key}">{$offer.title}</label><br>
                            {/foreach}
                        {/if}
                    </div>
                </div>
            </div><br>
        {/if}
        </div>
        <div class="col-md-12"></div>
        <div class="col-md-6"></div>
    </div>
</div>