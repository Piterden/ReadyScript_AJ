<div class="container article">
	<div class="row text-center mainTitle">
	    <h1><span class="whiteBack">{$article.title}</span></h1>
	</div>
	<div class="col-md-16 col-md-offset-4 content">
		{if !empty($article.image)}
	        <img class="mainImage" src="{$article.__image->getUrl(770, 770, 'xy')}" alt="{$article.title}"/>
	    {/if}
	    {$article.content}
	</div>
</div>

<!-- 		    <div class="dateWrap">
		    	<div class="dateBlock">
		            <div class="date">
		                <div class="day h2">
		                    {$item.dateof|dateformat:"%d"}
		                </div>
		                <div class="month">
		                    {$item.dateof|dateformat:"%v"}
		                </div>
		            </div>
		        </div>
		    </div>


	<div class="col-md-24">
		{*moduleinsert name="\Photo\Controller\Block\PhotoList" type="article" route_id_param="article_id"*}
	</div> -->