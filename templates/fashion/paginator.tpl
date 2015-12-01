{if $paginator->total_pages>1}
    <div class="paginator">
        <div class="top">
                {if $paginator->page>1}
                    <a href="{$paginator->getPageHref($paginator->page-1)}" class="prev" title="предыдущая страница">&laquo;<span class="text"> назад</span></a>
                {/if}
                {if $paginator->page < $paginator->total_pages}
                    <a href="{$paginator->getPageHref($paginator->page+1)}" class="next" title="следующая страница"><span class="text">вперед</span> &raquo;</a>
                {/if}
        </div>
        <div class="pages">
        {foreach from=$paginator->getPageList() item=page}            
            <a href="{$page.href}" {if $page.act}class="act"{/if}>{if $page.class=='left'}&laquo;{$page.n}{elseif $page.class=='right'}{$page.n}&raquo;{else}{$page.n}{/if}</a>
        {/foreach}
        </div>
    </div>
{/if}