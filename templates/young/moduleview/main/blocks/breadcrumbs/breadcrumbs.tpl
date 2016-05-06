{$bc=$app->breadcrumbs->getBreadCrumbs()}
{if !empty($bc)}
<ul class="breadcrumbs">
    {foreach $bc as $item}
    <li {if $item@first}class="first"{/if}>
        {if empty($item.href)}
        <span>{$item.title}</span>
        {else}
        <a href="{$item.href}">{$item.title}</a>        
        {/if}
    </li>
    {/foreach}
</ul>
{/if}