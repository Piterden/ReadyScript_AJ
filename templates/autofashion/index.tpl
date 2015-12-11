{extends file="%THEME%/wrapper.tpl"}
{block name="content"}
<div class="container main"> 

	{* Баннеры *}
	{moduleinsert name="\Banners\Controller\Block\Slider" zone="home_slider"}

	<div class="row specBlock">
		{moduleinsert name="\Catalog\Controller\Block\TopProducts" indexTemplate='blocks/topproducts/top_products.tpl' pageSize='4' dirs='9' order='dateof'}
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
	{moduleinsert name="\Article\Controller\Block\LastNews" category="news" indexTemplate='blocks/lastnews/lastnews.tpl' pageSize='3'}
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

<script>
jQuery(document).ready(function($) {
	var $parallaxBlock = $('.aboutHead');
  	//console.log($(window).height());
  	$(window).scroll(function() {
  		var activeHeight = $(this).height() + $parallaxBlock.height();
  		var scrollTop = $(this).scrollTop();
  		var startOffset = $parallaxBlock.offset().top - $(this).height();
        if (scrollTop >= startOffset) {
        	var diff = scrollTop - startOffset;
		  	var offset = - diff * ((500 - $parallaxBlock.height()) / activeHeight);
		  	//console.log(offset);
            $parallaxBlock.css('background-position', '0 ' + offset + 'px');
        } else {
           
        }
        //console.log($(this).scrollTop()); //check
    });

});
</script>


	<!-- <div class="box mt40">
		{* Лидеры продаж *}
		{moduleinsert name="\Catalog\Controller\Block\TopProducts" dirs="samye-prodavaemye-veshchi" pageSize="5"}
		
		<div class="oh mt40">
			<div class="left">
				{* Новости *}
				{moduleinsert name="\Article\Controller\Block\LastNews" indexTemplate="blocks/lastnews/lastnews.tpl" category="2" pageSize="4"}
			</div>
			<div class="right">
				{* Оплата и возврат *}
				{moduleinsert name="\Article\Controller\Block\Article" indexTemplate="blocks/article/main_payment_block.tpl" article_id="molodezhnaya--glavnaya--ob-oplate"}
				
				{* Доставка *}
				{moduleinsert name="\Article\Controller\Block\Article" indexTemplate="blocks/article/main_delivery_block.tpl" article_id="molodezhnaya--glavnaya--o-dostavke"}
			</div>
		</div>
		{* Товары во вкладках *}
		{moduleinsert name="\Catalog\Controller\Block\ProductTabs" categories=["populyarnye-veshchi", "novye-postupleniya"] pageSize=6}
		
		{* Бренды *}
		{moduleinsert name="\Catalog\Controller\Block\BrandList"}
	</div> -->
{/block}