<div class="container newsPage">
    <div class="row text-center mainTitle">
        <h1><span class="whiteBack">Новости</span></h1>
    </div>
    <div class="col-md-16 col-md-offset-4 content">
        <ul class="news">
            {foreach $list as $item}
            <li class="item {if $item@iteration % 2==0}fr{else}fl{/if}" {$item->getDebugAttributes()}>
                <!-- <p class="date">{$item.dateof|date_format:"d.m.Y H:i"}</p> -->
                <p class="date">{$item.dateof|date_format:"d.m.Y"}</p>
                <a href="{$item->getUrl()}" class="title">{$item.title}</a>
                <p class="shortinfo">{$item.content|strip_tags|teaser:300}</p>
            </li>
            {/foreach}
        </ul>
        {include file="%THEME%/paginator.tpl"}
    </div>
</div>