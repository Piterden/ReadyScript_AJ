<div class="formbox" >
                
        <form method="POST" action="{urlmake}" enctype="multipart/form-data" class="crud-form">
            <input type="submit" value="" style="display:none">
            <div class="notabs">
                                                                                                            
                                                                                            
                                                                                            
                                                    
                                    <table class="otable">
                                                                                                                    
                                <tr>
                                    <td class="otitle">{$elem.__route_id->getTitle()}</td>
                                    <td>{include file=$elem.__route_id->getRenderTemplate() field=$elem.__route_id}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__template->getTitle()}</td>
                                    <td>{include file=$elem.__template->getRenderTemplate() field=$elem.__template}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__inherit->getTitle()}</td>
                                    <td>{include file=$elem.__inherit->getRenderTemplate() field=$elem.__inherit}</td>
                                </tr>
                                                                                                        </table>
                            </div>
        </form>
    </div>