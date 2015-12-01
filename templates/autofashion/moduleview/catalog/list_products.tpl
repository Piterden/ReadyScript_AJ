{addjs file="jquery.changeoffer.js"}
{$shop_config=ConfigLoader::byModule('shop')}
{$check_quantity=$shop_config.check_quantity}

{if $no_query_error}
<div class="noQuery">
	Не задан поисковый запрос
</div> 
{else}
<div id="products" class="productList{if $shop_config} shopVersion{/if}{if $category.level == 0 && $category.is_spec_dir == N} container{/if}">
	<!-- {if $category.description}<article class="categoryDescription">{$category.description}</article>{/if} -->
	{if count($sub_dirs)}{assign var=one_dir value=reset($sub_dirs)}{/if}
	{if (empty($query) || (count($sub_dirs) && $dir_id != $one_dir.id)) && $category.level == 0 && $category.is_spec_dir == N}
		<div class="row productListTitle">
			<div class="col-md-24 text-center mainTitle">
				{if !empty($query)}
					<h1>Результаты поиска</h1>
				{else}
					<h1>{$category.name}</h1>
				{/if}
			</div>
		</div>
		<div class="row subCategories topCategory">
			{foreach $sub_dirs as $item name=sub_dir}
			{if $smarty.foreach.sub_dir.index == 3}
			    {break}
			{/if}
			<div class="col-md-8 subCategory {$item.alias}">
				<div class="wrapper">
					<a href="{urlmake category=$item._alias p=null f=null bfilter=null}">
						<img src="{$item->__image->getUrl(358,528)}" alt="{$item.name}">
						<span class="textBlock">
							<span class="wrapper">
								<span class="icon"></span>
								<span class="categoryTitle h3">{$item.name}</span>
								<span class="lookAtAll h5 cornered-border">Смотреть все</span>
							</span>
						</span>
					</a>
				</div>	
			</div>
			{/foreach}
		</div>
		<div class="row top">
			{moduleinsert name="\Catalog\Controller\Block\TopProducts" indexTemplate='blocks/topproducts/top_products.tpl' pageSize='4' dirs='10' order='dateof'}
		</div>
	
	{else}
		{if count($list)}
			{if $view_as == 'blocks'}
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
					{static_call var=added_ids callback=['\Wishlist\Model\WishApi', 'getWishedProductIds'] params=[$current_user.id]}
				    <!-- /Sortline -->
					{foreach $list as $product}
						{$main_image=$product->getMainImage()}
			            <div class="col-sm-8 productItem" {$product->getDebugAttributes()} data-id="{$product.id}">
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
			                                <h3 class="title">{$product.title}</h3>
			                                <div class="desc">{$product.short_description}</div>
			                            </div>
			                        </div>
			                    </a>
			                </div>
						</div>
					{/foreach}
				</div>
			{else}
				<table class="productTable">
					{foreach $list as $product}
					<tr {$product->getDebugAttributes()} data-id="{$product.id}">
						{$main_image=$product->getMainImage()}
						<td class="image"><a href="{$product->getUrl()}"><img src="{$main_image->getUrl(100,100)}" alt="{$main_image.title|default:"{$product.title}"}"/></a></td>
						<td class="info">
							<a href="{$product->getUrl()}" class="title">{$product.title}</a>
							{if $product.barcode}<p class="barcode">Артикул: {$product.barcode}</p>{/if}
							<p class="descr">{$product.short_description}</p>
						</td>
						<td class="price">{$product->getCost()} {$product->getCurrency()}</td>
						<td class="actions">
							{if $shop_config}
								{if $product->shouldReserve()}
									<a href="{$router->getUrl('shop-front-reservation', ["product_id" => $product.id])}" class="button reserve inDialog">Заказать</a>
								{else}        
									{if $check_quantity && $product.num<1}
										<div class="noAvaible">Нет в наличии</div>
									{else}
										{if $product->isOffersUse() || $product->isMultiOffersUse()}
											<span data-href="{$router->getUrl('shop-front-multioffers', ["product_id" => $product.id])}" class="button showMultiOffers inDialog noShowCart">В корзину</span>
										{else}
											<a data-href="{$router->getUrl('shop-front-cartpage', ["add" => $product.id])}" class="button addToCart noShowCart" data-add-text="Добавлено">В корзину</a>
										{/if}                                                                                        
									{/if}
								{/if}
							{/if}
							<a class="compare{if $product->inCompareList()} inCompare{/if}"><span>Сравнить</span><span class="already">Добавлено</span></a>
						</td>
					</tr>                       
					{/foreach}
				</table>
			{/if}
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
	{/if}

</div>
{/if}

<!-- {*
<div class="col-md-6" {$product->getDebugAttributes()} data-id="{$product.id}">
	{$main_image=$product->getMainImage()}
	<a href="{$product->getUrl()}" class="image">{if $product->inDir('new')}<i class="new"></i>{/if}<img src="{$main_image->getUrl(188,258)}" alt="{$main_image.title|default:"{$product.title}"}"/></a>
	<a href="{$product->getUrl()}" class="title">{$product.title}</a>
	<p class="price">{$product->getCost()} {$product->getCurrency()} 
		{$last_price=$product->getCost('Зачеркнутая цена')}
		{if $last_price>0}<span class="last">{$last_price} {$product->getCurrency()}</span>{/if}</p>
	<div class="hoverBlock">
		<div class="back"></div>
		<div class="main">
			{if $shop_config}
				{if $product->shouldReserve()}
					<a data-href="{$router->getUrl('shop-front-reservation', ["product_id" => $product.id])}" class="button reserve inDialog">Заказать</a>
				{else}        
					{if $check_quantity && $product.num<1}
						<span class="noAvaible">Нет в наличии</span>
					{else}
						{if $product->isOffersUse() || $product->isMultiOffersUse()}
							<span data-href="{$router->getUrl('shop-front-multioffers', ["product_id" => $product.id])}" class="button showMultiOffers inDialog noShowCart">В корзину</span>
						{else}
							<a data-href="{$router->getUrl('shop-front-cartpage', ["add" => $product.id])}" class="button addToCart noShowCart" data-add-text="Добавлено">В корзину</a>
						{/if}                                                            
					{/if}
				{/if}
			{/if}                            
			<a class="compare{if $product->inCompareList()} inCompare{/if}"><span>К сравнению</span><span class="already">Добавлено</span></a>
		</div>
	</div>
</div>
*} -->          