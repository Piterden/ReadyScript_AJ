{static_call var=all_props callback=['\Catalog\Model\PropertyApi', 'staticSelectListByType'] params=['list']}
{$multioffer_help_url='http://readyscript.ru/manual/catalog_products.html#catalog_multioffers'}

{if !empty($all_props)}
    <div>
        <div>
            <input id="deleteMe-offers" type="checkbox" name="_offers_[delete]" value="1"/> 
            <label for="deleteMe-offers">{t}Удалить имеющиеся комплектации{/t}</label> 
        </div>    
        <div id="multi-check-wrapME">
            <input type="hidden" name="_offers_[use]" value="0"> 
            <input type="checkbox" id="use-multiofferME" name="_offers_[use]" value="1"> 
            <label for="use-multiofferME">{t}Использовать многомерные комплектации{/t}. <span>
            <a href="{$multioffer_help_url}" target="_blank" class="how-to">{t}Как использовать?{/t}</a></span></label>
        </div>

        <div class="multioffer-wrapME">
            <div class="item">
                <div class="no_photo_row">
                    <input type="radio" name="_offers_[is_photo]" value="0" checked="checked"/> <span>без фото</span>
                </div>
                <table>
                    <tbody class="offers-bodyME">
                        
                    </tbody>
                </table>
            </div>
            <div class="add-wrap">
                <div class="keyval-container" data-id=".multioffer-wrapME .rowME">
                    <a class="add-levelME" href="javascript:;">добавить параметр</a>
                </div>
                <div>
                   <input type="checkbox" id="create-auto-offersME" name="_offers_[create_autooffers]" value="1" > 
                   <label for="create-auto-offersME">{t}Создавать комплектации{/t} <a class="help-icon"
                    title="{t}Установите данный флаг, если есть необходимость изменения цены<br/> или количества товара для разных комплектаций{/t}">?</a></label> 
                </div>
                <div class="bottom-bar">
                    <input class="mbutton create-complexs" type="button" name="" value="cоздать"/>
                </div>
            </div>
        </div>

        {literal}
        <script type="text/x-tmpl" id="multioffer-lineme">
            <tr class="rowME">
                <td class="is_photo">
                    <label><input type="radio" name="_offers_[is_photo]" value="1"/> <span>фото</span></label>
                </td>
                <td class="key">
                   <div class="title">
                   {/literal}
                      {t}Название параметра комплектации{/t}:
                   {literal}
                   </div> 
                   <input type="text" name="_offers_[levels][0][title]" maxlength="255"/> 
                </td>
                <td class="value">
                   <div class="title">
                      {/literal}  
                      {t}Списковые характеристики{/t}:
                      {literal}
                   </div>  
                   {/literal}
                  
                    {html_options name="_offers_[levels][0][prop]" options=$all_props data-prop-id="0"}
                   
                   {literal}
                </td>
                <td class="delete-level-td">
                    <a href="#" class="delete-levelME">удалить</a>
                </td>
            </tr>
        </script>
        {/literal}
    </div>
{/if}