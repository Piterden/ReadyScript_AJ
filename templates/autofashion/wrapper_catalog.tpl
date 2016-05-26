{extends file="%THEME%/wrapper.tpl"} {block name="content"}
<div class="container">
    <div class="row productListTitle">
        <div class="col-md-24 text-center mainTitle">
            {if !empty($query)} {*Если поисковый запрос*}
            <h1><span class="whiteBack">Результаты поиска {$query}</span></h1> {else}
            <h1><span class="whiteBack">{$category.name}</span></h1> {/if}
        </div>
        {if $sub_dirs|@count} {*Если есть подкатегории*}
        <div class="row subCategories topCategory">
            <div class="col-xs-24">
                <ul class="subCats">
                    {foreach $sub_dirs as $item name=sub_dir}
                    <li class="subCategory {$item.alias}">
                        <a href="{urlmake category=$item._alias p=null f=null bfilter=null}">
                            <span class="categoryTitle h4">{$item.name}</span>
                        </a>
                    </li>
                    {/foreach}
                </ul>
            </div>
        </div>
        {/if}
    </div>
    <div class="row">
        {if count($list)}
        <div class="col-md-6">
            <div id="filtersBlock">
                {moduleinsert name="\Catalog\Controller\Block\SideFilters"}
            </div>
        </div>
        <div class="col-md-18">
            {$app->blocks->getMainContent()}
        </div>
        {else}
        <div class="col-md-24 text-center h4">
            {$app->blocks->getMainContent()}
        </div>
        {/if}
    </div>
</div>
{/block}
