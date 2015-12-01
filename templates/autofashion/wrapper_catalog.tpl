{extends file="%THEME%/wrapper.tpl"}
{block name="content"}
{if ( $category.level != 0 || $category.is_spec_dir == Y ) && count($list)}
	<div class="container">
		<div class="row productListTitle">
			<div class="col-md-24 text-center mainTitle">
				<h1>{$category.name}</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div id="filtersBlock">
					{moduleinsert name="\Catalog\Controller\Block\SideFilters"}
				</div>	
			</div>
			<div class="col-md-18">
				{$app->blocks->getMainContent()}
			</div>
		</div>
	</div>
{else}
	{$app->blocks->getMainContent()}
{/if}
{/block}