<div class="formbox" >
            
    <div class="tabs">
        <ul class="tab-container">
                    <li class=" act first"><a data-view="tab0">{$elem->getPropertyIterator()->getGroupName(0)}</a></li>
                    <li class=""><a data-view="tab1">{$elem->getPropertyIterator()->getGroupName(1)}</a></li>
                    <li class=""><a data-view="tab2">{$elem->getPropertyIterator()->getGroupName(2)}</a></li>
                </ul>
        <form method="POST" action="{urlmake}" enctype="multipart/form-data" class="crud-form">    
            <input type="submit" value="" style="display:none"/>
                        <div class="frame" data-name="tab0">
                                                    
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <table class="otable">
                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__SM_COMPILE_CHECK->getTitle()}</td>
                                    <td>{include file=$elem.__SM_COMPILE_CHECK->getRenderTemplate() field=$elem.__SM_COMPILE_CHECK}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__DETAILED_EXCEPTION->getTitle()}</td>
                                    <td>{include file=$elem.__DETAILED_EXCEPTION->getRenderTemplate() field=$elem.__DETAILED_EXCEPTION}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__CACHE_ENABLED->getTitle()}</td>
                                    <td>{include file=$elem.__CACHE_ENABLED->getRenderTemplate() field=$elem.__CACHE_ENABLED}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__CACHE_BLOCK_ENABLED->getTitle()}</td>
                                    <td>{include file=$elem.__CACHE_BLOCK_ENABLED->getRenderTemplate() field=$elem.__CACHE_BLOCK_ENABLED}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__CACHE_TIME->getTitle()}</td>
                                    <td>{include file=$elem.__CACHE_TIME->getRenderTemplate() field=$elem.__CACHE_TIME}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__COMPRESS_CSS->getTitle()}</td>
                                    <td>{include file=$elem.__COMPRESS_CSS->getRenderTemplate() field=$elem.__COMPRESS_CSS}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__COMPRESS_JS->getTitle()}</td>
                                    <td>{include file=$elem.__COMPRESS_JS->getRenderTemplate() field=$elem.__COMPRESS_JS}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__JS_POSITION_FOOTER->getTitle()}</td>
                                    <td>{include file=$elem.__JS_POSITION_FOOTER->getRenderTemplate() field=$elem.__JS_POSITION_FOOTER}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__show_debug_header->getTitle()}</td>
                                    <td>{include file=$elem.__show_debug_header->getRenderTemplate() field=$elem.__show_debug_header}</td>
                                </tr>
                                
                                                                                    </table>
                                                </div>
                        <div class="frame nodisp" data-name="tab1">
                                                    
                                                                        {include file=$elem.____cache__->getRenderTemplate() field=$elem.____cache__}
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
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__smtp_is_use->getTitle()}</td>
                                    <td>{include file=$elem.__smtp_is_use->getRenderTemplate() field=$elem.__smtp_is_use}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__smtp_host->getTitle()}</td>
                                    <td>{include file=$elem.__smtp_host->getRenderTemplate() field=$elem.__smtp_host}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__smtp_port->getTitle()}</td>
                                    <td>{include file=$elem.__smtp_port->getRenderTemplate() field=$elem.__smtp_port}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__smtp_secure->getTitle()}</td>
                                    <td>{include file=$elem.__smtp_secure->getRenderTemplate() field=$elem.__smtp_secure}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__smtp_auth->getTitle()}</td>
                                    <td>{include file=$elem.__smtp_auth->getRenderTemplate() field=$elem.__smtp_auth}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__smtp_username->getTitle()}</td>
                                    <td>{include file=$elem.__smtp_username->getRenderTemplate() field=$elem.__smtp_username}</td>
                                </tr>
                                
                                                                                                                            
                                <tr>
                                    <td class="otitle">{$elem.__smtp_password->getTitle()}</td>
                                    <td>{include file=$elem.__smtp_password->getRenderTemplate() field=$elem.__smtp_password}</td>
                                </tr>
                                
                                                                                    </table>
                                                </div>
                    </form>
    </div>
    </div>