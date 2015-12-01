{extends file="%THEME%/body.tpl"} {* Указываем родителя данного шаблона *}
{block name="content"} {* Указываем какую часть будем перезаписывать *}

<div class="container main">  	      
	<div class="row sliders-wrap">
		<div class="col-sm-24 sliders-wrap-inner">
			<div class="inner-border"></div>
			<div class="link-wrap man">
				<a href="{$router->getUrl('catalog-front-listproducts')}dlya-muzhchin/" class="link">
					<span class="title h3">Мужская коллекция</span>
					<span class="all h5 cornered-border">Смотреть все</span>
				</a>
			</div>
			<div class="link-wrap woman">
				<a href="{$router->getUrl('catalog-front-listproducts')}dlya-zhenshchin/" class="link">
					<span class="title h3">Женская коллекция</span>
					<span class="all h5 cornered-border">Смотреть все</span>
				</a>
			</div>
			<div class="slider1-wrap">
				<div id="slider1" class="carousel slide vertical">
					<!-- Carousel items -->
					<div class="carousel-inner">
						<div class="active item">
							<img src="{$THEME_IMG}/slider_auto_01.jpg">
						</div>
						<div class="item">
							<img src="{$THEME_IMG}/slider_auto_02.jpg">
						</div>
						<div class="item">
							<img src="{$THEME_IMG}/slider_auto_03.jpg">
						</div>
					</div>
				</div>
			</div>
			<div class="slider2-wrap">
				<div id="slider2" class="carousel slide vertical">
					<!-- Carousel items -->
					<div class="carousel-inner">
						<div class="active item">
							<img src="{$THEME_IMG}/slider_limo_01.jpg">
						</div>
						<div class="item">
							<img src="{$THEME_IMG}/slider_limo_02.jpg">
						</div>
						<div class="item">
							<img src="{$THEME_IMG}/slider_limo_03.jpg">
						</div>
					</div>
				</div>
			</div>
			<div class="slider-nav-wrap cornered-border">
				<!-- Carousel nav -->
				<!-- <a class="left" href="#slider1" data-slide="prev">‹</a> -->
				<a class="right" href="#slider1" data-slide="next"><i class="fa fa-angle-down"></i></a>
			</div>
		</div>
	</div>

	<div class="row specBlock">
		{moduleinsert name="\Catalog\Controller\Block\TopProducts" indexTemplate='theme:autojack/blocks/topproducts/top_products.tpl' pageSize='4' dirs='9' order='dateof'}
	</div>

</div>

<div class="infoBlock1">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<a href="">
					<img src="{$THEME_IMG}/info_banner_1.jpg" alt="">
				</a>
			</div>
			<div class="col-sm-12">
				<a href="">
					<img src="{$THEME_IMG}/info_banner_2.jpg" alt="">
				</a>
			</div>
		</div>
	</div>
</div>

<div class="container lastNews">
	{* Вставляем блок "новости" из категории news *}
	{moduleinsert name="\Article\Controller\Block\LastNews" category="news" indexTemplate='theme:autojack/blocks/lastnews/lastnews.tpl' pageSize='3'}
</div>

<div class="about">
	<div class="aboutHead">
		<div class="preview">О бренде</div>
		<div class="title">Куртки AUTO JACK</div>
		<div class="desc">Немецко-российское качество с 2000 года</div>
	</div>
	<div class="aboutBody container">
		<div class="row">
			<div class="col-md-12 col-md-offset-6">
				<p>Немецко-­российский бренд AutoJack известен на рынке с 2000 года и добился широкой известности благодаря инновационному подходу к созданию верхней одежды. В нашей продукции используются лучшие материалы и технологии со всего мира, объединенные в фирменную систему Climate-Control®. Climate-Control® гарантирует потребителю идеальный комфорт в любых условиях. </p> 

				<p>Одежда от AutoJack предназначена для современного городского жителя, который проводит за рулем и в помещении не меньше времени, чем на улице.  Поэтому система Climate-Control® защищает не только от неблагоприятных погодных условий, но и от перегрева. В одежде от AutoJack вам будет не холодно и не жарко.</p>
			</div>
		</div>
	</div>
</div>


{/block}
