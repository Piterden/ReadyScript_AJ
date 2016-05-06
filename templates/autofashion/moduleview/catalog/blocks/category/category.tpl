{* Список категорий из 4-х уровней*}
{if $dirlist}
{* Первый уровень *}
<ul class="category">
	{foreach from=$dirlist item=dir}
	<li class="{$dir.fields.alias}{if in_array($dir.fields.id, $pathids)} act{/if}" {$dir.fields->getDebugAttributes()}><a href="{$dir.fields->getUrl()}">{$dir.fields.name}</a>
		{* Второй уровень *}
		{if !empty($dir.child)}
		{assign var=cnt value=count($dir.child)}
		{if $cnt>9 && $cnt<21}
			{assign var=columns value="twoColumn"}
		{elseif $cnt>20}
			{assign var=columns value="threeColumn"}
		{/if}
		<ul {if $columns}class="{$columns}"{/if}>
			{foreach from=$dir.child item=item}
			<li {if in_array($item.fields.id, $pathids)}class="act"{/if} {$item.fields->getDebugAttributes()}><a href="{$item.fields->getUrl()}">{$item.fields.name}</a>
				{* Третий уровень *}
				{if !empty($item.child)}
				<ul>
					{foreach $item.child as $subdir}
						<li {if in_array($subdir.fields.id, $pathids)}class="act"{/if} {$subdir.fields->getDebugAttributes()}><a href="{$subdir.fields->getUrl()}">{$subdir.fields.name}</a>
						{* Четвертый уровень *}
						{if !empty($subdir.child)}
							<ul>
							{foreach $subdir.child as $subdir2}
								<li {if in_array($subdir2.fields.id, $pathids)}class="act"{/if} {$subdir2.fields->getDebugAttributes()}><a href="{$subdir2.fields->getUrl()}">{$subdir2.fields.name}</a>
								</li>
							{/foreach}
							</ul>
						{/if}
						</li>
					{/foreach}
				</ul>
				{/if}
			</li>
			{/foreach}
		</ul>
	{/if}
	</li>
	{/foreach}
</ul>
{else}
	{include file="theme:default/block_stub.tpl"  class="blockCategory" do=[
		[
			'title' => t("Добавьте категории товаров"),
			'href' => {adminUrl do=false mod_controller="catalog-ctrl"}
		]
	]}
{/if}