{addcss file="{$mod_css}watchnow.css" basepath="root"}

<div class="last-watch">
    {assign var=item value=$list.0}
    {if $total}
        {if $offset>0}<a data-update-url="{adminUrl mod_controller="catalog-widget-watchnow" offset=$offset-1}" class="prev call-update"></a>{/if}
        {if $offset+1 < $total}<a data-update-url="{adminUrl mod_controller="catalog-widget-watchnow" offset=$offset+1}" class="next call-update"></a>{/if}
        <div class="picture">
            <a href="{$item.editUrl}">
                <img src="{$item.product->getMainImage(160, 150, 'xy')}" class="p-photo">
            </a>
        </div>    
        <div class="description">
            <a href="{$item.editUrl}" class="title">{$item.product.title}</a><br>
            <span class="path"><a href="{$item.path.href}">{$item.path.line}</a></span><br>
            <span class="time">{$item.eventDate}</span><br>
            <a class="login" {if $item.user.href}href="{$item.user.href}"{/if}>{$item.user.name}</a>
        </div>
    {else}
        <div class="empty">
            {t}Ни один товар не был просмотрен{/t}
        </div>
    {/if}
</div>