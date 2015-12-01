<span class="text">страница</span>
<a data-href="{$paginator->left}" class="prev" title="предыдущая страница"></a>
<input type="text" class="page" name="{$paginator->page_key}" value="{$paginator->page}" onfocus="$(this).select()">
<a data-href="{$paginator->right}" class="next" title="следующая страница"></a>
<span class="text">из {$paginator->page_count}</span>
<span class="text perpage_block">{if $local_options.short}показывать{/if} по </span>
<input type="text" class="perpage" name="{$paginator->pagesize_key}" value="{$paginator->page_size}" onfocus="$(this).select()">
<button type="button" class="ok">OK</button>       
{if !$local_options.short}
&nbsp;&nbsp;<span class="total">всего: <span class="total_value">{$paginator->total}</span></span>
{/if}