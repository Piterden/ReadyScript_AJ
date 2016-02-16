<div class="formbox" >
    {if $elem._before_form_template}{include file=$elem._before_form_template}{/if}

            
    <div class="tabs">
        <ul class="tab-container">
                    <li class=" act first"><a data-view="tab0">{$elem->getPropertyIterator()->getGroupName(0)}</a></li>
                    <li class=""><a data-view="tab1">{$elem->getPropertyIterator()->getGroupName(1)}</a></li>
                    <li class=""><a data-view="tab2">{$elem->getPropertyIterator()->getGroupName(2)}</a></li>
                    <li class=""><a data-view="tab3">{$elem->getPropertyIterator()->getGroupName(3)}</a></li>
                    <li class=""><a data-view="tab4">{$elem->getPropertyIterator()->getGroupName(4)}</a></li>
                </ul>
        <form method="POST" action="{urlmake}" enctype="multipart/form-data" class="crud-form">    
            <input type="submit" value="" style="display:none">
                        <div class="frame" data-name="tab0">
                                                    
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <table class="otable">
                                                        
                            <tr>
                                <td class="otitle">{$elem.__name->getTitle()}&nbsp;&nbsp;{if $elem.__name->getHint() != ''}<a class="help-icon" title="{$elem.__name->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__name->getRenderTemplate() field=$elem.__name}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__description->getTitle()}&nbsp;&nbsp;{if $elem.__description->getHint() != ''}<a class="help-icon" title="{$elem.__description->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__description->getRenderTemplate() field=$elem.__description}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__version->getTitle()}&nbsp;&nbsp;{if $elem.__version->getHint() != ''}<a class="help-icon" title="{$elem.__version->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__version->getRenderTemplate() field=$elem.__version}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__core_version->getTitle()}&nbsp;&nbsp;{if $elem.__core_version->getHint() != ''}<a class="help-icon" title="{$elem.__core_version->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__core_version->getRenderTemplate() field=$elem.__core_version}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__author->getTitle()}&nbsp;&nbsp;{if $elem.__author->getHint() != ''}<a class="help-icon" title="{$elem.__author->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__author->getRenderTemplate() field=$elem.__author}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__enabled->getTitle()}&nbsp;&nbsp;{if $elem.__enabled->getHint() != ''}<a class="help-icon" title="{$elem.__enabled->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__enabled->getRenderTemplate() field=$elem.__enabled}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__default_cost->getTitle()}&nbsp;&nbsp;{if $elem.__default_cost->getHint() != ''}<a class="help-icon" title="{$elem.__default_cost->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__default_cost->getRenderTemplate() field=$elem.__default_cost}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__old_cost->getTitle()}&nbsp;&nbsp;{if $elem.__old_cost->getHint() != ''}<a class="help-icon" title="{$elem.__old_cost->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__old_cost->getRenderTemplate() field=$elem.__old_cost}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__hide_unobtainable_goods->getTitle()}&nbsp;&nbsp;{if $elem.__hide_unobtainable_goods->getHint() != ''}<a class="help-icon" title="{$elem.__hide_unobtainable_goods->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__hide_unobtainable_goods->getRenderTemplate() field=$elem.__hide_unobtainable_goods}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__list_page_size->getTitle()}&nbsp;&nbsp;{if $elem.__list_page_size->getHint() != ''}<a class="help-icon" title="{$elem.__list_page_size->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__list_page_size->getRenderTemplate() field=$elem.__list_page_size}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__items_on_page->getTitle()}&nbsp;&nbsp;{if $elem.__items_on_page->getHint() != ''}<a class="help-icon" title="{$elem.__items_on_page->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__items_on_page->getRenderTemplate() field=$elem.__items_on_page}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__list_default_order->getTitle()}&nbsp;&nbsp;{if $elem.__list_default_order->getHint() != ''}<a class="help-icon" title="{$elem.__list_default_order->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__list_default_order->getRenderTemplate() field=$elem.__list_default_order}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__list_default_order_direction->getTitle()}&nbsp;&nbsp;{if $elem.__list_default_order_direction->getHint() != ''}<a class="help-icon" title="{$elem.__list_default_order_direction->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__list_default_order_direction->getRenderTemplate() field=$elem.__list_default_order_direction}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__list_default_view_as->getTitle()}&nbsp;&nbsp;{if $elem.__list_default_view_as->getHint() != ''}<a class="help-icon" title="{$elem.__list_default_view_as->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__list_default_view_as->getRenderTemplate() field=$elem.__list_default_view_as}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__default_weight->getTitle()}&nbsp;&nbsp;{if $elem.__default_weight->getHint() != ''}<a class="help-icon" title="{$elem.__default_weight->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__default_weight->getRenderTemplate() field=$elem.__default_weight}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__default_weight_unit->getTitle()}&nbsp;&nbsp;{if $elem.__default_weight_unit->getHint() != ''}<a class="help-icon" title="{$elem.__default_weight_unit->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__default_weight_unit->getRenderTemplate() field=$elem.__default_weight_unit}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__default_unit->getTitle()}&nbsp;&nbsp;{if $elem.__default_unit->getHint() != ''}<a class="help-icon" title="{$elem.__default_unit->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__default_unit->getRenderTemplate() field=$elem.__default_unit}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__concat_dir_meta->getTitle()}&nbsp;&nbsp;{if $elem.__concat_dir_meta->getHint() != ''}<a class="help-icon" title="{$elem.__concat_dir_meta->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__concat_dir_meta->getRenderTemplate() field=$elem.__concat_dir_meta}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__auto_barcode->getTitle()}&nbsp;&nbsp;{if $elem.__auto_barcode->getHint() != ''}<a class="help-icon" title="{$elem.__auto_barcode->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__auto_barcode->getRenderTemplate() field=$elem.__auto_barcode}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__disable_search_index->getTitle()}&nbsp;&nbsp;{if $elem.__disable_search_index->getHint() != ''}<a class="help-icon" title="{$elem.__disable_search_index->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__disable_search_index->getRenderTemplate() field=$elem.__disable_search_index}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__update_price_round->getTitle()}&nbsp;&nbsp;{if $elem.__update_price_round->getHint() != ''}<a class="help-icon" title="{$elem.__update_price_round->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__update_price_round->getRenderTemplate() field=$elem.__update_price_round}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__update_price_round_value->getTitle()}&nbsp;&nbsp;{if $elem.__update_price_round_value->getHint() != ''}<a class="help-icon" title="{$elem.__update_price_round_value->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__update_price_round_value->getRenderTemplate() field=$elem.__update_price_round_value}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__cbr_link->getTitle()}&nbsp;&nbsp;{if $elem.__cbr_link->getHint() != ''}<a class="help-icon" title="{$elem.__cbr_link->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__cbr_link->getRenderTemplate() field=$elem.__cbr_link}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__cbr_percent_update->getTitle()}&nbsp;&nbsp;{if $elem.__cbr_percent_update->getHint() != ''}<a class="help-icon" title="{$elem.__cbr_percent_update->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__cbr_percent_update->getRenderTemplate() field=$elem.__cbr_percent_update}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__use_offer_unit->getTitle()}&nbsp;&nbsp;{if $elem.__use_offer_unit->getHint() != ''}<a class="help-icon" title="{$elem.__use_offer_unit->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__use_offer_unit->getRenderTemplate() field=$elem.__use_offer_unit}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__import_photos_timeout->getTitle()}&nbsp;&nbsp;{if $elem.__import_photos_timeout->getHint() != ''}<a class="help-icon" title="{$elem.__import_photos_timeout->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__import_photos_timeout->getRenderTemplate() field=$elem.__import_photos_timeout}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__import_yml_timeout->getTitle()}&nbsp;&nbsp;{if $elem.__import_yml_timeout->getHint() != ''}<a class="help-icon" title="{$elem.__import_yml_timeout->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__import_yml_timeout->getRenderTemplate() field=$elem.__import_yml_timeout}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__show_all_products->getTitle()}&nbsp;&nbsp;{if $elem.__show_all_products->getHint() != ''}<a class="help-icon" title="{$elem.__show_all_products->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__show_all_products->getRenderTemplate() field=$elem.__show_all_products}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__price_like_slider->getTitle()}&nbsp;&nbsp;{if $elem.__price_like_slider->getHint() != ''}<a class="help-icon" title="{$elem.__price_like_slider->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__price_like_slider->getRenderTemplate() field=$elem.__price_like_slider}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__search_fields->getTitle()}&nbsp;&nbsp;{if $elem.__search_fields->getHint() != ''}<a class="help-icon" title="{$elem.__search_fields->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__search_fields->getRenderTemplate() field=$elem.__search_fields}</td>
                            </tr>
                            
                                                    </table>
                                                </div>
                        <div class="frame nodisp" data-name="tab1">
                                                    
                                                                        {include file=$elem.____clickfields__->getRenderTemplate() field=$elem.____clickfields__}
                                                                                                                                                                                                                </div>
                        <div class="frame nodisp" data-name="tab2">
                                                    
                                                                                                                                                                                                    <table class="otable">
                                                        
                            <tr>
                                <td class="otitle">{$elem.__csv_id_fields->getTitle()}&nbsp;&nbsp;{if $elem.__csv_id_fields->getHint() != ''}<a class="help-icon" title="{$elem.__csv_id_fields->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__csv_id_fields->getRenderTemplate() field=$elem.__csv_id_fields}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__csv_offer_product_search_field->getTitle()}&nbsp;&nbsp;{if $elem.__csv_offer_product_search_field->getHint() != ''}<a class="help-icon" title="{$elem.__csv_offer_product_search_field->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__csv_offer_product_search_field->getRenderTemplate() field=$elem.__csv_offer_product_search_field}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__csv_offer_search_field->getTitle()}&nbsp;&nbsp;{if $elem.__csv_offer_search_field->getHint() != ''}<a class="help-icon" title="{$elem.__csv_offer_search_field->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__csv_offer_search_field->getRenderTemplate() field=$elem.__csv_offer_search_field}</td>
                            </tr>
                            
                                                    </table>
                                                </div>
                        <div class="frame nodisp" data-name="tab3">
                                                    
                                                                                                                                                        <table class="otable">
                                                        
                            <tr>
                                <td class="otitle">{$elem.__brand_products_specdir->getTitle()}&nbsp;&nbsp;{if $elem.__brand_products_specdir->getHint() != ''}<a class="help-icon" title="{$elem.__brand_products_specdir->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__brand_products_specdir->getRenderTemplate() field=$elem.__brand_products_specdir}</td>
                            </tr>
                            
                                                        
                            <tr>
                                <td class="otitle">{$elem.__brand_products_cnt->getTitle()}&nbsp;&nbsp;{if $elem.__brand_products_cnt->getHint() != ''}<a class="help-icon" title="{$elem.__brand_products_cnt->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__brand_products_cnt->getRenderTemplate() field=$elem.__brand_products_cnt}</td>
                            </tr>
                            
                                                    </table>
                                                </div>
                        <div class="frame nodisp" data-name="tab4">
                                                    
                                                                                                            <table class="otable">
                                                        
                            <tr>
                                <td class="otitle">{$elem.__warehouse_sticks->getTitle()}&nbsp;&nbsp;{if $elem.__warehouse_sticks->getHint() != ''}<a class="help-icon" title="{$elem.__warehouse_sticks->getHint()|escape}">?</a>{/if}
                                </td>
                                <td>{include file=$elem.__warehouse_sticks->getRenderTemplate() field=$elem.__warehouse_sticks}</td>
                            </tr>
                            
                                                    </table>
                                                </div>
                    </form>
    </div>
    </div>