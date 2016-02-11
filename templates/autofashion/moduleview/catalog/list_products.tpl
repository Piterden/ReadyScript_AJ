{addjs file="jquery.changeoffer.js"}
{$shop_config=ConfigLoader::byModule('shop')}
{$check_quantity=$shop_config.check_quantity}

{if $no_query_error}
<div class="noQuery">
	Не задан поисковый запрос
</div>
{else}
<div id="products" class="productList{if $shop_config} shopVersion{/if}">
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
					{include file="%THEME%/moduleview/catalog/product_card.tpl" cardWidth="8"}
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

	{if $category.description}<article class="categoryDescription">{$category.description}</article>{/if}
	{* {if count($sub_dirs)}{assign var=one_dir value=reset($sub_dirs)}{/if} *}
</div>
{/if}

		                                {* <pre>{$product->getBrand()->title}</pre> *}
