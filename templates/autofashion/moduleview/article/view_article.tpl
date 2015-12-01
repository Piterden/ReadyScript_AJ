<div class="container">
	<div class="col-md-16 col-md-offset-4 article">
	    <h1>{$article.title}</h1>
	    
	    {if !empty($article.image)}
	        <img class="mainImage" src="{$article.__image->getUrl(700, 304, 'xy')}" alt="{$article.title}"/>
	    {/if}
	    {$article.content}
	</div>
</div>
<div class="container article">
	{if !empty($article.image)} 
		<img src="{$article.__image->getLink()}" alt="{$article.title}">
		<div class="title text-center onImage" style="position:absolute;">
		    <div class="dateWrap">
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
		    <h1>{$article.title}</h1>
		</div>
	{else}
	<div class="title text-center">
	    <h1>{$article.title}</h1>
	</div>
	{/if}	
	<div class="col-md-16 col-md-offset-4 content">
	    {$article.content}
	</div>
	<div class="col-md-24">
		{moduleinsert name="\Photo\Controller\Block\PhotoList" type="article" route_id_param="article_id"}
	</div>
</div>