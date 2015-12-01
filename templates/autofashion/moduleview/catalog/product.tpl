{addjs file="jcarousel/jquery.jcarousel.min.js"}
{addjs file="jquery.changeoffer.js?v=2"}
{addjs file="jquery.zoom.min.js"}
{addjs file="product.js"}
{assign var=shop_config value=ConfigLoader::byModule('shop')}
{assign var=check_quantity value=$shop_config.check_quantity}
{assign var=catalog_config value=$this_controller->getModuleConfig()} 

<div class="container">
	<div class="product{if !$product->isAvailable()} notAvaliable{/if}{if $product->canBeReserved()} canBeReserved{/if}{if $product.reservation == 'forced'} forcedReserve{/if}" data-id="{$product.id}">
		<div class="row title">
			<div class="col-md-14 col-md-offset-5">
				<h2 class="text-center">{$product.title}</h2>
			</div>
		</div>
		<div class="row">
			<div class="col-md-8">
				{* Фотографии товара *}
				<div class="productImages">
					<div class="specCategoriesBlockWrap">
						{foreach $product->getSpecDirs() as $specCategory}
							{if $specCategory.image != '' && $product->inDir($specCategory.alias)}
							<div class="specCategory">
								<img src="{$specCategory->__image->getLink()}" alt="{$specCategory.name}" title="{$specCategory.name}">
							</div>
							{/if}
						{/foreach}
					</div>
					<div class="productImagesWrap">
						<div class="main">
							{$images=$product->getImages()}
							{if !$product->hasImage()} 
								{$main_image=$product->getMainImage()}       
								<span class="item"><img src="{$main_image->getUrl(350,486,'xy')}" alt="{$main_image.title|default:"{$product.title}"}"/></span>
							{else}               
								{* Главное фото *} 
								{if $product->isOffersUse()}
								   {* Назначенные фото у первой комлектации *}
								   {$offer_images=$product.offers.items[0].photos_arr}  
								{/if}
								{foreach $images as $key => $image}
								   <a href="{$image->getUrl(800,600,'xy')}" data-id="{$image.id}" class="item mainPicture {if ($offer_images && ($image.id!=$offer_images.0)) || (!$offer_images && !$image@first)} hidden{/if} zoom" {if ($offer_images && in_array($image.id, $offer_images)) || (!$offer_images)}rel="bigphotos"{/if} data-n="{$key}" target="_blank" data-zoom-src="{$image->getUrl(947, 1300)}"><img class="winImage" src="{$image->getUrl(316,500,'xy')}" alt="{$image.title|default:"{$product.title} фото {$key+1}"}"></a>
								{/foreach}
							{/if}
						</div>
					</div>
				</div>
			</div>
			{* Боковая линейка фото *}
			{if count($images)>1}
			<div class="gallery col-md-2">
				<div class="wrap">
					<ul>
						{foreach $images as $key => $image}
						<li data-id="{$image.id}" class="{if $offer_images && !in_array($image.id, $offer_images)}hidden{elseif !$first++} first{/if}{if ($offer_images && ($image.id==$offer_images.0)) || (!$offer_images && $image@first)} active{/if}"><a href="{$image->getUrl(800,600,'xy')}" class="preview" data-n="{$key}" target="_blank"><img src="{$image->getUrl(50, 75)}"></a></li>
						{/foreach}
						<script>
							jQuery(document).ready(function($) {
								console.log($('.productImages .gallery ul li'));
								$('.productImages .gallery ul li a').on('click', function() {
									console.log($(this));
									$(this).parent('li').addClass('active').siblings().removeClass('active');
								});
							});
						</script>
					</ul>
				</div>
				<a class="control prev"><i class="fa fa-chevron-up"></i></a>
				<a class="control next"><i class="fa fa-chevron-down"></i></a>
			</div>
			{/if}
			<div class="col-md-8">
			{* Подгружаем остатки по складам, т.к. при смене комплектации 
			будет изменяться и отображение остатков *}
			{$product->fillOffersStockStars()}
			{*Подгружаем цвета*}
			{modulegetvars name="\Colors\Controller\BlockValues" var="colors"}
			{modulegetvars name="\Materials\Controller\BlockValues" var="materialsdata"}
			
			{if $product->isMultiOffersUse()}
	            {* Многомерные комплектации *}
	            <div class="multiOffers">
					<div class="sku text-center">
						Артикул: <span class="offerBarcode">{$product.barcode}</span>
					</div>
	                <!-- <div class="pname">{$product.offer_caption|default:'Комплектация'}</div> -->
	                {* Подгрузим у многомерных комплектаций фото к их вариантам *}
	                {$product->fillMultiOffersPhotos()}
	                {* Переберём доступные многомерные комплектации *}
	                {foreach $product.multioffers.levels|@array_reverse as $level}
	                    {if !empty($level.values) && $level.title != 'Цвет'}
	                        <div class="multiofferTitle">{if $level.title}{$level.title}{else}{$level.prop_title}{/if}</div>
	                        {if !$level.is_photo && !isset($level.values_photos)} {* Если отображать не как фото (выпадающим списком)*}
	                            <select name="multioffers[{$level.prop_id}]" data-prop-title="{if $level.title}{$level.title}{else}{$level.prop_title}{/if}">
	                                {foreach $level.values as $value}
	                                    <option value="{$value.val_str}">{$value.val_str}</option>   
	                                {/foreach}
	                            </select>
	                            <div class="multiofferBlock multiofferBlock{$level.prop_id}">
	                                {foreach $level.values as $value key=i}
	                                    <div class="moItem{if $i == 1} active{/if}"><div class="moItemInner">{$value.val_str}</div></div>
	                                {/foreach}
	                            </div>
	                            <div class="clearfix"></div>
	                        {else}
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
	                    {else if !empty($level.values) && $level.title == 'Цвет'}
	                    	<div class="multiofferTitle">{if $level.title}{$level.title}{else}{$level.prop_title}{/if}</div>
                            <select name="multioffers[{$level.prop_id}]" data-prop-title="{if $level.title}{$level.title}{else}{$level.prop_title}{/if}">
                                {foreach $level.values as $value}
                                    <option value="{$value.val_str}">{$value.val_str}</option>   
                                {/foreach}
                            </select>
                            <div class="multiofferBlock multiofferBlock{$level.prop_id}">
                                {foreach $level.values as $value key=i}
                                    <div class="moItem{if $i == 1} active{/if}"><div class="moItemInner" style="background-color:#fff;background-image: linear-gradient( -45deg, {$colors.colors[$value.val_str].color2} 0%, {$colors.colors[$value.val_str].color2} 50%, {$colors.colors[$value.val_str].color1} 50%, {$colors.colors[$value.val_str].color1} 50%);" title="{$value.val_str}">{$value.val_str}</div></div> 
                                {/foreach}
                            </div>
                            <div class="clearfix"></div>
	                    {/if}
	                {/foreach}

		            {* Вывод наличия на складах *}
			        {assign var=stick_info value=$product->getWarehouseStickInfo()}
			        {assign var=stock value=$product->getWarehouseStock()}
			        {if !empty($stick_info.warehouses)}
			            <div class="warehouseDiv">
			                <div class="titleDiv">Наличие:</div>
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
			        {else}
						<div class="restWrap">
							{foreach from=stock item=rest}
								
							{/foreach}
						</div>
			        {/if}

	            </div>
	            
	            {if $product->isOffersUse()}
	                {foreach from=$product.offers.items key=key item=offer name=offers}
	                    <input value="{$key}" type="hidden" name="hidden_offers" class="hidden_offers" {if $smarty.foreach.offers.first}checked{/if} id="offer_{$key}" data-info='{$offer->getPropertiesJson()}' {if $check_quantity}data-num="{$offer.num}"{/if} {if $catalog_config.use_offer_unit}data-unit="{$offer->getUnit()->stitle}"{/if} data-change-cost='{ ".offerBarcode": "{$offer.barcode|default:$product.barcode}", ".myCost": "{$product->getCost(null, $key)}", ".lastPrice": "{$product->getCost('Зачеркнутая цена', $key)}"}' data-images='{$offer->getPhotosJson()}' data-sticks='{$offer->getStickJson()}'/>
	                {/foreach}
	                <input type="hidden" name="offer" value="0"/>
	            {/if}


	        {elseif $product->isOffersUse()}
	            {* Простые комплектации *}
	            <!-- <div class="packages">
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
	            </div> -->
	        {/if}

			

			</div>
			
			<div class="col-md-6">
				<div class="priceBlock">
					{assign var=last_price value=$product->getCost('Зачеркнутая цена')}
					<div class="price">
							<span class="myCost">{$product->getCost()}</span>{$product->getCurrency()}
							{* Если включена опция единицы измерения в комплектациях *}
							{if $catalog_config.use_offer_unit && $product->isOffersUse()}
								<span class="unitBlock">/ <span class="unit">{$product.offers.items[0]->getUnit()->stitle}</span></span>
							{/if}
					</div>
					{if $last_price>0}<div class="lineTroughtPrice lastPrice">{$last_price}{$product->getCurrency()}</div>{/if}
					<div class="withoutDelivery">Без стоимости доставки</div>
				</div>

				<div class="amountWrap">
					<div class="amount">Количество: <input value="1" type="number" name="amount" class="fieldAmount"></div>					
				</div>
	        	
	        	{if $shop_config}
	        	<div class="addToCartWrap">
					<a data-href="{$router->getUrl('shop-front-cartpage', ["add" => $product.id])}" class="button addToCart" data-add-text="Добавлено" title="Добавить в корзину">
						<img src="{$THEME_IMG}/cart.png" alt="Add to Cart" width="32px" height="32px">
				        <div class="toCartText">
							В корзину
				        </div>
					</a>                    
	        	</div>
				{/if}
	            {if !$shop_config || (!$product->shouldReserve() && (!$check_quantity || $product.num>0))}
	                {if $catalog_config.buyinoneclick }
	                	<div class="buyOneClickWrap">
		                    <a data-href="{$router->getUrl('catalog-front-oneclick',["product_id"=>$product.id])}" title="Купить в 1 клик" class="button buyOneClick inDialog">Купить в 1 клик</a>
	                	</div>
	                {/if}                        
	            {/if}            
	            <a class="compare{if $product->inCompareList()} inCompare{/if}"><span>Сравнить</span><span class="already">Добавлено</span></a>
			</div>
		</div>
		<div class="row">
			<div class="col-md-24">
				<!-- Nav tabs -->
				<ul class="nav nav-tabs productNavTabs" role="tablist">
					<li role="presentation" class="active">
						<a href="#description" aria-controls="description" role="tab" data-toggle="tab">Описание</a>
					</li>
					<li role="presentation">
						<a href="#specification" aria-controls="specification" role="tab" data-toggle="tab">Спецификация</a>
					</li>
					<li role="presentation">
						<a href="#materials" aria-controls="materials" role="tab" data-toggle="tab">Материалы</a>
					</li>
					<li role="presentation">
						<a href="#functions" aria-controls="functions" role="tab" data-toggle="tab">Функции</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="row">
		{assign var="properties" value=$product->fillProperty()}
		{assign var="materials" value=$properties[1].properties[4]}
		{assign var="options" value=$properties[1].properties[5]}
			<!-- Tab panes -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane fade in active" id="description">
					<div class="productDescription col-md-12 col-md-offset-6">
						{$product.description}
					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="specification">
					<div class="specificationBlock">
						{foreach from=$properties[6].properties item=prop key=key}
							{if $prop.type == 'list'}
								<div class="productSpecification">
									<div class="label">{$prop.title}</div>
									<div class="value">
										{foreach from=$prop.value item=item key=key}
											<div class="propValue">{$item}</div>
										{/foreach}
									</div>
								</div>
							{/if}
						{/foreach}
						{foreach from=$properties[6].properties item=prop key=key}
							{if $prop.type == 'bool'}
								<div class="productSpecification">
									<div class="label">{$prop.title}</div>
									<div class="value">
										<div class="propValue">
											{if $prop.value == '0'}нет{elseif $prop.value == '1'}есть{/if}
										</div>
									</div>
								</div>
							{/if}
						{/foreach}
						<table>
							
						</table>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="materials">
					<div class="materialsBlock">
						{foreach $materials.value as $material}
							<div class="materialItem col-md-6">
								<div class="title">
									{if $materialsdata.materials[$material]->icon != ''}
										<div class="icon"><img src="{$materialsdata.materials[$material]->__icon->getLink()}" alt="{$material}"></div>
									{/if}
									<div class="text">{$material}</div>
									<div class="clearfix"></div>
								</div>
								<div class="description">{$materialsdata.materials[$material].description}</div>
							</div>
						{/foreach}
					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="functions">
				<div class="optionsWrap">
					<div class="optionsList">
						{foreach $options.value as $option}
							<div class="optionItem col-md-6">
								<i class="fa fa-check-square-o"></i> {$option}
							</div>
						{/foreach}
						<div class="clearfix"></div>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="container recommendedWrap">
	<div class="row">
		{moduleinsert name="\Catalog\Controller\Block\Recommended"}
	</div>
</div>

<div class="container commentsWrap">
	{moduleinsert name="\Comments\Controller\Block\Comments" type="\Catalog\Model\CommentType\Product" pageSize=5}
</div>

