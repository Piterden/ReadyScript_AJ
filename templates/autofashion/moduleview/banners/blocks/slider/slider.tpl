{addjs file="main_slider.js"}
{if $zone}
	{$banners=$zone->getBanners()}
	<div class="slider{if $zone.alias=='main_left'}1{elseif $zone.alias=='main_right'}2{/if}-wrap">
		<div id="slider{if $zone.alias=='main_left'}1{elseif $zone.alias=='main_right'}2{/if}" class="carousel slide vertical">
			<!-- Carousel items -->
			<div class="carousel-inner">
				{foreach $banners as $banner}
					<div class="item{if $banner@first} active{/if}">
						{if $banner.link}<a href="{$banner.link}"{if $banner.targetblank}target="_blank"{/if}>{/if} 
						<img src="{$banner->getBannerUrl()}" alt="{$banner.title}">
						{if $banner.link}</a>{/if}
					</div>
				{/foreach}
			</div>
		</div>
	</div>
{else}
	<div class="row sliders-wrap">
		<div class="col-sm-24 sliders-wrap-inner">
			<div class="inner-border"></div>
			<div class="link-wrap man">
				<a href="{$router->getUrl('catalog-front-listproducts')}dlya-muzhchin/" class="link">
					<span class="title h3">Мужская коллекция</span>
					<span class="lookAtAll h5 cornered-border">Смотреть все</span>
				</a>
			</div>
			<div class="link-wrap woman">
				<a href="{$router->getUrl('catalog-front-listproducts')}dlya-zhenshchin/" class="link">
					<span class="title h3">Женская коллекция</span>
					<span class="lookAtAll h5 cornered-border">Смотреть все</span>
				</a>
			</div>
			{moduleinsert name="\Banners\Controller\Block\Slider" zone="main_left"}
			{moduleinsert name="\Banners\Controller\Block\Slider" zone="main_right"}
			<div class="slider-nav-wrap cornered-border">
				<!-- Carousel nav -->
				<!-- <a class="left" href="#slider1" data-slide="prev">‹</a> -->
				<a class="right" href="#slider1" data-slide="next"><i class="fa fa-angle-down"></i></a>
			</div>
		</div>
	</div>
{/if}