<div class="container article">
	<div class="row text-center mainTitle">
	    <h1><span class="whiteBack">{$menu_item.title}</span></h1>
	</div>
	<div class="col-md-16 col-md-offset-4 content">
		{if !empty($menu_item.img)}
	        <img class="mainImage" src="{$menu_item.__img->getUrl(770, 770, 'xy')}" alt="{$menu_item.title}"/>
	    {/if}
	    {$menu_item.content}
	</div>
</div>
