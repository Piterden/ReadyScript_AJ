<div class="prop-insert-div">
</div>

<div class="property-form clone-it" style="display:none">
    <span class="field-error" data-field="title"></span>
    <input type="hidden" class="p-siteid"/>
    <input type="hidden" class="p-type"/>
    <input type="hidden" class="p-public"/>
    <input type="hidden" class="p-xmlid"/>
    <table width="100%" class="property-table">
            <tr class="p-proplist-block">
                <td class="key">Выберите характеристику</td>
                <td>
                    <select class="p-proplist">
                       <option>Идет загрузка...</option>
                    </select>
                    <span class="ploading"><img src="{$Setup.IMG_PATH}/adminstyle/small-loader.gif">идет загрузка...</span>
                </td>
            </tr>                            
            <tr class="p-row-list-delit" style="display:none;">
                <td class="key">Удалить?</td>
                <td>
                    <span>
                        <input type="checkbox" class="p-check-list-delit" value="1"/>
                    </span><br>
                    <span class="fieldhelp">Удалить эту характеристику у товаров</span>
                </td>
            </tr>             
            <tr class="p-value-block" style="display:none;">
                <td class="key">Значение</td>
                <td>
                    <span class="p-val-block">
                        <input type="text" class="p-val">
                    </span>
                </td>
            </tr>  
            <tr class="p-row-list-values" style="display:none;">
                <td class="key">Список значений</td>
                <td>
                    <span class="p-list-check">
                        
                    </span><br>
                </td>
            </tr> 
    </table>
    <div class="oh">
        <a href="Javascript:;" class="close-property">Убрать строку</a>
    </div>
</div>

<div class="multi-prop-del-button">
    <input type="checkbox" value="1" name="_property_[0]"/ id="delAllProperty"> - <label for="delAllProperty">удалить все характеристики выбранных товаров?</label>
</div>