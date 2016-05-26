{assign var=bc value=$app->breadcrumbs->getBreadCrumbs()}
{if !empty($bc)}
<div class="breadcrumb-wrap">
    <ul class="breadcrumb">
	    {if strpos($smarty.session.REQUEST_URI, 'product') != false}
			{$bs[]=[ 'title' => '1' ]}
			{$bc|@print_r}
		{/if}
        {foreach $bc as $item} {if empty($item.href)}
        <li {if $item@first}class="first" {/if}>
            <span>{$item.title}</span>
        </li>
        {else}
        <li {if $item@first}class="first" {/if}>
            {if !$item@last}<a href="{$item.href}">{$item.title}</a>{else}{$item.title}{/if}
        </li>
        {/if} {/foreach}
        {* <li id="lastBreadcrumb">
            <span>{$title}</span>
        </li> *}
    </ul>
</div>
{/if}
{* {literal}<script>jQuery(document).ready(function($) {$('#lastBreadcrumb').text($('h1').text());});</script>{/literal} *}
