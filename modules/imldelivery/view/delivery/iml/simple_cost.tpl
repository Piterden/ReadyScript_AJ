<div class="costsWrapper">
	{foreach $costs as $name => $cost}
		<div class="imlContainer costWrap row {$name|translit|replace:' ':'-'}">
		    <div class="infoBlock col-sm-9">{$name}</div>
		    <div class="costBlock col-sm-3">{if $cost > 0}{$cost}{else}бесплатно{/if}</div>
		</div>
	{/foreach}
</div>
