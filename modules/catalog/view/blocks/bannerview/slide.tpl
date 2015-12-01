<div class="viewContainer">
    {if $item>0}<a class="prev" data-params='{ "dir":"{$dir}", "item":"{$item-1}"}'></a>{/if}
    {if $item<$total-1}<a class="next" data-params='{ "dir":"{$dir}", "item":"{$item+1}"}'></a>{/if}
    <div class="banner" {$product->getDebugAttributes()}>
        <a href="{$product->getUrl()}" class="picture"><img src="{$product->getMainImage(353, 272)}"></a>
        <div class="info">
            <div class="title">{$product.title}</div><br>
            <div class="fcost"><span>{$product->getCost()} {$product->getCurrency()}</span></div><br>
            <a href="{$product->getUrl()}" class="more">{t}подробнее{/t}</a>
        </div>
    </div>
</div>