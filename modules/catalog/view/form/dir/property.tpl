{addcss file="{$mod_css}property.css" basepath="root"}
{addjs file="{$mod_js}property.js" basepath="root"}
<div id="propertyblock" data-owner-type="group" data-get-property-url="{adminUrl mod_controller="catalog-propctrl" do="ajaxGetPropertyList"}" data-save-property-url="{adminUrl mod_controller="catalog-propctrl" do="ajaxCreateOrUpdateProperty"}" data-get-some-properties="{adminUrl mod_controller="catalog-propctrl" do="AjaxGetSomeProperties"}">
    <div class="property-tools">
        <div class="property-actions">
            <a class="add-property underline"><span>{t}Добавить характеристику{/t}</span></a>
            <a class="add-some-property underline"><span>{t}Вставить несколько характеристик{/t}</span></a>
            <span class="success-text">{t}Характеристика успешно добавлена{/t}</span>
        </div>
        {include file="property_form.tpl"}
    </div>
    
    <table class="property-container">
        <tbody class="overable">
        {foreach from=$elem->getPropObjects() item=group key=key}
            {include file="property_group_product.tpl" group=$group owner_type="group"}
        {/foreach}
        </tbody>
    </table>
</div>      