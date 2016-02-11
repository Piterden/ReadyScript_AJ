<div class="formbox" >
            
    <div class="tabs">
        <ul class="tab-container">
                    <li class=" act first"><a data-view="tab0">{$elem->getPropertyIterator()->getGroupName(0)}</a></li>
                    <li class=""><a data-view="tab1">{$elem->getPropertyIterator()->getGroupName(1)}</a></li>
                    <li class=""><a data-view="tab2">{$elem->getPropertyIterator()->getGroupName(2)}</a></li>
                    <li class=""><a data-view="tab3">{$elem->getPropertyIterator()->getGroupName(3)}</a></li>
                    <li class=""><a data-view="tab4">{$elem->getPropertyIterator()->getGroupName(4)}</a></li>
                </ul>
        <form method="POST" action="{urlmake}" enctype="multipart/form-data" class="crud-form">    
            <input type="submit" value="" style="display:none"/>
                        <div class="frame" data-name="tab0">
                                                    
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <table class="otable">
                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__name->getTitle()}</td>
                                    <td>{include file=$elem.__name->getRenderTemplate() field=$elem.__name}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__surname->getTitle()}</td>
                                    <td>{include file=$elem.__surname->getRenderTemplate() field=$elem.__surname}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__midname->getTitle()}</td>
                                    <td>{include file=$elem.__midname->getRenderTemplate() field=$elem.__midname}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__e_mail->getTitle()}</td>
                                    <td>{include file=$elem.__e_mail->getRenderTemplate() field=$elem.__e_mail}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__login->getTitle()}</td>
                                    <td>{include file=$elem.__login->getRenderTemplate() field=$elem.__login}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__openpass->getTitle()}</td>
                                    <td>{include file=$elem.__openpass->getRenderTemplate() field=$elem.__openpass}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__phone->getTitle()}</td>
                                    <td>{include file=$elem.__phone->getRenderTemplate() field=$elem.__phone}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__sex->getTitle()}</td>
                                    <td>{include file=$elem.__sex->getRenderTemplate() field=$elem.__sex}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__subscribe_on->getTitle()}</td>
                                    <td>{include file=$elem.__subscribe_on->getRenderTemplate() field=$elem.__subscribe_on}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__dateofreg->getTitle()}</td>
                                    <td>{include file=$elem.__dateofreg->getRenderTemplate() field=$elem.__dateofreg}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__ban_expire->getTitle()}</td>
                                    <td>{include file=$elem.__ban_expire->getRenderTemplate() field=$elem.__ban_expire}</td>
                                </tr>
                                
                                                                                    </table>
                                                </div>
                        <div class="frame nodisp" data-name="tab1">
                                                    
                                                                                                                                                                                                                                                                            <table class="otable">
                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__is_company->getTitle()}</td>
                                    <td>{include file=$elem.__is_company->getRenderTemplate() field=$elem.__is_company}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__company->getTitle()}</td>
                                    <td>{include file=$elem.__company->getRenderTemplate() field=$elem.__company}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__company_inn->getTitle()}</td>
                                    <td>{include file=$elem.__company_inn->getRenderTemplate() field=$elem.__company_inn}</td>
                                </tr>
                                
                                                                                    </table>
                                                </div>
                        <div class="frame nodisp" data-name="tab2">
                                                    
                                                                        {include file=$elem.____groups__->getRenderTemplate() field=$elem.____groups__}
                                                                                                                                                                                                                    </div>
                        <div class="frame nodisp" data-name="tab3">
                                                    
                                                                                                                                    <table class="otable">
                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__data->getTitle()}</td>
                                    <td>{include file=$elem.__data->getRenderTemplate() field=$elem.__data}</td>
                                </tr>
                                
                                                                                    </table>
                                                </div>
                        <div class="frame nodisp" data-name="tab4">
                                                    
                                                                                                                                    <table class="otable">
                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__user_cost->getTitle()}</td>
                                    <td>{include file=$elem.__user_cost->getRenderTemplate() field=$elem.__user_cost}</td>
                                </tr>
                                
                                                                                    </table>
                                                </div>
                    </form>
    </div>
    </div>