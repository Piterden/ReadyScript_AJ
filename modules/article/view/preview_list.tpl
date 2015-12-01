<ul class="articles">
{foreach from=$list item=item}
    <li {$item->getDebugAttributes()}>
        <span class="date">{$item.dateof|date_format:"d.m.Y H:i"}</span><br>
        <a class="link" href="{$item->getUrl()}">
            <span class="title">{$item.title}</span><br>
            <span class="preview">{if !empty($item.image)}<img src="{$item.__image->getUrl(120,120,'xy')}" alt="{$item.title}" class="image"/>{/if} 
            {$item->getPreview()}</span>
        </a>
    </li>
{/foreach}
</ul>

{include file="%THEME%/paginator.tpl"}