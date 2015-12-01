{if $brands}
<div class="brandList">
    {foreach from=$brands item=brand}
        <div class="oneBrand" {$brand->getDebugAttributes()}>
            <a href="{$brand->getUrl()}" alt="{$brand.title}"><img src="{$brand.__image->getUrl(195,100, 'axy')}" alt="{$brand.title}"/><br><span>{$brand.title}</span></a>
        </div>
    {/foreach}
</div>
{else}
    <p class="empty">Нет ни одного бренда</p>
{/if}