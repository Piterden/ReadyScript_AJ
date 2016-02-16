
                                            
    
                                    
            <tr>
                <td class="otitle">{$elem.__max_weight->getTitle()}&nbsp;&nbsp;{if $elem.__max_weight->getHint() != ''}<a class="help-icon" title="{$elem.__max_weight->getHint()|escape}">?</a>{/if}
                </td>
                <td>{include file=$elem.__max_weight->getRenderTemplate() field=$elem.__max_weight}</td>
            </tr>
                        