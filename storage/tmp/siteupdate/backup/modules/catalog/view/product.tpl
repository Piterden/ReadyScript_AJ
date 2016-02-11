{addjs file="jcarousel/jquery.jcarousel.min.js"}
{addjs file="jquery.changeoffer.js?v=2"}
{addjs file="product.js"}
{assign var=shop_config value=ConfigLoader::byModule('shop')}
{assign var=check_quantity value=$shop_config.check_quantity}
{assign var=catalog_config value=$this_controller->getModuleConfig()} 
        
<section class="product productItem hproduct{if !$product->isAvailable()} notAvaliable{/if}{if $product->canBeReserved()} canBeReserved{/if}{if $product.reservation == 'forced'} forcedReserve{/if}" data-id="{$product.id}">
    <h1 class="fn">{$product.title}</h1>
    <div class="image">
        <span class="labels">
            {foreach from=$product->getMySpecDir() item=spec}
            {if $spec.image}
                <img src="{$spec->__image->getUrl(62,62, 'xy')}">
            {/if}
            {/foreach}
        </span>
        
        {if !$product->hasImage()}      
            {$main_image=$product->getMainImage()}
            <span class="mainPicture"><img src="{$main_image->getUrl(310,310,'xy')}" class="photo" alt="{$main_image.title|default:"{$product.title}"}"/></span>
        {else}
            {* Главные фото *}
            {assign var=images value=$product->getImages()}
            {if $product->isOffersUse()}
               {* Назначенные фото у первой комлектации *}
               {$offer_images=$product.offers.items[0].photos_arr}  
            {/if}
            {foreach from=$images key=key item=image name=biglist}
                <a href="{$image->getUrl(800,600,'xy')}" data-n="{$key}" data-id="{$image.id}" class="photo mainPicture viewbox {if ($offer_images && ($image.id!=$offer_images.0)) || (!$offer_images && !$image@first)} hidden{/if}" {if ($offer_images && in_array($image.id, $offer_images)) || (!$offer_images)}rel="bigphotos"{/if}><img src="{$image->getUrl(310,310,'xy')}" alt="{$image.title|default:"{$product.title} фото {$key+1}"}"></a>
            {/foreach}
            
            {* Нижняя линейка фото *}
            {if count($images)>1}
            <div class="productGalleryWrap">
                <div class="gallery">
                    <ul>
                        {foreach from=$product->getImages() key=key item=image}
                        <li data-id="{$image.id}" class="{if $offer_images && !in_array($image.id, $offer_images)}hidden{/if}"><a href="{$image->getUrl(800,600,'xy')}" data-n="{$key}" target="_blank" class="preview"><img src="{$image->getUrl(64,64)}"></a></li>
                        {/foreach}
                    </ul>
                </div>
                <a class="control prev"></a>
                <a class="control next"></a>
             </div>        
             {/if}
        {/if}
    </div>
    
    <div class="inform">
        <div class="prLine">
            <div class="ratingBlock bg">
                <span class="rating" title="Средняя оценка: {$product->getRatingBall()}"><span class="value" style="width:{$product->getRatingPercent()}%"></span></span><br>
                <span class="commentsCount">оценок {$product->getCommentsNum()}</span>
            </div>
            <a href="#comments" class="gotoComment">{t}написать отзыв{/t}</a>
            
            <div class="share">
                <div class="handler"></div>
                <div class="block">
                    <i class="corner"></i>
                    <p class="text">Поделиться:</p>
                    <script type="text/javascript" src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
                    <script type="text/javascript" src="//yastatic.net/share2/share.js" charset="utf-8"></script>
                    <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,twitter"></div>
                </div>
            </div>
        </div>
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
                    <input value="{$key}" type="hidden" name="hidden_offers" class="hidden_offers" {if $smarty.foreach.offers.first}checked{/if} id="offer_{$key}" data-info='{$offer->getPropertiesJson()}' {if $check_quantity}data-num="{$offer.num}"{/if} {if $catalog_config.use_offer_unit}data-unit="{$offer->getUnit()->stitle}"{/if} data-change-cost='{ ".offerBarcode": "{$offer.barcode|default:$product.barcode}", ".myCost": "{$product->getCost(null, $key)}", ".lastPrice": "{$product->getOldCost($key)}"}' data-images='{$offer->getPhotosJson()}' data-sticks='{$offer->getStickJson()}'/>
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
                                <option value="{$key}" {if $smarty.foreach.offers.first}checked{/if} {if $check_quantity}data-num="{$offer.num}"{/if} {if $catalog_config.use_offer_unit}data-unit="{$offer->getUnit()->stitle}"{/if} data-change-cost='{ ".offerBarcode": "{$offer.barcode|default:$product.barcode}", ".myCost": "{$product->getCost(null, $key)}", ".lastPrice": "{$product->getOldCost($key)}"}' data-images='{$offer->getPhotosJson()}' data-sticks='{$offer->getStickJson()}'>{$offer.title}</option>
                                {/foreach}
                            </select>
                        {else}
                            {foreach from=$product.offers.items key=key item=offer name=offers}
                                <div class="packageItem">
                                    <input value="{$key}" type="radio" name="offer" {if $smarty.foreach.offers.first}checked{/if} id="offer_{$key}" {if $check_quantity}data-num="{$offer.num}"{/if} {if $catalog_config.use_offer_unit}data-unit="{$offer->getUnit()->stitle}"{/if} data-change-cost='{ ".offerBarcode": "{$offer.barcode|default:$product.barcode}", ".myCost": "{$product->getCost(null, $key)}", ".lastPrice": "{$product->getOldCost($key)}"}' data-images='{$offer->getPhotosJson()}' data-sticks='{$offer->getStickJson()}'>
                                    <label for="offer_{$key}">{$offer.title}</label>
                                </div>
                            {/foreach}
                        {/if}
                    </div>
                </div>
            </div><br>
        {/if}
        
        {* Блок с сопутствующими товарами *}
        {if $shop_config}
            {moduleinsert name="\Shop\Controller\Block\Concomitant"}
        {/if}

        {* Блок с ценой *}
        <div class="fcost">
            {assign var=last_price value=$product->getOldCost()}
            {if $last_price>0}<div class="lastPrice">{$last_price}</div>{/if}
            <span class="myCost price">{$product->getCost()}</span> {$product->getCurrency()}
        </div>
        
        {* Если включена опция единицы измерения в комплектациях *}
        {if $catalog_config.use_offer_unit && $product->isOffersUse()}
            <span class="unitBlock">/ <span class="unit">{$product.offers.items[0]->getUnit()->stitle}</span></span>
        {/if}
        
        {* Кнопки действия *}
        <div class="mobileBasketLine">            
            {if $shop_config}
                <a data-href="{$router->getUrl('shop-front-cartpage', ["add" => $product.id])}" class="toBasket addToCart">в корзину</a>      
                <a data-href="{$router->getUrl('shop-front-reservation', ["product_id" => $product.id])}" class="inDialog reserve hidden">заказать</a>                
                <span class="unobtainable hidden">Нет в наличии</span>                                       
            {/if}

            {if !$shop_config || (!$product->shouldReserve() && (!$check_quantity || $product.num>0))}
                {if $catalog_config.buyinoneclick }
                    <a data-href="{$router->getUrl('catalog-front-oneclick',["product_id"=>$product.id,"offer_id"=>0])}" title="Купить в 1 клик" class="oneclick buyOneClick inDialog"></a>
                {/if}
            {/if}            
            <br>
            <a class="compare{if $product->inCompareList()} inCompare{/if}"><span>сравнить</span></a>
        </div>                    
        <p class="descr">{$product.short_description|nl2br}</p>
        <div class="floatWrap basketLine">
            {if $shop_config}
                <a href="{$router->getUrl('shop-front-cartpage', ["add" => $product.id])}" class="toBasket addToCart">в корзину</a>      
                <span class="unobtainable hidden">Нет в наличии</span>                                       
                <a href="{$router->getUrl('shop-front-reservation', ["product_id" => $product.id])}" class="inDialog reserve hidden">заказать</a>
            {/if}
            
            {if !$shop_config || (!$product->shouldReserve() && (!$check_quantity || $product.num>0))}
                {if $catalog_config.buyinoneclick }
                    <a href="{$router->getUrl('catalog-front-oneclick',["product_id"=>$product.id,"offer_id"=>0])}" title="Купить в 1 клик" class="oneclick buyOneClick inDialog"></a>
                {/if}
            {/if}
            <a class="compare{if $product->inCompareList()} inCompare{/if}"><span>сравнить</span></a>
            
        </div>
        {if $product.barcode}
        <p class="barcode"><span class="cap">Артикул:</span> <span class="offerBarcode">{$product.barcode}</span></p>
        {/if}
        
        {if $product.brand_id}
        <p class="brand"><span class="cap">Производитель:</span> <a class="brandTitle" href="{$product->getBrand()->getUrl()}">{$product->getBrand()->title}</a></p>
        {/if}                
        
        {* Вывод наличия на складах *}
        {assign var=stick_info value=$product->getWarehouseStickInfo()}
        {if !empty($stick_info.warehouses)}
            <div class="warehouseDiv">
                <div class="title">Наличие:</div>
                {foreach from=$stick_info.warehouses item=warehouse}
                    <div class="warehouseRow" data-warehouse-id="{$warehouse.id}">
                        <div class="stickWrap">
                        {foreach from=$stick_info.stick_ranges item=stick_range}
                             {$sticks=$product.offers.items.0.sticks[$warehouse.id]}
                             <span class="stick {if $sticks>=$stick_range}filled{/if}"></span>          
                        {/foreach}
                        </div>
                        <a class="title" href="{$warehouse->getUrl()}"><span>{$warehouse.title}</span></a>
                    </div>
                {/foreach}
            </div>
        {/if}
        
    </div>            
    <br class="clearboth">
    <div class="properties">
        {foreach from=$product.offers.items key=key item=offer name=offers}
        {if $offer.propsdata_arr}
        <div class="offerProperty{if $key>0} hidden{/if}" data-offer="{$key}">
            <h2><span>Характеристики комплектации</span></h2>
            <table class="kv">
                {foreach from=$offer.propsdata_arr key=pkey item=pval}
                        <tr>
                            <td class="key"><span>{$pkey}</span></td>
                            <td class="value">{$pval}</td>
                        </tr>
                {/foreach}
            </table>
        </div>
        {/if}
        {/foreach}
        
        {foreach from=$product->fillProperty() item=data}
        {if !$data.group.hidden}
        <div class="propertyGroup">
            <h2><span>{$data.group.title|default:"Характеристики"}</span></h2>
            <table class="kv">
                {foreach from=$data.properties item=property}
                    {assign var=prop_value value=$property->textView()}
                    {if !$property.hidden && !empty($prop_value)}
                        <tr>
                            <td class="key"><span>{$property.title}</span></td>
                            <td class="value">{$prop_value} {$property.unit}</td>
                        </tr>
                    {/if}
                {/foreach}
            </table>
        </div>
        {/if}
        {/foreach}
    </div>
    
    {if !empty($product.description)}
        <h2><span>Описание</span></h2>
        <article class="description">
            {$product.description}
        </article>
    {/if}
    
    {* Вывод публичных файлов *}
    {if $files=$product->getFiles()}
    <div class="files">
        <h2><span>Файлы</span></h2>
        <ul class="filesList">
            {foreach $files as $file}
            <li>
                <a href="{$file->getUrl()}">{$file.name} ({$file.size|format_filesize})</a>
                {if $file.description}<div class="fileDescription">{$file.description}</div>{/if}
            </li>
            {/foreach}
        </ul>
    </div>
    {/if}    
    
</section>