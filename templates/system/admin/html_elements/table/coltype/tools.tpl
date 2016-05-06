<div class="table-tools">
    {foreach from=$params item=param}
    {if isset($param.imgclass)}
    <a href="{$param.href}" {if !empty($param.onclick)}onclick="{$param.onclick}"{/if} class="{$param.imgclass}" title="{$param.title}"></a>
    {else}
    <a href="{$param.href}" {if !empty($param.onclick)}onclick="{$param.onclick}"{/if}><img border="0" src="{$param.img}" title="{$param.title}">{$param.text}</a>
    {/if}
    {/foreach}
</div>