
                                            
                                            
                                            
                                            
                                            
                                            
    
                                    
            <tr>
                <td class="otitle">{$elem.__secret_login->getTitle()}&nbsp;&nbsp;{if $elem.__secret_login->getHint() != ''}<a class="help-icon" title="{$elem.__secret_login->getHint()|escape}">?</a>{/if}
                </td>
                <td>{include file=$elem.__secret_login->getRenderTemplate() field=$elem.__secret_login}</td>
            </tr>
                                            
            <tr>
                <td class="otitle">{$elem.__secret_pass->getTitle()}&nbsp;&nbsp;{if $elem.__secret_pass->getHint() != ''}<a class="help-icon" title="{$elem.__secret_pass->getHint()|escape}">?</a>{/if}
                </td>
                <td>{include file=$elem.__secret_pass->getRenderTemplate() field=$elem.__secret_pass}</td>
            </tr>
                                            
            <tr>
                <td class="otitle">{$elem.__region_id_from->getTitle()}&nbsp;&nbsp;{if $elem.__region_id_from->getHint() != ''}<a class="help-icon" title="{$elem.__region_id_from->getHint()|escape}">?</a>{/if}
                </td>
                <td>{include file=$elem.__region_id_from->getRenderTemplate() field=$elem.__region_id_from}</td>
            </tr>
                                            
            <tr>
                <td class="otitle">{$elem.__service_id->getTitle()}&nbsp;&nbsp;{if $elem.__service_id->getHint() != ''}<a class="help-icon" title="{$elem.__service_id->getHint()|escape}">?</a>{/if}
                </td>
                <td>{include file=$elem.__service_id->getRenderTemplate() field=$elem.__service_id}</td>
            </tr>
                                            
            <tr>
                <td class="otitle">{$elem.__show_map->getTitle()}&nbsp;&nbsp;{if $elem.__show_map->getHint() != ''}<a class="help-icon" title="{$elem.__show_map->getHint()|escape}">?</a>{/if}
                </td>
                <td>{include file=$elem.__show_map->getRenderTemplate() field=$elem.__show_map}</td>
            </tr>
                                            
            <tr>
                <td class="otitle">{$elem.__address_type->getTitle()}&nbsp;&nbsp;{if $elem.__address_type->getHint() != ''}<a class="help-icon" title="{$elem.__address_type->getHint()|escape}">?</a>{/if}
                </td>
                <td>{include file=$elem.__address_type->getRenderTemplate() field=$elem.__address_type}</td>
            </tr>
                        