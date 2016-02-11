<div class="formbox" >
            
    <div class="tabs">
        <ul class="tab-container">
                    <li class=" act first"><a data-view="tab0">{$elem->getPropertyIterator()->getGroupName(0)}</a></li>
                    <li class=""><a data-view="tab1">{$elem->getPropertyIterator()->getGroupName(1)}</a></li>
                    <li class=""><a data-view="tab2">{$elem->getPropertyIterator()->getGroupName(2)}</a></li>
                    <li class=""><a data-view="tab3">{$elem->getPropertyIterator()->getGroupName(3)}</a></li>
                </ul>
        <form method="POST" action="{urlmake}" enctype="multipart/form-data" class="crud-form">    
            <input type="submit" value="" style="display:none"/>
                        <div class="frame" data-name="tab0">
                                                    
                                                                                                                                                                                                                                                                                                                                                <table class="otable">
                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__admin_email->getTitle()}</td>
                                    <td>{include file=$elem.__admin_email->getRenderTemplate() field=$elem.__admin_email}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__admin_phone->getTitle()}</td>
                                    <td>{include file=$elem.__admin_phone->getRenderTemplate() field=$elem.__admin_phone}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__theme->getTitle()}</td>
                                    <td>{include file=$elem.__theme->getRenderTemplate() field=$elem.__theme}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__favicon->getTitle()}</td>
                                    <td>{include file=$elem.__favicon->getRenderTemplate() field=$elem.__favicon}</td>
                                </tr>
                                
                                                                                    </table>
                                                </div>
                        <div class="frame nodisp" data-name="tab1">
                                                    
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <table class="otable">
                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__logo->getTitle()}</td>
                                    <td>{include file=$elem.__logo->getRenderTemplate() field=$elem.__logo}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__slogan->getTitle()}</td>
                                    <td>{include file=$elem.__slogan->getRenderTemplate() field=$elem.__slogan}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__firm_name->getTitle()}</td>
                                    <td>{include file=$elem.__firm_name->getRenderTemplate() field=$elem.__firm_name}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__firm_inn->getTitle()}</td>
                                    <td>{include file=$elem.__firm_inn->getRenderTemplate() field=$elem.__firm_inn}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__firm_kpp->getTitle()}</td>
                                    <td>{include file=$elem.__firm_kpp->getRenderTemplate() field=$elem.__firm_kpp}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__firm_bank->getTitle()}</td>
                                    <td>{include file=$elem.__firm_bank->getRenderTemplate() field=$elem.__firm_bank}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__firm_bik->getTitle()}</td>
                                    <td>{include file=$elem.__firm_bik->getRenderTemplate() field=$elem.__firm_bik}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__firm_rs->getTitle()}</td>
                                    <td>{include file=$elem.__firm_rs->getRenderTemplate() field=$elem.__firm_rs}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__firm_ks->getTitle()}</td>
                                    <td>{include file=$elem.__firm_ks->getRenderTemplate() field=$elem.__firm_ks}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__firm_director->getTitle()}</td>
                                    <td>{include file=$elem.__firm_director->getRenderTemplate() field=$elem.__firm_director}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__firm_accountant->getTitle()}</td>
                                    <td>{include file=$elem.__firm_accountant->getRenderTemplate() field=$elem.__firm_accountant}</td>
                                </tr>
                                
                                                                                    </table>
                                                </div>
                        <div class="frame nodisp" data-name="tab2">
                                                    
                                                                                                                                                                                                        <table class="otable">
                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__notice_from->getTitle()}</td>
                                    <td>{include file=$elem.__notice_from->getRenderTemplate() field=$elem.__notice_from}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__notice_reply->getTitle()}</td>
                                    <td>{include file=$elem.__notice_reply->getRenderTemplate() field=$elem.__notice_reply}</td>
                                </tr>
                                
                                                                                    </table>
                                                </div>
                        <div class="frame nodisp" data-name="tab3">
                                                    
                                                                                                                                                                                                                                                                            <table class="otable">
                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__facebook_group->getTitle()}</td>
                                    <td>{include file=$elem.__facebook_group->getRenderTemplate() field=$elem.__facebook_group}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__vkontakte_group->getTitle()}</td>
                                    <td>{include file=$elem.__vkontakte_group->getRenderTemplate() field=$elem.__vkontakte_group}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__twitter_group->getTitle()}</td>
                                    <td>{include file=$elem.__twitter_group->getRenderTemplate() field=$elem.__twitter_group}</td>
                                </tr>
                                
                                                                                    </table>
                                                </div>
                    </form>
    </div>
    </div>