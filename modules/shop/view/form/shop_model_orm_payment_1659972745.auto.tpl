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
                                    <td class="otitle">{$elem.__description->getTitle()}</td>
                                    <td>{include file=$elem.__description->getRenderTemplate() field=$elem.__description}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__picture->getTitle()}</td>
                                    <td>{include file=$elem.__picture->getRenderTemplate() field=$elem.__picture}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__first_status->getTitle()}</td>
                                    <td>{include file=$elem.__first_status->getRenderTemplate() field=$elem.__first_status}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__success_status->getTitle()}</td>
                                    <td>{include file=$elem.__success_status->getRenderTemplate() field=$elem.__success_status}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__user_type->getTitle()}</td>
                                    <td>{include file=$elem.__user_type->getRenderTemplate() field=$elem.__user_type}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__target->getTitle()}</td>
                                    <td>{include file=$elem.__target->getRenderTemplate() field=$elem.__target}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__delivery->getTitle()}</td>
                                    <td>{include file=$elem.__delivery->getRenderTemplate() field=$elem.__delivery}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__public->getTitle()}</td>
                                    <td>{include file=$elem.__public->getRenderTemplate() field=$elem.__public}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__default_payment->getTitle()}</td>
                                    <td>{include file=$elem.__default_payment->getRenderTemplate() field=$elem.__default_payment}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__class->getTitle()}</td>
                                    <td>{include file=$elem.__class->getRenderTemplate() field=$elem.__class}</td>
                                </tr>
                                                                                                        </table>
                            </div>
        </form>
    </div>