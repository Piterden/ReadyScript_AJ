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
                                    <td class="otitle">{$elem.__hide_from_url->getTitle()}</td>
                                    <td>{include file=$elem.__hide_from_url->getRenderTemplate() field=$elem.__hide_from_url}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__alias->getTitle()}</td>
                                    <td>{include file=$elem.__alias->getRenderTemplate() field=$elem.__alias}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__parent->getTitle()}</td>
                                    <td>{include file=$elem.__parent->getRenderTemplate() field=$elem.__parent}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__public->getTitle()}</td>
                                    <td>{include file=$elem.__public->getRenderTemplate() field=$elem.__public}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__typelink->getTitle()}</td>
                                    <td>{include file=$elem.__typelink->getRenderTemplate() field=$elem.__typelink}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.____setlink__->getTitle()}</td>
                                    <td>{include file=$elem.____setlink__->getRenderTemplate() field=$elem.____setlink__}</td>
                                </tr>
                                                                                                        </table>
                            </div>
        </form>
    </div>