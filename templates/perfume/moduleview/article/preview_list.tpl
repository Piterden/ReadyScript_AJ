<div class="newsBlock">
    <h3>Новости</h3>
    <ul class="news">
        {foreach $list as $item}
            <li class="item {if $item@iteration % 2==0}fr{else}fl{/if}" {$item->getDebugAttributes()}>
                <p class="date">{$item.dateof|date_format:"d.m.Y H:i"}</p>
                <a href="{$item->getUrl()}" class="title">{$item.title}</a>
                <p class="descr">{$item->getPreview()}</p>
            </li>                  
        {/foreach}
    </ul>
    {include file="%THEME%/paginator.tpl"}          
</div>