<div class="left-menu">
    <div class="top"><span>КАТАЛОГ</span></div>
    <ul class="categories">
        {foreach from=$dirlist item=dir}
        <li {if in_array($dir.fields.id, $pathids)}class="act"{/if} {$dir.fields->getDebugAttributes()}><a href="{$dir.fields->getUrl()}">{$dir.fields.name}</a></li>
        {/foreach}
    </ul>
    <div class="bottom"></div>
</div>