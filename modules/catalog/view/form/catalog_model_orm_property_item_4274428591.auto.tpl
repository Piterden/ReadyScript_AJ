<div class="formbox" >
                
        <form method="POST" action="{urlmake}" enctype="multipart/form-data" class="crud-form">
            <input type="submit" value="" style="display:none">
            <div class="notabs">
                                                                                                            
                                                                                            
                                                                                            
                                                                                            
                                                                                            
                                                                                            
                                                    
                                    <table class="otable">
                                                                                                                    
                                <tr>
                                    <td class="otitle">{$elem.__title->getTitle()}</td>
                                    <td>{include file=$elem.__title->getRenderTemplate() field=$elem.__title}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__type->getTitle()}</td>
                                    <td>{include file=$elem.__type->getRenderTemplate() field=$elem.__type}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.___tpl_->getTitle()}</td>
                                    <td>{include file=$elem.___tpl_->getRenderTemplate() field=$elem.___tpl_}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__unit->getTitle()}</td>
                                    <td>{include file=$elem.__unit->getRenderTemplate() field=$elem.__unit}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__parent_id->getTitle()}</td>
                                    <td>{include file=$elem.__parent_id->getRenderTemplate() field=$elem.__parent_id}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__hidden->getTitle()}</td>
                                    <td>{include file=$elem.__hidden->getRenderTemplate() field=$elem.__hidden}</td>
                                </tr>
                                                                                                        </table>
                            </div>
        </form>
    </div>