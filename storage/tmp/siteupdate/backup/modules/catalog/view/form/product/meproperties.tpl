{addcss file="{$mod_css}selectproduct.css" basepath="root"}
{addcss file="{$mod_css}property.css" basepath="root"}
{addjs file="{$mod_js}meproperty.js" basepath="root"}

<table class="otable">                                              
    <tr class="editrow">
        <td class="ochk" width="20">
            <input title="{t}Отметьте, чтобы применить изменения по этому полю{/t}" type="checkbox" class="doedit" name="doedit[]" value="{$elem.___property_->getName()}" {if in_array($elem.___property_->getName(), $param.doedit)}checked{/if}></td>
        <td class="otitle"></td>
        <td>
            <div class="multi_edit_rightcol coveron">
                <div class="cover"></div>
                <div data-name="tab2" id="multipropertyblock" data-owner-type="product" data-get-property-url="{adminUrl mod_controller="catalog-propctrl" do="ajaxGetPropertyList"}">
                    <a class="add-proprow select-button"><span>Добавить характеристику</span></a>
                    {include file="meproperty_form.tpl"}
                </div>
            </div>        
        </td>
    </tr>
</table>