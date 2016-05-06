{addjs file="{$mod_js}jquery.compare.js" basepath="root"}
<div class="sideBlock{if !count($list)} hidden{/if}" id="compareBlock" data-compare-url='{ "add":"{$router->getUrl('catalog-block-compare', ["cpmdo" => "ajaxAdd", "_block_id" => $_block_id])}", "remove":"{$router->getUrl('catalog-block-compare', ["cpmdo" => "ajaxRemove", "_block_id" => $_block_id])}" }'>
    <h2><span>Товары для<br>сравнения</span></h2>
    <div class="wrapWidth">
        <ul class="compareProducts">
            {$list_html}                     
        </ul>
    </div>
    <a href="{$router->getUrl('catalog-front-compare')}" class="doCompare" target="_blank"><span>сравнить</span></a>
</div>