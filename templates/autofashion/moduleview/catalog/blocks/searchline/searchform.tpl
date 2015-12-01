<form method="GET" action="{$router->getUrl('catalog-front-listproducts', [])}">
    <div class="searchLine">
        <div class="queryWrap" id="queryBox">
            <input type="text" class="query{if !$param.hideAutoComplete} autocomplete{/if}" data-deftext="поиск в каталоге" name="query" value="{$query}" autocomplete="off" data-source-url="{$router->getUrl('catalog-block-searchline', ['sldo' => 'ajaxSearchItems', _block_id => $_block_id])}" placeholder="Поиск в каталоге">
        </div>
        <button type="submit" class="find"><i class="fa fa-search"></i></button>
    </div>
</form>