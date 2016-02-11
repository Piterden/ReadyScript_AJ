{addjs file="jcarousel/jquery.jcarousel.min.js"}
{addjs file="product.js"}
{assign var=shop_config value=ConfigLoader::byModule('shop')}
{assign var=check_quantity value=$shop_config.check_quantity}
{assign var=catalog_config value=$this_controller->getModuleConfig()} 

<div class="product{if !$product->isAvailable()} notAvaliable{/if}{if $product->canBeReserved()} canBeReserved{/if}{if $product.reservation == 'forced'} forcedReserve{/if}" data-id="{$product.id}">
    <h1>{$product.title}</h1>
    <div class="left">
        <div class="images">
            {if !$product->hasImage()}
                {$main_image=$product->getMainImage()}
                <span class="image"><img src="{$main_image->getUrl(310,310,'xy')}" alt="{$main_image.title|default:"{$product.title}"}"/></span>
            {else}
                {* Главные фото *}
                {$images=$product->getImages()}
                {if $product->isOffersUse()}
                   {* Назначенные фото у первой комлектации *}
                   {$offer_images=$product.offers.items[0].photos_arr}  
                {/if}
                {foreach $images as $key => $image}
                    <a href="{$image->getUrl(800,600,'xy')}" class="image main {if ($offer_images && ($image.id!=$offer_images.0)) || (!$offer_images && !$image@first)} hidden{/if}" data-n="{$key}" data-id="{$image.id}" target="_blank" {if ($offer_images && in_array($image.id, $offer_images)) || (!$offer_images)}rel="bigphotos"{/if} ><img src="{$image->getUrl(344,344,'xy')}" alt="{$image.title|default:"{$product.title} фото {$key+1}"}"/></a>
                {/foreach}
                
                {* Нижняя линейка фото *}
                {if count($images)>1}
                <div class="gallery">
                    <div class="scrollWrap">
                        <ul class="scrollBlock">
                            {foreach $images as $key => $image}
                            <li data-id="{$image.id}" class="{if $offer_images && !in_array($image.id, $offer_images)}hidden{elseif !$first++} first{/if}">
                                <a href="{$image->getUrl(800,600,'xy')}" class="preview" data-n="{$key}" target="_blank"><img src="{$image->getUrl(100, 100)}"  alt="{$image.title|default:"{$product.title} фото {$key+1}"}"/></a></li>
                            {/foreach}
                        </ul>
                    </div>
                    <a href="#" class="control prev"></a>
                    <a href="#" class="control next"></a>
                </div>
                {/if}
            {/if}
        </div>
    </div>                    
    <div class="right">
        <div class="share">
            <div class="handler"></div>
            <div class="block">
                <p class="text">Поделиться с друзьями:</p>
                <script type="text/javascript" src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
                <script type="text/javascript" src="//yastatic.net/share2/share.js" charset="utf-8"></script>
                <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,twitter"></div>
            </div>
        </div>
        
        {$last_price=$product->getOldCost()}
        <div class="priceLine">
            <div class="prices">
                {if $last_price>0}<p class="lastPrice">{$last_price} {$product->getCurrency()}</p>{/if}
                <p class="price"><span class="myCost">{$product->getCost()}</span> {$product->getCurrency()}
                    {* Если включена опция единицы измерения в комплектациях *}
                    {if $catalog_config.use_offer_unit && $product->isOffersUse()}
                        <span class="unitBlock">/ <span class="unit">{$product.offers.items[0]->getUnit()->stitle}</span></span>
                    {/if}
                </p>
                
                

                {* Подгружаем остатки по складам, т.к. при смене комплектации 
                будет изменяться и отображение остатков *} 
                {$product->fillOffersStockStars()}                
                
                {if $product->isMultiOffersUse()}
                    {* Многомерные комплектации *}
                    <div class="multiOffers">
                        <div class="pname">{$product.offer_caption|default:'Комплектация'}</div>
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
                                                <a class="multiOfferValueBlock {if $value@first}sel{/if}" data-value="{$value.val_str}" title="{$value.val_str}"><img src="{$level.values_photos[$value.val_str]->getUrl(40,40,'axy')}" alt="{$value.val_str}"/></a>
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
                            <input value="{$key}" type="hidden" name="hidden_offers" class="hidden_offers" {if $smarty.foreach.offers.first}checked{/if} id="offer_{$key}" data-info='{$offer->getPropertiesJson()}' {if $catalog_config.use_offer_unit}data-unit="{$offer->getUnit()->stitle}"{/if} {if $check_quantity}data-num="{$offer.num}"{/if} data-change-cost='{ ".offerBarcode": "{$offer.barcode|default:$product.barcode}", ".myCost": "{$product->getCost(null, $key)}", ".lastPrice": "{$product->getOldCost($key)}"}' data-images='{$offer->getPhotosJson()}' data-sticks='{$offer->getStickJson()}'/>
                        {/foreach}
                        
                        <input type="hidden" name="offer" value="0"/>
                    {/if}

                {elseif $product->isOffersUse()}
                    {* Простые комплектации *}
                    <div class="packages">
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
                {/if}                       
            </div>
            <div class="rating">
                <p>Оценка покупателей</p>
                <span class="stars" title="Средняя оценка: {$product->getRatingBall()}">
                    <i style="width:{$product->getRatingPercent()}%"></i>
                </span>
                <a href="#comments" class="comments">{$product->getCommentsNum()} отзывов</a>
            </div>
            <div class="clearboth"></div>
        </div>
        
        {if $shop_config}
            {* Блок с сопутствующими товарами *}
            {moduleinsert name="\Shop\Controller\Block\Concomitant"}
        {/if}
        
        <div class="buttons">
            {if $shop_config}
                <a data-href="{$router->getUrl('shop-front-reservation', ["product_id" => $product.id])}" class="redButton inDialog reservation">заказать</a>
                <span class="noProduct">Нет в наличии</span>
                <a data-href="{$router->getUrl('shop-front-cartpage', ["add" => $product.id])}" class="addToCart" data-add-text="Добавлено">В корзину</a>
            {/if}
            
            {if !$shop_config || (!$product->shouldReserve() && (!$check_quantity || $product.num>0))}
                {if $catalog_config.buyinoneclick }
                    <a data-href="{$router->getUrl('catalog-front-oneclick',["product_id"=>$product.id])}" title="Купить в 1 клик" class="buyOneClick blueButton inDialog">Купить в 1 клик</a>
                {/if}
            {/if}            
        </div>
        <a class="compare {if $product->inCompareList()} inCompare{/if}"><span>Сравнить</span><span class="already">Добавлено<br><i class="ext doCompare">Сравнить</i></span></a>
        <ul class="params">
            {if $product.barcode}
            <li>Артикул: <span class="offerBarcode">{$product.barcode}</span></li>
            {/if}
            {if $product.brand_id}
            <li>Производитель: <a href="{$product->getBrand()->getUrl()}">{$product->getBrand()->title}</a></li>
            {/if}            
        </ul>

        {* Вывод наличия на складах *}
        {assign var=stick_info value=$product->getWarehouseStickInfo()}
        {if !empty($stick_info.warehouses)}
            <div class="warehouseDiv">
                <div class="title">Наличие</div>
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
        
        <p class="shortDescription">
            {$product.short_description}
        </p>
    </div>
    
    <div class="bottomLeft">
        <h3>Описание</h3>
        <div class="properties">
            {foreach $product.offers.items as $key=>$offer}
            {if $offer.propsdata_arr}
            <div class="offerProperty{if $key>0} hidden{/if}" data-offer="{$key}">
                <h4>Характеристики комплектации</h4>
                <table class="kv">
                    {foreach $offer.propsdata_arr as $pkey=>$pval}
                    <tr>
                        <td class="key"><span>{$pkey}</span></td>
                        <td class="value">{$pval}</td>
                    </tr>
                    {/foreach}
                </table>
            </div>
            {/if}
            {/foreach}            
        
            {foreach $product->fillProperty() as $data}
                {if !$data.group.hidden}
                <h4>{$data.group.title|default:"Характеристики"}</h4>
                <table class="kv">
                    {foreach $data.properties as $property}
                    {if !$property.hidden}
                    <tr>
                        <td class="key">{$property.title}</td>
                        <td class="value">{$property->textView()} {$property.unit}</td>
                    </tr>
                    {/if}
                    {/foreach}
                </table>
                {/if}
            {/foreach}
        </div>
        <article class="description">
            {$product.description}
        </article>
    </div>                
    
    <div class="bottomRight">
        {if $files=$product->getFiles()}
            <h3>Файлы</h3>
            <ul class="filesList">
                {foreach $files as $file}
                <li>
                    <a href="{$file->getUrl()}">{$file.name} ({$file.size|format_filesize})</a>
                    {if $file.description}<div class="fileDescription">{$file.description}</div>{/if}
                </li>
                {/foreach}
            </ul>
        {/if}
    
        {moduleinsert name="\Comments\Controller\Block\Comments" type="\Catalog\Model\CommentType\Product"}            
        {moduleinsert name="\Catalog\Controller\Block\Recommended"}
    </div>
    <div class="clearboth"></div>
</div>