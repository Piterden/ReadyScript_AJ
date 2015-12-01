<div class="block blockCompare compareBlock{if !count($list)} hidden{/if}">
    <p class="caption">Товары для  сравнения</p>
    <ul class="compareProducts">
        {$list_html}        
    </ul>
    <a href="{$router->getUrl('catalog-front-compare')}" class="colorButton doCompare" target="_blank">Сравнить</a>
</div>