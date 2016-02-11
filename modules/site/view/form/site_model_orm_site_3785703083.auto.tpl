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
                                    <td class="otitle">{$elem.__full_title->getTitle()}</td>
                                    <td>{include file=$elem.__full_title->getRenderTemplate() field=$elem.__full_title}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__domains->getTitle()}</td>
                                    <td>{include file=$elem.__domains->getRenderTemplate() field=$elem.__domains}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__folder->getTitle()}</td>
                                    <td>{include file=$elem.__folder->getRenderTemplate() field=$elem.__folder}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__language->getTitle()}</td>
                                    <td>{include file=$elem.__language->getRenderTemplate() field=$elem.__language}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__default->getTitle()}</td>
                                    <td>{include file=$elem.__default->getRenderTemplate() field=$elem.__default}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__update_robots_txt->getTitle()}</td>
                                    <td>{include file=$elem.__update_robots_txt->getRenderTemplate() field=$elem.__update_robots_txt}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__redirect_to_main_domain->getTitle()}</td>
                                    <td>{include file=$elem.__redirect_to_main_domain->getRenderTemplate() field=$elem.__redirect_to_main_domain}</td>
                                </tr>
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__theme->getTitle()}</td>
                                    <td>{include file=$elem.__theme->getRenderTemplate() field=$elem.__theme}</td>
                                </tr>
                                                                                                        </table>
                            </div>
        </form>
    </div>