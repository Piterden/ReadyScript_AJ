{if $paginator->total_pages>1}
    <div class="row">
        <div class="paginator col-md-12 col-md-offset-6 text-center">
            {if $paginator->showFirst()}
            <a href="{$paginator->getPageHref(1)}" title="первая страница"><i class="fa fa-angle-double-left"></i></a>
            {/if}
            {if $paginator->page>1}
            <a href="{$paginator->getPageHref($paginator->page-1)}" title="предыдущая страница"><i class="fa fa-angle-left"></i></a>
            {/if}
            {foreach from=$paginator->getPageList() item=page}            
            <a href="{$page.href}" {if $page.act}class="act"{/if}>{if $page.class=='left'}«{$page.n}{elseif $page.class=='right'}{$page.n}»{else}{$page.n}{/if}</a>
            {/foreach}
            {if $paginator->page < $paginator->total_pages}
            <a href="{$paginator->getPageHref($paginator->page+1)}" title="следующая страница"><i class="fa fa-angle-right"></i></a>
            {/if}
            {if $paginator->showLast()}
            <a href="{$paginator->getPageHref($paginator->total_pages)}" title="последняя страница"><i class="fa fa-angle-double-right"></i></a>
            {/if}
        </div>
    </div>
{/if}