<div class="virtual-form multiedit" data-has-validation="true" data-action="/manager/catalog-block-offerblock/?odo=offerMultiEdit">
    
    <div class="me_info">
        Выбрано элементов: <strong>{$param.sel_count}</strong>
    </div>    
    {foreach $param.hidden_fields as $key=>$val}
    <input type="hidden" name="{$key}" value="{$val}">
    {/foreach}
    
    <div class="crud-form-error"></div>    
    <input type="hidden" name="offer_id" value="{$elem.id}">
    <input type="hidden" name="product_id" value="{$elem.product_id}">
    <table class="table-inline-edit">
                                    
                <tr class="editrow">
                    <td class="ochk" width="20">
                        <input title="{t}Отметьте, чтобы применить изменения по этому полю{/t}" type="checkbox" class="doedit" name="doedit[]" value="{$elem.__title->getName()}" {if in_array($elem.__title->getName(), $param.doedit)}checked{/if}></td>
                    <td class="key">{$elem.__title->getTitle()}</td>
                    <td><div class="multi_edit_rightcol coveron"><div class="cover"></div>{include file=$elem.__title->getRenderTemplate(true) field=$elem.__title}</div></td>
                </tr>
                            
                <tr class="editrow">
                    <td class="ochk" width="20">
                        <input title="{t}Отметьте, чтобы применить изменения по этому полю{/t}" type="checkbox" class="doedit" name="doedit[]" value="{$elem.__barcode->getName()}" {if in_array($elem.__barcode->getName(), $param.doedit)}checked{/if}></td>
                    <td class="key">{$elem.__barcode->getTitle()}</td>
                    <td><div class="multi_edit_rightcol coveron"><div class="cover"></div>{include file=$elem.__barcode->getRenderTemplate(true) field=$elem.__barcode}</div></td>
                </tr>
                            
                <tr class="editrow">
                    <td class="ochk" width="20">
                        <input title="{t}Отметьте, чтобы применить изменения по этому полю{/t}" type="checkbox" class="doedit" name="doedit[]" value="{$elem.__pricedata_arr->getName()}" {if in_array($elem.__pricedata_arr->getName(), $param.doedit)}checked{/if}></td>
                    <td class="key">{$elem.__pricedata_arr->getTitle()}</td>
                    <td><div class="multi_edit_rightcol coveron"><div class="cover"></div>{include file=$elem.__pricedata_arr->getRenderTemplate(true) field=$elem.__pricedata_arr}</div></td>
                </tr>
                            
                <tr class="editrow">
                    <td class="ochk" width="20">
                        <input title="{t}Отметьте, чтобы применить изменения по этому полю{/t}" type="checkbox" class="doedit" name="doedit[]" value="{$elem.___propsdata->getName()}" {if in_array($elem.___propsdata->getName(), $param.doedit)}checked{/if}></td>
                    <td class="key">{$elem.___propsdata->getTitle()}</td>
                    <td><div class="multi_edit_rightcol coveron"><div class="cover"></div>{include file=$elem.___propsdata->getRenderTemplate(true) field=$elem.___propsdata}</div></td>
                </tr>
                            
                <tr class="editrow">
                    <td class="ochk" width="20">
                        <input title="{t}Отметьте, чтобы применить изменения по этому полю{/t}" type="checkbox" class="doedit" name="doedit[]" value="{$elem.__stock_num->getName()}" {if in_array($elem.__stock_num->getName(), $param.doedit)}checked{/if}></td>
                    <td class="key">{$elem.__stock_num->getTitle()}</td>
                    <td><div class="multi_edit_rightcol coveron"><div class="cover"></div>{include file=$elem.__stock_num->getRenderTemplate(true) field=$elem.__stock_num}</div></td>
                </tr>
                            
                <tr class="editrow">
                    <td class="ochk" width="20">
                        <input title="{t}Отметьте, чтобы применить изменения по этому полю{/t}" type="checkbox" class="doedit" name="doedit[]" value="{$elem.__photos_arr->getName()}" {if in_array($elem.__photos_arr->getName(), $param.doedit)}checked{/if}></td>
                    <td class="key">{$elem.__photos_arr->getTitle()}</td>
                    <td><div class="multi_edit_rightcol coveron"><div class="cover"></div>{include file=$elem.__photos_arr->getRenderTemplate(true) field=$elem.__photos_arr}</div></td>
                </tr>
                                <tr>
                <td></td>
                <td class="key"></td>
                <td><a class="button save virtual-submit">Сохранить</a>
                    <a class="button cancel">Отмена</a>
                </td>
            </tr>
    </table>
</div>