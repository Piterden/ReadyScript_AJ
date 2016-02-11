{if $items}
	<ul class="topMenu">
	    {foreach from=$items item=item}
			<li class="{if !empty($item.child)}node dropblock{/if}{if $item.fields.typelink=='separator'} separator{/if}{if $item.fields->isAct()} act{/if}" {if $item.fields.typelink != 'separator'}{$item.fields->getDebugAttributes()}{/if}>
			    {if $item.fields.typelink!='separator'}
			        <a href="{$item.fields->getHref()}" {if $item.fields.target_blank}target="_blank"{/if}>{$item.fields.title}</a>
			    {else}
			        &nbsp;
			    {/if}
			    {if !empty($item.child)}
			    <ul class="dropdown">
			        {foreach from=$item.child item=child}
			        	<li class="{if !empty($child.child)}node{/if}{if $child.fields.typelink=='separator'} separator{/if}{if $child.fields->isAct()} act{/if}" {if $child.fields.typelink != 'separator'}{$child.fields->getDebugAttributes()}{/if}>
						    {if $child.fields.typelink!='separator'}
						        <a href="{$child.fields->getHref()}" {if $child.fields.target_blank}target="_blank"{/if}>{$child.fields.title}</a>
						    {else}
						        &nbsp;
						    {/if}
						</li>
			        {/foreach}
			    </ul>
			    {/if}
			</li>
		{/foreach}
	</ul>
{else}
    {include file="theme:autojack/block_stub.tpl"  class="noBack blockSmall blockLeft blockMenu" do=[
        {adminUrl do="add" mod_controller="menu-ctrl"} => t("Добавьте пункт меню")
    ]}
{/if}
