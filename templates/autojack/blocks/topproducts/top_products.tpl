{if $products}
<section class="topProducts pl{$_block_id}">
		<div class="titleWrap col-sm-24 text-center">
			<h3>{$dir.name}</h3>            
		</div>
		<div class="clearfix"></div>
		<div class="productWrap">
			{foreach from=$products item=product}
			{$main_image=$product->getMainImage()}
			<div class="col-sm-6 productItem" {$product->getDebugAttributes()}>
				<div class="productItemWrap">
					<div class="wishWrap">
						<div class="wishBox">
							<img src="{$THEME_IMG}/wishlist.png" alt="Add to Wishlist">
						</div>
						<div class="wishDesc">Добавить в список<br>желаемых покупок</div>
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

				<!-- <a href="{$product->getUrl()}" class="pic">
				<span class="labels">
					{foreach from=$product->getMySpecDir() item=spec}
					{if $spec.image}
						<img src="{$spec->__image->getUrl(62,62, 'xy')}">
					{/if}
					{/foreach}
				</span>
				
				<a href="{$product->getUrl()}" class="info">
					<h3>{$product.title}</h3>
					<p class="group">
						<span class="scost">{$product->getCost()} {$product->getCurrency()}</span>
						<span class="name">{$product->getMainDir()->name}</span>
					</p>
				</a> -->
			</div>
			{/foreach}
			<div class="clearfix"></div>
			<!-- {if $paginator->total_pages > $paginator->page}
				<a data-pagination-options='{ "appendElement":".productList", "context":".pl{$_block_id}" }' data-href="{$router->getUrl('catalog-block-topproducts', ['_block_id' => $_block_id, 'page' => $paginator->page+1])}" class="onemoreEmpty ajaxPaginator">посмотреть еще</a>
			{/if} -->
		</div>
</section>
{else}
	{include file="theme:default/block_stub.tpl"  class="blockTopProducts" do=[
		[
			'title' => t("Добавьте категорию с товарами"),
			'href' => {adminUrl do=false mod_controller="catalog-ctrl"}
		],
		[
			'title' => t("Настройте блок"),
			'href' => {$this_controller->getSettingUrl()},
			'class' => 'crud-add'
		]
	]}
{/if}