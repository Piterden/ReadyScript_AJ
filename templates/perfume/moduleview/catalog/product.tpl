{addjs file="jcarousel/jquery.jcarousel.min.js"}
{addjs file="product.js"}
{assign var=shop_config value=ConfigLoader::byModule('shop')}
{assign var=check_quantity value=$shop_config.check_quantity}
{assign var=catalog_config value=$this_controller->getModuleConfig()} 


<div class="product{if !$product->isAvailable()} notAvaliable{/if}{if $product->canBeReserved()} canBeReserved{/if}{if $product.reservation == 'forced'} forcedReserve{/if}" data-id="{$product.id}">
    <h1 class="productTitle">{$product.title}</h1>
    <div class="social">
        <span class="usersMark">Оценка покупателей</span>
        <span class="stars" title="Средняя оценка: {$product->getRatingBall()}"><i style="width:{$product->getRatingPercent()}%"></i></span>
        <a href="#comments" class="comments">{$product->getCommentsNum()} отзывов</a>
        <div class="share">
            <div class="handler"></div>
            <div class="block">
                <p class="text">Поделиться с друзьями:</p>
                <script type="text/javascript" src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
                <script type="text/javascript" src="//yastatic.net/share2/share.js" charset="utf-8"></script>
                <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,twitter"></div>
            </div>
        </div>
    </div>    

    <div class="card">
        <div class="images">
            {if !$product->hasImage()}
                {$main_image=$product->getMainImage()}
                <span class="main"><img src="{$main_image->getUrl(327,322)}" alt="{$main_image.title|default:"{$product.title}"}"/></span>
            {else}
                {* Главные фото *}
                {$images=$product->getImages()}
                {if $product->isOffersUse()}
                   {* Назначенные фото у первой комлектации *}
                   {$offer_images=$product.offers.items[0].photos_arr}  
                {/if}
                {foreach $images as $key => $image}
                    <a href="{$image->getUrl(800,600,'xy')}" class="image main {if ($offer_images && ($image.id!=$offer_images.0)) || (!$offer_images && !$image@first)} hidden{/if}" data-n="{$key}" data-id="{$image.id}" target="_blank" {if ($offer_images && in_array($image.id, $offer_images)) || (!$offer_images)}rel="bigphotos"{/if} ><img src="{$image->getUrl(327,322,'xy')}" alt="{$image.title|default:"{$product.title} фото {$key+1}"}"></a>
                {/foreach}
                
                {* Нижняя линейка фото *}
                {if count($images)>1}
                <div class="productGalleryWrap">
                    <div class="gallery">
                        <ul>
                            {foreach $images as $key => $image}
                            <li data-id="{$image.id}" class="{if $offer_images && !in_array($image.id, $offer_images)}hidden{elseif !$first++} first{/if}"><a href="{$image->getUrl(800,600,'xy')}" class="preview" data-n="{$key}" target="_blank"><img src="{$image->getUrl(100, 100)}" alt="{$image.title|default:"{$product.title} фото {$key+1}"}"/></a></li>
                            {/foreach}
                        </ul>
                    </div>
                    <a class="control prev"></a>
                    <a class="control next"></a>
                 </div>
                 {/if}
             {/if}
        </div>
        <div class="information">
            {if $product.short_description}
            <p class="descr">{$product.short_description}</p>
            {/if}
           
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
            
           
           {if $shop_config}
                {* Блок с сопутствующими товарами *}
                {moduleinsert name="\Shop\Controller\Block\Concomitant"}
           {/if}
            
           {assign var=last_price value=$product->getOldCost()}
           <div class="price">
                {if $last_price>0}<p class="lastPriceWrap"><span class="lastPrice">{$last_price}</span> {$product->getCurrency()}</p>{/if}
                <span class="myCost">{$product->getCost()}</span> {$product->getCurrency()}
                
                {* Если включена опция единицы измерения в комплектациях *}
                {if $catalog_config.use_offer_unit && $product->isOffersUse()}
                    <span class="unitBlock">/ <span class="unit">{$product.offers.items[0]->getUnit()->stitle}</span></span>
                {/if}
           </div>
           <p class="cartBlock">
                {if $shop_config}
                    <a data-href="{$router->getUrl('shop-front-reservation', ["product_id" => $product.id])}" class="inDialog reserve hidden">Заказать</a>
                    <span class="unobtainable hidden">Нет в наличии</span>
                    <a data-href="{$router->getUrl('shop-front-cartpage', ["add" => $product.id])}" class="addToCart" data-add-text="Добавлено">В корзину</a>
                {/if}
                
                {if !$shop_config || (!$product->shouldReserve() && (!$check_quantity || $product.num>0))}
                    {if $catalog_config.buyinoneclick }
                        <a data-href="{$router->getUrl('catalog-front-oneclick',["product_id"=>$product.id])}" title="Купить в 1 клик" class="buyOneClick inDialog">Купить в 1 клик</a>
                    {/if}
                {/if}            
           </p>
           <p class="cmpBlock">
               <a class="compare{if $product->inCompareList()} inCompare{/if}"><span>Сравнить</span><span class="already">Добавлено</span></a>
           </p>
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
    </div>
    <div class="clearboth"></div>    
    {$properties = $product->fillProperty()}
    {$tabs=[]}
    {if $properties || $product->isOffersUse()} {$tabs["property"] = 'Характеристики'} {/if}
    {if $product.description} {$tabs["description"] = 'Описание'} {/if}
    {if $files=$product->getFiles()}{$tabs["files"] = 'Файлы'} {/if}
    {$tabs["comments"] = 'Отзывы'}
    
    <div class="rsTabs mobile mt40">
        <ul class="tabList">
            {foreach $tabs as $key=>$tab}
            {if $tab@first}{$act_tab=$key}{/if}
            <li {if $tab@first}class="act"{/if} data-href=".tab-{$key}"><a>{$tab}</a></li>
            {/foreach}
        </ul>
        
        {if $tabs.property}
        <div class="tab tab-property {if $act_tab == 'property'}act{/if}">
            <p class="mobileCaption">{$tabs.property}</p>
            {foreach $product.offers.items as $key=>$offer}
            {if $offer.propsdata_arr}
            <div class="offerProperty propertyGroup{if $key>0} hidden{/if}" data-offer="{$key}">
                <p class="groupName">Характеристики комплектации</p>
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
            <div class="propertyGroup">
                <p class="groupName">{$data.group.title|default:"Характеристики"}</p>
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
            </div>
            {/if}
            {/foreach}            
        </div>
        {/if}
        
        {if $tabs.description}
        <div class="tab tab-description textStyle {if $act_tab == 'description'}act{/if}">
            <p class="mobileCaption">{$tabs.description}</p>
            {$product.description}
        </div>
        {/if}
        
        {if $tabs.files}
            <div class="tab tab-files {if $act_tab == 'files'}act{/if}">
                <p class="mobileCaption">{$tabs.files}</p>
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
                
        {if $tabs.comments}
        <div class="tab tab-comments {if $act_tab == 'comments'}act{/if}">
            <p class="mobileCaption">{$tabs.comments}</p>
            {moduleinsert name="\Comments\Controller\Block\Comments" type="\Catalog\Model\CommentType\Product"}            
        </div>
        {/if}
    </div>    
</div>