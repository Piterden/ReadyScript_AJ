{if $paginator->total_pages>1}
        <div class="paginator">
            {if $paginator->showFirst()}
            <a href="{$paginator->getPageHref(1)}" data-page="1" title="первая страница">&laquo;</a>
            {/if}
            {if $paginator->page>1}
            <a href="{$paginator->getPageHref($paginator->page-1)}" data-page="{$paginator->page-1}" title="предыдущая страница">&laquo;<span class="text"> назад</span></a>
            {/if}
            {foreach from=$paginator->getPageList() item=page}            
            <a href="{$page.href}" data-page="{$page.n}" {if $page.act}class="act"{/if}>{if $page.class=='left'}&laquo;{$page.n}{elseif $page.class=='right'}{$page.n}&raquo;{else}{$page.n}{/if}</a>
            {/foreach}
            {if $paginator->page < $paginator->total_pages}
            <a href="{$paginator->getPageHref($paginator->page+1)}" data-page="{$paginator->page+1}" title="следующая страница"><span class="text">вперед</span> &raquo;</a>
            {/if}
            {if $paginator->showLast()}
            <a href="{$paginator->getPageHref($paginator->total_pages)}" data-page="{$paginator->total_pages}" title="последняя страница">&raquo;</a>
            {/if}
        </div>
{/if}