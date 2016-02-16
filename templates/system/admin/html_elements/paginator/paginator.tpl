<span class="text">страница</span>
<a href="{$paginator->left}" class="prev call-update" title="предыдущая страница"></a>
<input type="text" class="page" name="{$paginator->page_key}" value="{$paginator->page}" onfocus="$(this).select()">
<a href="{$paginator->right}" class="next call-update" title="следующая страница"></a>
<span class="text">из {$paginator->page_count}</span>
<span class="text perpage_block">показывать по </span>
<input type="text" class="perpage" name="{$paginator->pagesize_key}" value="{$paginator->page_size}" onfocus="$(this).select()">
<button type="submit" class="ok">OK</button>
{if empty($local_options.short)}
<span class="total">всего записей: <span class="total_value">{$paginator->total}</span></span>
{/if}