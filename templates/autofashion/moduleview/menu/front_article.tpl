<div class="container article">
	{if !empty($article.image)} 
		<img src="{$article.__image->getLink()}" alt="{$article.title}">
		<div class="title text-center onImage" style="position:absolute;">
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
</div>