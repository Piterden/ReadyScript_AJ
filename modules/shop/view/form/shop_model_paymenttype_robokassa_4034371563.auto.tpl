
                                            
                                            
                                            
                                            
                                            
    
                                    
            <tr>
                <td class="otitle">{$elem.__testmode->getTitle()}&nbsp;&nbsp;{if $elem.__testmode->getHint() != ''}<a class="help-icon" title="{$elem.__testmode->getHint()|escape}">?</a>{/if}
                </td>
                <td>{include file=$elem.__testmode->getRenderTemplate() field=$elem.__testmode}</td>
            </tr>
                                            
            <tr>
                <td class="otitle">{$elem.__login->getTitle()}&nbsp;&nbsp;{if $elem.__login->getHint() != ''}<a class="help-icon" title="{$elem.__login->getHint()|escape}">?</a>{/if}
                </td>
                <td>{include file=$elem.__login->getRenderTemplate() field=$elem.__login}</td>
            </tr>
                                            
            <tr>
                <td class="otitle">{$elem.__password_1->getTitle()}&nbsp;&nbsp;{if $elem.__password_1->getHint() != ''}<a class="help-icon" title="{$elem.__password_1->getHint()|escape}">?</a>{/if}
                </td>
                <td>{include file=$elem.__password_1->getRenderTemplate() field=$elem.__password_1}</td>
            </tr>
                                            
            <tr>
                <td class="otitle">{$elem.__password_2->getTitle()}&nbsp;&nbsp;{if $elem.__password_2->getHint() != ''}<a class="help-icon" title="{$elem.__password_2->getHint()|escape}">?</a>{/if}
                </td>
                <td>{include file=$elem.__password_2->getRenderTemplate() field=$elem.__password_2}</td>
            </tr>
                                            
            <tr>
                <td class="otitle">{$elem.____help__->getTitle()}&nbsp;&nbsp;{if $elem.____help__->getHint() != ''}<a class="help-icon" title="{$elem.____help__->getHint()|escape}">?</a>{/if}
                </td>
                <td>{include file=$elem.____help__->getRenderTemplate() field=$elem.____help__}</td>
            </tr>
                        