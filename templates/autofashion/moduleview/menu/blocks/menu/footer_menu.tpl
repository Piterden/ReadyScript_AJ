{if $items}
	{assign var="id" value=$items.0.fields.parent}
	{static_call var=menulist callback=['\Menu\Model\Api', 'staticSelectList']}
	<div class="title h4">{$menulist.$id|replace:'&nbsp;':''}</div>
	<div class="list h4">
		<ul>
		{foreach from=$items item=item}
			<li>
			{if $item.fields.typelink!='separator'}<a href="{$item.fields->getHref()}" {if $item.fields.target_blank}target="_blank"{/if}>{$item.fields.title}</a>{else}&nbsp;{/if}
			</li>
		{/foreach}
		</ul>
	</div>
{else}
	{include file="theme:default/block_stub.tpl" class="noBack blockSmall blockLeft blockLogo" do=[
		{$this_controller->getSettingUrl()}    => t("Настройте блок")
	]}
{/if}