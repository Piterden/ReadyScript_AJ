{addcss file="{$mod_css}property.css" basepath="root"}
{addjs file="{$mod_js}property.js" basepath="root"}

<div data-name="tab2" id="propertyblock" data-owner-type="product" data-get-property-url="{adminUrl mod_controller="catalog-propctrl" do="ajaxGetPropertyList"}" data-save-property-url="{adminUrl mod_controller="catalog-propctrl" do="ajaxCreateOrUpdateProperty"}">
    <div class="property-tools">
        <div class="property-actions">
            <a class="add-property underline"><span>Добавить характеристику</span></a>
            <span class="success-text">Характеристика успешно добавлена</span>
        </div>
        {include file="property_form.tpl"}
    </div>
    <div class="floatwrap">
        <a class="set-self-val underline"><span>Задать индивидуальные значения всем характеристикам</span></a>
    </div>
    
    <table class="property-container">
        {foreach from=$elem->getPropObjects() item=group key=key}
            {include file="property_group_product.tpl" group=$group}
        {/foreach}
    </table>    
</div>