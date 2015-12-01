<div class="virtual-form" data-has-validation="true" data-action="/cp/catalog-block-offerblock/?odo=offerEdit">
    <div class="crud-form-error"></div>
    <input type="hidden" name="offer_id" value="{$elem.id}">
    <input type="hidden" name="product_id" value="{$elem.product_id}">
    <table class="table-inline-edit">
                                                    
                <tr>
                    <td class="key">{$elem.__title->getTitle()}</td>
                    <td>{include file=$elem.__title->getRenderTemplate() field=$elem.__title}</td>
                </tr>
                                                            
                <tr>
                    <td class="key">{$elem.__barcode->getTitle()}</td>
                    <td>{include file=$elem.__barcode->getRenderTemplate() field=$elem.__barcode}</td>
                </tr>
                                                            
                <tr>
                    <td class="key">{$elem.__pricedata_arr->getTitle()}</td>
                    <td>{include file=$elem.__pricedata_arr->getRenderTemplate() field=$elem.__pricedata_arr}</td>
                </tr>
                                                            
                <tr>
                    <td class="key">{$elem.___propsdata->getTitle()}</td>
                    <td>{include file=$elem.___propsdata->getRenderTemplate() field=$elem.___propsdata}</td>
                </tr>
                                                            
                <tr>
                    <td class="key">{$elem.__stock_num->getTitle()}</td>
                    <td>{include file=$elem.__stock_num->getRenderTemplate() field=$elem.__stock_num}</td>
                </tr>
                                                            
                <tr>
                    <td class="key">{$elem.__photos_arr->getTitle()}</td>
                    <td>{include file=$elem.__photos_arr->getRenderTemplate() field=$elem.__photos_arr}</td>
                </tr>
                                                <tr>
                <td class="key"></td>
                <td><a class="button save virtual-submit">Сохранить</a>
                <a class="button cancel">Отмена</a>
                </td>
            </tr>
    </table>
</div>