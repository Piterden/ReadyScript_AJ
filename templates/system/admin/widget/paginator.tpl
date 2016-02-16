{if $paginator->total_pages>1}
    <div class="widget-paginator">
        <div class="putright">
            {foreach from=$paginator->getPages() item=item}
            {if $item.class == 'page'}
                <a data-update-url="{$item.href}" class="call-update{if $item.act} act{/if}"><span>{$item.n}</span></a>
            {else}
                {if $item.class == 'right'}
                    <a data-update-url="{$item.href}" class="call-update{if $item.act} act{/if}"><span>{$item.n}&raquo;</span></a>
                {else}
                    <a data-update-url="{$item.href}" class="call-update{if $item.act} act{/if}"><span>&laquo;{$item.n}</span></a>
                {/if}
            {/if}
            {/foreach}
        </div>
    </div>
{/if}