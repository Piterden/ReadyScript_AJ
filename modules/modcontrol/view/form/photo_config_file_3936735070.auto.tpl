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
                                    <td class="otitle">{$elem.__original_photos_resize->getTitle()}</td>
                                    <td>{include file=$elem.__original_photos_resize->getRenderTemplate() field=$elem.__original_photos_resize}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__original_photos_width->getTitle()}</td>
                                    <td>{include file=$elem.__original_photos_width->getRenderTemplate() field=$elem.__original_photos_width}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__original_photos_height->getTitle()}</td>
                                    <td>{include file=$elem.__original_photos_height->getRenderTemplate() field=$elem.__original_photos_height}</td>
                                </tr>
                                                            
                                <tr>
                                    <td class="otitle">{$elem.__product_sort_photo_desc->getTitle()}</td>
                                    <td>{include file=$elem.__product_sort_photo_desc->getRenderTemplate() field=$elem.__product_sort_photo_desc}</td>
                                </tr>
                                                                        </table>
                            </div>
        </form>
    </div>