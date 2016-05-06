{* Список категорий из 2-х уровней*}
{if $dirlist}
    {addjs file="jquery.mainmenu.js" basepath="common"}
    <ul class="category">
        {foreach from=$dirlist item=dir}
        <li {if in_array($dir.fields.id, $pathids)}class="act"{/if} {$dir.fields->getDebugAttributes()}><a href="{$dir.fields->getUrl()}">{$dir.fields.name}</a>
            {if !empty($dir.child)}
            {assign var=cnt value=count($dir.child)}
            {if $cnt>9 && $cnt<21}
                {assign var=columns value="twoColumn"}
            {elseif $cnt>20}
                {assign var=columns value="threeColumn"}
            {/if}
            <ul {if $columns}class="{$columns}"{/if}>
                <li class="corner"></li>
                {foreach from=$dir.child item=item}
                <li {if in_array($item.fields.id, $pathids)}class="act"{/if} {$item.fields->getDebugAttributes()}><a href="{$item.fields->getUrl()}">{$item.fields.name}</a>
                {/foreach}
            </ul>
            {/if}
        </li>
        {/foreach}
    </ul>
{else}
    {include file="theme:default/block_stub.tpl"  class="blockCategory" do=[
        [
            'title' => t("Добавьте категории товаров"),
            'href' => {adminUrl do=false mod_controller="catalog-ctrl"}
        ]
    ]}
{/if}