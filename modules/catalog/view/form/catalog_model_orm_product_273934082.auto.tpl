<div class="formbox" data-dialog-options = '{ "width":900, "minHeight":500 }'>
            
    <div class="tabs">
        <ul class="tab-container">
                    <li class=" act first"><a data-view="tab0">{$elem->getPropertyIterator()->getGroupName(0)}</a></li>
                    <li class=""><a data-view="tab1">{$elem->getPropertyIterator()->getGroupName(1)}</a></li>
                    <li class=""><a data-view="tab2">{$elem->getPropertyIterator()->getGroupName(2)}</a></li>
                    <li class=""><a data-view="tab3">{$elem->getPropertyIterator()->getGroupName(3)}</a></li>
                    <li class=""><a data-view="tab4">{$elem->getPropertyIterator()->getGroupName(4)}</a></li>
                    <li class=""><a data-view="tab5">{$elem->getPropertyIterator()->getGroupName(5)}</a></li>
                    <li class=""><a data-view="tab6">{$elem->getPropertyIterator()->getGroupName(6)}</a></li>
                    <li class=""><a data-view="tab7">{$elem->getPropertyIterator()->getGroupName(7)}</a></li>
                    <li class=""><a data-view="tab8">{$elem->getPropertyIterator()->getGroupName(8)}</a></li>
                </ul>
        <form method="POST" action="{urlmake}" enctype="multipart/form-data" class="crud-form">    
            <input type="submit" value="" style="display:none"/>
                        <div class="frame" data-name="tab0">
                                                    
                                                                                                {include file=$elem.__id->getRenderTemplate() field=$elem.__id}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        {include file=$elem.___tmpid->getRenderTemplate() field=$elem.___tmpid}
                                                                                                                                                                                                                                <table class="otable">
                                                                                                                                                        
                                <tr>
                                    <td class="otitle">{$elem.__title->getTitle()}&nbsp;&nbsp;{if $elem.__title->getHint() != ''}<a class="help-icon" title="{$elem.__title->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__title->getRenderTemplate() field=$elem.__title}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__alias->getTitle()}&nbsp;&nbsp;{if $elem.__alias->getHint() != ''}<a class="help-icon" title="{$elem.__alias->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__alias->getRenderTemplate() field=$elem.__alias}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__short_description->getTitle()}&nbsp;&nbsp;{if $elem.__short_description->getHint() != ''}<a class="help-icon" title="{$elem.__short_description->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__short_description->getRenderTemplate() field=$elem.__short_description}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__description->getTitle()}&nbsp;&nbsp;{if $elem.__description->getHint() != ''}<a class="help-icon" title="{$elem.__description->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__description->getRenderTemplate() field=$elem.__description}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__barcode->getTitle()}&nbsp;&nbsp;{if $elem.__barcode->getHint() != ''}<a class="help-icon" title="{$elem.__barcode->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__barcode->getRenderTemplate() field=$elem.__barcode}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__weight->getTitle()}&nbsp;&nbsp;{if $elem.__weight->getHint() != ''}<a class="help-icon" title="{$elem.__weight->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__weight->getRenderTemplate() field=$elem.__weight}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__dateof->getTitle()}&nbsp;&nbsp;{if $elem.__dateof->getHint() != ''}<a class="help-icon" title="{$elem.__dateof->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__dateof->getRenderTemplate() field=$elem.__dateof}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__excost->getTitle()}&nbsp;&nbsp;{if $elem.__excost->getHint() != ''}<a class="help-icon" title="{$elem.__excost->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__excost->getRenderTemplate() field=$elem.__excost}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__unit->getTitle()}&nbsp;&nbsp;{if $elem.__unit->getHint() != ''}<a class="help-icon" title="{$elem.__unit->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__unit->getRenderTemplate() field=$elem.__unit}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__min_order->getTitle()}&nbsp;&nbsp;{if $elem.__min_order->getHint() != ''}<a class="help-icon" title="{$elem.__min_order->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__min_order->getRenderTemplate() field=$elem.__min_order}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__public->getTitle()}&nbsp;&nbsp;{if $elem.__public->getHint() != ''}<a class="help-icon" title="{$elem.__public->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__public->getRenderTemplate() field=$elem.__public}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__xdir->getTitle()}&nbsp;&nbsp;{if $elem.__xdir->getHint() != ''}<a class="help-icon" title="{$elem.__xdir->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__xdir->getRenderTemplate() field=$elem.__xdir}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__maindir->getTitle()}&nbsp;&nbsp;{if $elem.__maindir->getHint() != ''}<a class="help-icon" title="{$elem.__maindir->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__maindir->getRenderTemplate() field=$elem.__maindir}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__xspec->getTitle()}&nbsp;&nbsp;{if $elem.__xspec->getHint() != ''}<a class="help-icon" title="{$elem.__xspec->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__xspec->getRenderTemplate() field=$elem.__xspec}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__reservation->getTitle()}&nbsp;&nbsp;{if $elem.__reservation->getHint() != ''}<a class="help-icon" title="{$elem.__reservation->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__reservation->getRenderTemplate() field=$elem.__reservation}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__brand_id->getTitle()}&nbsp;&nbsp;{if $elem.__brand_id->getHint() != ''}<a class="help-icon" title="{$elem.__brand_id->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__brand_id->getRenderTemplate() field=$elem.__brand_id}</td>
                                </tr>
                                
                                                                                                                                                                                        
                                <tr>
                                    <td class="otitle">{$elem.__rating->getTitle()}&nbsp;&nbsp;{if $elem.__rating->getHint() != ''}<a class="help-icon" title="{$elem.__rating->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__rating->getRenderTemplate() field=$elem.__rating}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__xml_id->getTitle()}&nbsp;&nbsp;{if $elem.__xml_id->getHint() != ''}<a class="help-icon" title="{$elem.__xml_id->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__xml_id->getRenderTemplate() field=$elem.__xml_id}</td>
                                </tr>
                                
                                                                                    </table>
                                                </div>
                        <div class="frame nodisp" data-name="tab1">
                                                    
                                                                        {include file=$elem.___property_->getRenderTemplate() field=$elem.___property_}
                                                                                                                                                </div>
                        <div class="frame nodisp" data-name="tab2">
                                                    
                                                                        {include file=$elem.___offers_->getRenderTemplate() field=$elem.___offers_}
                                                                                                                                                                                                                    </div>
                        <div class="frame nodisp" data-name="tab3">
                                                    
                                                                                                                                                                                                                                                                            <table class="otable">
                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__meta_title->getTitle()}&nbsp;&nbsp;{if $elem.__meta_title->getHint() != ''}<a class="help-icon" title="{$elem.__meta_title->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__meta_title->getRenderTemplate() field=$elem.__meta_title}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__meta_keywords->getTitle()}&nbsp;&nbsp;{if $elem.__meta_keywords->getHint() != ''}<a class="help-icon" title="{$elem.__meta_keywords->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__meta_keywords->getRenderTemplate() field=$elem.__meta_keywords}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__meta_description->getTitle()}&nbsp;&nbsp;{if $elem.__meta_description->getHint() != ''}<a class="help-icon" title="{$elem.__meta_description->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__meta_description->getRenderTemplate() field=$elem.__meta_description}</td>
                                </tr>
                                
                                                                                    </table>
                                                </div>
                        <div class="frame nodisp" data-name="tab4">
                                                    
                                                                        {include file=$elem.___recomended_->getRenderTemplate() field=$elem.___recomended_}
                                                                                                                                                </div>
                        <div class="frame nodisp" data-name="tab5">
                                                    
                                                                        {include file=$elem.___concomitant_->getRenderTemplate() field=$elem.___concomitant_}
                                                                                                                                                </div>
                        <div class="frame nodisp" data-name="tab6">
                                                    
                                                                        {include file=$elem.___photo_->getRenderTemplate() field=$elem.___photo_}
                                                                                                                                                </div>
                        <div class="frame nodisp" data-name="tab7">
                                                    
                                                                                                                                    <table class="otable">
                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__tax_ids->getTitle()}&nbsp;&nbsp;{if $elem.__tax_ids->getHint() != ''}<a class="help-icon" title="{$elem.__tax_ids->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__tax_ids->getRenderTemplate() field=$elem.__tax_ids}</td>
                                </tr>
                                
                                                                                    </table>
                                                </div>
                        <div class="frame nodisp" data-name="tab8">
                                                    
                                                                        {include file=$elem.____files__->getRenderTemplate() field=$elem.____files__}
                                                                                                                                                </div>
                    </form>
    </div>
    </div>