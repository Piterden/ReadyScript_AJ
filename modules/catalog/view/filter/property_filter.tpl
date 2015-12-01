<div class="property-filter-toogle">
    <a class="toggle{if $fitem->isActiveFilter()} open{/if}">{t}искать по характеристикам{/t}</a>
</div>
<div class="property-filter-forms" {if !$fitem->isActiveFilter()}style="display:none"{/if}>
    {$cat_properties=$fitem->getProperties()}
    {if $cat_properties}
    <table class="property-filter-table">
    {foreach from=$cat_properties item=item}
        <tr>
            <td class="prop_name">{$item.title}</td>
            <td>
                {include file="%catalog%/filter/view_filter.tpl" prop=$item}
            </td>
        </tr>
    {/foreach}
    </table>    
    {else}
        <div class="no-category-properties">У выбранной категории нет характеристик</div>
    {/if}
</div>

<script>
$(function() {
    $('.property-filter-toogle .toggle').click(function() {
        var is_open = $(this).hasClass('open');
        $(this).toggleClass('open');
        $('.property-filter-forms').toggle(!is_open);
    });
});
</script>