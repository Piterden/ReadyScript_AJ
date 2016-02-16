{if $paginator->total_pages>1}
<div class="paginator">
    {if $paginator->page>1}
    <a href="{$paginator->getPageHref($paginator->page-1)}" class="prev" title="предыдущая страница">&nbsp;</a>
    {/if}
    {foreach $paginator->getPageList() as $page}            
    <a href="{$page.href}" {if $page.act}class="act"{/if}>{if $page.class=='left'}&laquo;{$page.n}{elseif $page.class=='right'}{$page.n}&raquo;{else}{$page.n}{/if}</a>
    {/foreach}
    {if $paginator->page < $paginator->total_pages}
    <a href="{$paginator->getPageHref($paginator->page+1)}" class="next" title="следующая страница">&nbsp;</a>
    {/if}
</div>
{/if}