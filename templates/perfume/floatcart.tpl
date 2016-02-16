{$wrapped_content}
<div class="fixedCart">
    <div class="container_12">
        <a href="#" class="up" id="up" title="наверх"></a>
        {if ModuleManager::staticModuleExists('shop')}
        {moduleinsert name="\Shop\Controller\Block\Cart"}
        {/if}
        {moduleinsert name="\Catalog\Controller\Block\Compare" indexTemplate="blocks/compare/cart_compare.tpl"}
    </div>
</div>    