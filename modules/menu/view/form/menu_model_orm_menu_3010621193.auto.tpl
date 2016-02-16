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
                                    <td class="otitle">{$elem.__hide_from_url->getTitle()}&nbsp;&nbsp;{if $elem.__hide_from_url->getHint() != ''}<a class="help-icon" title="{$elem.__hide_from_url->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__hide_from_url->getRenderTemplate() field=$elem.__hide_from_url}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__alias->getTitle()}&nbsp;&nbsp;{if $elem.__alias->getHint() != ''}<a class="help-icon" title="{$elem.__alias->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__alias->getRenderTemplate() field=$elem.__alias}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__parent->getTitle()}&nbsp;&nbsp;{if $elem.__parent->getHint() != ''}<a class="help-icon" title="{$elem.__parent->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__parent->getRenderTemplate() field=$elem.__parent}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__public->getTitle()}&nbsp;&nbsp;{if $elem.__public->getHint() != ''}<a class="help-icon" title="{$elem.__public->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__public->getRenderTemplate() field=$elem.__public}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__typelink->getTitle()}&nbsp;&nbsp;{if $elem.__typelink->getHint() != ''}<a class="help-icon" title="{$elem.__typelink->getHint()|escape}">?</a>{/if}
                                    </td>
                                    <td>{include file=$elem.__typelink->getRenderTemplate() field=$elem.__typelink}</td>
                                </tr>
                                                                                                        </table>
                            </div>
        </form>
    </div>