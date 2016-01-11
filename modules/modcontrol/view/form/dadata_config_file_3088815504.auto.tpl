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
                                    <td class="otitle">{$elem.__api_key->getTitle()}</td>
                                    <td>{include file=$elem.__api_key->getRenderTemplate() field=$elem.__api_key}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__email_show_hint->getTitle()}</td>
                                    <td>{include file=$elem.__email_show_hint->getRenderTemplate() field=$elem.__email_show_hint}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__email_show_all->getTitle()}</td>
                                    <td>{include file=$elem.__email_show_all->getRenderTemplate() field=$elem.__email_show_all}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__address_show_hint->getTitle()}</td>
                                    <td>{include file=$elem.__address_show_hint->getRenderTemplate() field=$elem.__address_show_hint}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__city_show_hint->getTitle()}</td>
                                    <td>{include file=$elem.__city_show_hint->getRenderTemplate() field=$elem.__city_show_hint}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__company_show_hint->getTitle()}</td>
                                    <td>{include file=$elem.__company_show_hint->getRenderTemplate() field=$elem.__company_show_hint}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__company_inn_input->getTitle()}</td>
                                    <td>{include file=$elem.__company_inn_input->getRenderTemplate() field=$elem.__company_inn_input}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__fio_show_hint->getTitle()}</td>
                                    <td>{include file=$elem.__fio_show_hint->getRenderTemplate() field=$elem.__fio_show_hint}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__surname_show_hint->getTitle()}</td>
                                    <td>{include file=$elem.__surname_show_hint->getRenderTemplate() field=$elem.__surname_show_hint}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__name_show_hint->getTitle()}</td>
                                    <td>{include file=$elem.__name_show_hint->getRenderTemplate() field=$elem.__name_show_hint}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__midname_show_hint->getTitle()}</td>
                                    <td>{include file=$elem.__midname_show_hint->getRenderTemplate() field=$elem.__midname_show_hint}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__ip_check_city->getTitle()}</td>
                                    <td>{include file=$elem.__ip_check_city->getRenderTemplate() field=$elem.__ip_check_city}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__count->getTitle()}</td>
                                    <td>{include file=$elem.__count->getRenderTemplate() field=$elem.__count}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__dadata_api_js->getTitle()}</td>
                                    <td>{include file=$elem.__dadata_api_js->getRenderTemplate() field=$elem.__dadata_api_js}</td>
                                </tr>
                                                                        </table>
                            </div>
        </form>
    </div>