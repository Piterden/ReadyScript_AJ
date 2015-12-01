{assign var=bc value=$app->breadcrumbs->getBreadCrumbs()}
{if !empty($bc)}
<div class="breadcrumbs">
    {foreach $bc as $item}
        {if empty($item.href)}
            <span {if $item@first}class="first"{/if}>{$item.title}</span>
        {else}
            <a href="{$item.href}" {if $item@first}class="first"{/if}>{$item.title}</a>
        {/if}
    {/foreach}
</div>
{/if}