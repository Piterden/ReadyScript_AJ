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
			<div class="col-md-24">
				<p style="text-align: justify;">Официальный интернет-магазин <a href="http://www.auto-jack.com" target="_blank">AutoJack</a> & <a href="http://www.limo-lady.com" target="_blank">LimoLady</a> в России</p>
				<p style="text-align: justify;">Фирменный интернет-магазин одежды с климат-контролем - это возможность купить верхнюю одежду с климат-контролем напрямую от производителя. В ассортименте интернет-магазина - мужские и женские зимние и демисезонные куртки, а также ветровки, толстовки, шапки, носки и брюки.</p>
				<p style="text-align: justify;">Только у нас - верхняя одежда <a href="http://www.auto-jack.com" target="_blank">AutoJack</a> & <a href="http://www.limo-lady.com" target="_blank">LimoLady</a> от производителя.</p>
				<p style="text-align: justify;">Бренд мужской одежды с климат-контролем AutoJack был основан в 2000 году и успел завоевать широкую популярность на рынке России. В 2003 году разработчики специально для прекрасных дам создали линию LimoLady, в которую входят женские куртки и ветровки с климат-контролем.</p>
				<p style="text-align: justify;">AutoJack и LimoLady представляют одежду нового поколения, которая не только поможет в создании стильного образа, но и обеспечит непревзойденный комфорт в любых условиях. Все это - благодаря использованию лучших материалов и последних технологий, собранных из нескольких стран мира (в том числе - Германия, Швейцария, Италия) и объединенных в фирменную систему Climate-Control<sup>&reg;</sup>.</p>
				<p style="text-align: justify;">В официальном интернет-магазине <a href="http://www.auto-jack.com" target="_blank">AutoJack</a> & <a href="http://www.limo-lady.com" target="_blank">LimoLady</a> к вашим услугам выгодные цены, гарантия качества, доставка на дом, безопасные способы оплаты, и многое другое. Наши сотрудники оперативно ответят на любые ваши вопросы, помогут подобрать ту модель, которая подойдет именно вам. Вас порадует широкий выбор моделей курток с натуральным и искусственным мехом, среди которых – женские и мужские куртки больших размеров. В многообразии фасонов и богатой палитре модных расцветок каждый обязательно найдет что-то на свой вкус.</p>
				<p style="text-align: justify;">На сайте публикуется информация о новых скидках и распродажах, а значит,  постоянные посетители интернет-магазина получат возможность купить куртки с климат-контролем от AutoJack и LimoLady на самых удачных условиях.</p>

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