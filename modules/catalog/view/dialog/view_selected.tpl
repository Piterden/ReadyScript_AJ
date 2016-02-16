{addcss file="%catalog%/selectproduct.css" basepath="root"}
{addjs file="%catalog%/selectproduct.js" basepath="root"}

<div class="product-group-container{if $hide_group_checkbox} hide-group-cb{/if}{if $hide_product_checkbox} hide-product-cb{/if}" data-urls='{ "getChild": "{adminUrl mod_controller="catalog-dialog" do="getChildCategory"}", "getProducts": "{adminUrl mod_controller="catalog-dialog" do="getProducts"}", "getDialog": "{adminUrl mod_controller="catalog-dialog" do=false}" }'>
    <a href="JavaScript:;" class="select-button"><span>Выбрать товары</span></a><br>
        <div class="selected-container">
            <ul class="group-block">
                {foreach from=$productArr.group item=item}
                <li class="group" val="{$item}"><a class="remove" title="{t}удалить из списка{/t}">&#215;</a><span class="group_icon" title="{t}категория товаров{/t}"></span>{$extdata.group.$item.obj.name}</li>
                {/foreach}
            </ul>
            <ul class="product-block">
                {foreach from=$productArr.product item=item}
                    <li class="product" val="{$item}">
                        <a class="remove" title="{t}удалить из списка{/t}">&#215;</a><span class="product_icon" title="{t}товар{/t}"></span>
                        {$extdata.product.$item.obj.title}
                    </li>
                {/foreach}
            </ul>
        </div>

        <div class="input-container" data-field-name="{$fieldName}">
            {foreach from=$productArr.group item=item}
            <input type="hidden" catids="{$extdata.group.$item.parents}" value="{$item}" name="{$fieldName}[group][]">
            {/foreach}
            {foreach from=$productArr.product item=item}
            <input type="hidden" catids="{$extdata.product.$item.dirs}" value="{$item}" name="{$fieldName}[product][]">
            {/foreach}
        </div>
</div>        

<script>
    $.allReady(function() {
        $('.product-group-container').selectProduct();
    });
</script>