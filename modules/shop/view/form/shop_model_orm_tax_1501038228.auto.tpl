<div class="formbox" >
            
    <div class="tabs">
        <ul class="tab-container">
                    <li class=" act first"><a data-view="tab0">{$elem->getPropertyIterator()->getGroupName(0)}</a></li>
                    <li class=""><a data-view="tab1">{$elem->getPropertyIterator()->getGroupName(1)}</a></li>
                </ul>
        <form method="POST" action="{urlmake}" enctype="multipart/form-data" class="crud-form">    
            <input type="submit" value="" style="display:none"/>
                        <div class="frame" data-name="tab0">
                                                    
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
                                    <td class="otitle">{$elem.__description->getTitle()}&nbsp;&nbsp;{if $elem.__description->getHint() != ''}<a class="help-icon" title="{$elem.__description->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__description->getRenderTemplate() field=$elem.__description}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__enabled->getTitle()}&nbsp;&nbsp;{if $elem.__enabled->getHint() != ''}<a class="help-icon" title="{$elem.__enabled->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__enabled->getRenderTemplate() field=$elem.__enabled}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__user_type->getTitle()}&nbsp;&nbsp;{if $elem.__user_type->getHint() != ''}<a class="help-icon" title="{$elem.__user_type->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__user_type->getRenderTemplate() field=$elem.__user_type}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__included->getTitle()}&nbsp;&nbsp;{if $elem.__included->getHint() != ''}<a class="help-icon" title="{$elem.__included->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__included->getRenderTemplate() field=$elem.__included}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__sortn->getTitle()}&nbsp;&nbsp;{if $elem.__sortn->getHint() != ''}<a class="help-icon" title="{$elem.__sortn->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__sortn->getRenderTemplate() field=$elem.__sortn}</td>
                                </tr>
                                
                                                                                    </table>
                                                </div>
                        <div class="frame nodisp" data-name="tab1">
                                                    
                                                                        {include file=$elem.____rates__->getRenderTemplate() field=$elem.____rates__}
                                                                                                                                                                                                                    </div>
                    </form>
    </div>
    </div>