<div class="costsWrapper">
	{foreach $costs as $name => $cost}
		<div class="imlContainer_{$delivery.id} costWrap row">
		    <div class="infoBlock col-sm-9">{$name}</div>
		    <div class="costBlock col-sm-3">{$cost|default:"бесплатно"}</div>
		</div>
	{/foreach}
</div>
