<div class="formbox" >
    {if $elem._before_form_template}{include file=$elem._before_form_template}{/if}

                
        <form method="POST" action="{urlmake}" enctype="multipart/form-data" class="crud-form">
            <input type="submit" value="" style="display:none">
            <div class="notabs">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
                                    <table class="otable">
                                                                                    
                                <tr>
                                    <td class="otitle">{$elem.__name->getTitle()}</td>
                                    <td>{include file=$elem.__name->getRenderTemplate() field=$elem.__name}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__description->getTitle()}</td>
                                    <td>{include file=$elem.__description->getRenderTemplate() field=$elem.__description}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__version->getTitle()}</td>
                                    <td>{include file=$elem.__version->getRenderTemplate() field=$elem.__version}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__core_version->getTitle()}</td>
                                    <td>{include file=$elem.__core_version->getRenderTemplate() field=$elem.__core_version}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__author->getTitle()}</td>
                                    <td>{include file=$elem.__author->getRenderTemplate() field=$elem.__author}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__enabled->getTitle()}</td>
                                    <td>{include file=$elem.__enabled->getRenderTemplate() field=$elem.__enabled}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__priority->getTitle()}</td>
                                    <td>{include file=$elem.__priority->getRenderTemplate() field=$elem.__priority}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__changefreq->getTitle()}</td>
                                    <td>{include file=$elem.__changefreq->getRenderTemplate() field=$elem.__changefreq}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__set_generate_time_as_lastmod->getTitle()}</td>
                                    <td>{include file=$elem.__set_generate_time_as_lastmod->getRenderTemplate() field=$elem.__set_generate_time_as_lastmod}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__lifetime->getTitle()}</td>
                                    <td>{include file=$elem.__lifetime->getRenderTemplate() field=$elem.__lifetime}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__add_urls->getTitle()}</td>
                                    <td>{include file=$elem.__add_urls->getRenderTemplate() field=$elem.__add_urls}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__exclude_urls->getTitle()}</td>
                                    <td>{include file=$elem.__exclude_urls->getRenderTemplate() field=$elem.__exclude_urls}</td>
                                </tr>
                                                                        </table>
                            </div>
        </form>
    </div>