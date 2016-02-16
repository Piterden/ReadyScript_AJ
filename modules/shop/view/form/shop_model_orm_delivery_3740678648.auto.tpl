<div class="formbox" >
                
        <form method="POST" action="{urlmake}" enctype="multipart/form-data" class="crud-form">
            <input type="submit" value="" style="display:none">
            <div class="notabs">
                                                                                                            
                                                                                            
                                                                                            
                                                                                            
                                                                                            
                                                                                            
                                                                                            
                                                                                            
                                                                                            
                                                                                            
                                                                                            
                                                    
                                    <table class="otable">
                                                                                                                    
                                <tr>
                                    <td class="otitle">{$elem.__title->getTitle()}&nbsp;&nbsp;{if $elem.__title->getHint() != ''}<a class="help-icon" title="{$elem.__title->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__title->getRenderTemplate() field=$elem.__title}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__description->getTitle()}&nbsp;&nbsp;{if $elem.__description->getHint() != ''}<a class="help-icon" title="{$elem.__description->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__description->getRenderTemplate() field=$elem.__description}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__picture->getTitle()}&nbsp;&nbsp;{if $elem.__picture->getHint() != ''}<a class="help-icon" title="{$elem.__picture->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__picture->getRenderTemplate() field=$elem.__picture}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__xzone->getTitle()}&nbsp;&nbsp;{if $elem.__xzone->getHint() != ''}<a class="help-icon" title="{$elem.__xzone->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__xzone->getRenderTemplate() field=$elem.__xzone}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__min_price->getTitle()}&nbsp;&nbsp;{if $elem.__min_price->getHint() != ''}<a class="help-icon" title="{$elem.__min_price->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__min_price->getRenderTemplate() field=$elem.__min_price}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__max_price->getTitle()}&nbsp;&nbsp;{if $elem.__max_price->getHint() != ''}<a class="help-icon" title="{$elem.__max_price->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__max_price->getRenderTemplate() field=$elem.__max_price}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__min_cnt->getTitle()}&nbsp;&nbsp;{if $elem.__min_cnt->getHint() != ''}<a class="help-icon" title="{$elem.__min_cnt->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__min_cnt->getRenderTemplate() field=$elem.__min_cnt}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__first_status->getTitle()}&nbsp;&nbsp;{if $elem.__first_status->getHint() != ''}<a class="help-icon" title="{$elem.__first_status->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__first_status->getRenderTemplate() field=$elem.__first_status}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__user_type->getTitle()}&nbsp;&nbsp;{if $elem.__user_type->getHint() != ''}<a class="help-icon" title="{$elem.__user_type->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__user_type->getRenderTemplate() field=$elem.__user_type}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__public->getTitle()}&nbsp;&nbsp;{if $elem.__public->getHint() != ''}<a class="help-icon" title="{$elem.__public->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__public->getRenderTemplate() field=$elem.__public}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__class->getTitle()}&nbsp;&nbsp;{if $elem.__class->getHint() != ''}<a class="help-icon" title="{$elem.__class->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__class->getRenderTemplate() field=$elem.__class}</td>
                                </tr>
                                                                                                        </table>
                            </div>
        </form>
    </div>