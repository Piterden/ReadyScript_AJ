{static_call var=all_props callback=['\Catalog\Model\PropertyApi', 'staticSelectListByType'] params=['list']}
{$multioffer_help_url='http://readyscript.ru/manual/catalog_products.html#catalog_multioffers'}

{if !empty($all_props)}
    <div id="multioffer-wrap">
        <div class="multioffer-warning">
            &quot;Многомерные комплектации&quot; недоступны, т.к. у товара не добавлены или не отмеченны списковые характеристики. <a href="{$multioffer_help_url}" target="_blank" class="how-to">Подробнее...</a>
        </div>
        <div id="multi-check-wrap">
            <input type="checkbox" id="use-multioffer" name="multioffers[use]" value="1" {if $elem->isMultiOffersUse()}checked{/if}> 
            <label for="use-multioffer">{t}Использовать многомерные комплектации{/t}. <span><a href="{$multioffer_help_url}" target="_blank" class="how-to">Как использовать?</a></span></label>
        </div>


        <div class="multioffer-wrap">
            <div class="item">
                <div class="no_photo_row">
                    <label><input type="radio" name="multioffers[is_photo]" value="0" checked="checked"/> <span>без фото</span></label>
                </div>
                <table>
                    <tbody class="offers-body">
                        {if $elem->isMultiOffersUse()}
                            {$m=0}
                            {foreach $elem.multioffers.levels as $k=>$level}
                                <tr class="row">
                                    <td class="is_photo">
                                        <label><input type="radio" name="multioffers[is_photo]" value="{$m+1}" {if $level.is_photo}checked="checked"{/if}/> <span>фото</span></label>
                                    </td>
                                    <td class="key">
                                       <div class="title">
                                          {t}Название параметра комплектации{/t}:
                                       </div> 
                                       <input type="text" name="multioffers[levels][{$m}][title]" maxlength="255" value="{$level.title}"/> 
                                    </td>
                                    <td class="value">
                                       <div class="title">
                                          {t}Списковые характеристики{/t}:
                                       </div>  
                                        {html_options name="multioffers[levels][{$m}][prop]" options=$all_props selected=$level.prop_id}
                                    </td>
                                    <td class="delete-level-td">
                                        <a href="#" class="delete-level">удалить</a>
                                    </td>
                                </tr>
                                {$m = $m+1}
                            {/foreach}
                        {/if}
                    </tbody>
                </table>
            </div>
            <div class="add-wrap">
                <div class="keyval-container" data-id=".multioffer-wrap .row">
                    <a class="add-level" href="javascript:;">добавить параметр</a>
                </div>
                <div>
                   <input type="checkbox" id="create-auto-offers" name="offers[create_autooffers]" value="1" > 
                   <label for="create-auto-offers">{t}Создавать комплектации{/t} <a class="help-icon"
                    title="{t}Установите данный флаг, если есть необходимость изменения цены<br/> или количества товара для разных комплектаций{/t}">?</a></label> 
                </div>
                <div class="bottom-bar">
                    <input class="mbutton create-complexs" type="button" name="" value="{t}cоздать{/t}"/>
                </div>
            </div>
        </div>

        {literal}
        <script type="text/x-tmpl" id="multioffer-line">
            <tr class="row">
                <td class="is_photo">
                    <label><input type="radio" name="multioffers[is_photo]" value="0"/> <span>фото</span></label>
                </td>
                <td class="key">
                   <div class="title">
                   {/literal}
                      {t}Название параметра комплектации{/t}:
                   {literal}
                   </div> 
                   <input type="text" name="multioffers[levels][0][title]" maxlength="255"/> 
                </td>
                <td class="value">
                   <div class="title">
                      {/literal}  
                      {t}Списковые характеристики{/t}:
                      {literal}
                   </div>  
                   {/literal}
                  
                    {html_options name="multioffers[levels][0][prop]" options=$all_props data-prop-id="0"}
                   
                   {literal}
                </td>
                <td class="delete-level-td">
                    <a href="#" class="delete-level">удалить</a>
                </td>
            </tr>
        </script>
        {/literal}
    </div>
{/if}