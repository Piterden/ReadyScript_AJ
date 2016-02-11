{if $category && $news}
<div class="row">
    <div class="col-sm-24 col-md-16 big newBlk">
        <div class="newWrap">
            {assign var='item' value=$news[0]}
            <div class="dateBlock">
                <div class="date">
                    <div class="day h2">
                        {$item.dateof|dateformat:"%d"}
                    </div>
                    <div class="month">
                        {$item.dateof|dateformat:"%v"}
                    </div>
                </div>
            </div>
            <div class="titleBlock">
                <div class="title">
                    <a href="{$item->getUrl()}" class="h3">{$item.title}</a></div>
                <div class="intro">
                    {* {$item->getPreview(180)} *}
                    <p>{$item.content|strip_tags|teaser:210}</p>
                </div>
            </div>
            <div class="imageBlock">
                <div class="imageWrap">
                    <img src="{$item.__image->getUrl(393, 227, 'cxy')}" alt="{$item.title}">
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-8 small newBlk">
        <div class="newWrap">
            {if $news[1]}
            {assign var='item' value=$news[1]}
            {/if}
            <div class="dateBlock">
                <div class="date">
                    <div class="day h2">
                        {$item.dateof|dateformat:"%d"}
                    </div>
                    <div class="month">
                        {$item.dateof|dateformat:"%v"}
                    </div>
                </div>
            </div>
            <div class="titleBlock">
                <div class="title">
                    <a href="{$item->getUrl()}" class="h3">{$item.title}</a></div>
                <div class="intro">
                    <p>{$item.content|strip_tags|teaser:210}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-8 small newBlk">
        <div class="newWrap">
            {if $news[2]}
            {assign var='item' value=$news[2]}
            {/if}
            <div class="dateBlock">
                <div class="date">
                    <div class="day h2">
                        {$item.dateof|dateformat:"%d"}
                    </div>
                    <div class="month">
                        {$item.dateof|dateformat:"%v"}
                    </div>
                </div>
            </div>
            <div class="titleBlock">
                <div class="title">
                    <a href="{$item->getUrl()}" class="h3">{$item.title}</a></div>
                <div class="intro">
                    <p>{$item.content|strip_tags|teaser:210}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-24 col-md-16 big newBlk">
        <div class="newWrap">
            {if $news[3]}
            {assign var='item' value=$news[3]}
            {/if}
            <div class="dateBlock">
                <div class="date">
                    <div class="day h2">
                        {$item.dateof|dateformat:"%d"}
                    </div>
                    <div class="month">
                        {$item.dateof|dateformat:"%v"}
                    </div>
                </div>
            </div>
            <div class="titleBlock">
                <div class="title">
                    <a href="{$item->getUrl()}" class="h3">{$item.title}</a></div>
                <div class="intro">
                    <p>{$item.content|strip_tags|teaser:210}</p>
                </div>
            </div>
            <div class="imageBlock">
                <div class="imageWrap">
                    <img src="{$item.__image->getUrl(394, 228, 'cxy')}" alt="{$item.title}">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row more">
    <div class="titleWrap col-sm-24 text-center">
        <a href="{$router->getUrl('article-front-previewlist', [category => $category->getUrlId()])}" class="onemore">Все новости</a>
        <div class="moreWrap">
        </div>
    </div>
</div>

{else}
    {include file="theme:default/block_stub.tpl"  class="blockLastNews" do=[
        [
            'title' => t("Добавьте категорию с новостями"),
            'href' => {adminUrl do=false mod_controller="article-ctrl"}
        ],
        [
            'title' => t("Настройте блок"),
            'href' => {$this_controller->getSettingUrl()},
            'class' => 'crud-add'
        ]
    ]}
{/if}