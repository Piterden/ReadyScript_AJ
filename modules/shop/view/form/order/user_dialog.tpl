<div class="formbox">
    <form id="userAddForm" method="POST" action="{urlmake}" data-order-block="#userBlockWrapper" enctype="multipart/form-data" class="crud-form">
        <div class="notabs">
            <table class="otable">
                <tr>
                    <td class="otitle" style="border-bottom: 1px solid #eee;">Пользователь</td>
                    <td style="border-bottom: 1px solid #eee;">
                        <input name="is_reg_user" type="radio" value="0" id="link-user" checked><label for="link-user">Связать с зарегистрированным пользователем</label><br>
                        <input name="is_reg_user" type="radio" value="1" id="reg-user"><label for="reg-user">Зарегистрировать нового пользователя</label><br>
                        <br>
                        <div id="partner-link-user" class="reg-tab">
                            {$field=$elem->__user_id}
                            {include file=$field->getOriginalTemplate()}<br>
                        </div>
                        <div id="partner-reg-user" class="reg-tab" style="display:none">
                            <table class="intable">
                                <tr>
                                    <td class="otitle">{$user->__name->getTitle()}</td>
                                    <td>{include file=$user->__name->getRenderTemplate() field=$user->__name}</td>
                                </tr>    
                                <tr>
                                    <td class="otitle">{$user->__surname->getTitle()}</td>
                                    <td>{include file=$user->__surname->getRenderTemplate() field=$user->__surname}</td>
                                </tr>    
                                <tr>
                                    <td class="otitle">{$user->__midname->getTitle()}</td>
                                    <td>{include file=$user->__midname->getRenderTemplate() field=$user->__midname}</td>
                                </tr>  
                                <tr>
                                    <td class="otitle">{$user->__phone->getTitle()}</td>
                                    <td>{include file=$user->__phone->getRenderTemplate() field=$user->__phone}</td>
                                </tr>        
                                <tr>
                                    <td class="otitle">{$user->__e_mail->getTitle()}</td>
                                    <td>{include file=$user->__e_mail->getRenderTemplate() field=$user->__e_mail}</td>
                                </tr>         
                                <tr>
                                    <td class="otitle">{$user.__pass->getTitle()}</td>
                                    <td>
                                        {include file=$user.__pass->getRenderTemplate() field=$user.__pass}
                                        <input name="changepass" type="hidden" value="1">
                                    </td>
                                </tr>            
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </form>
    <script type="text/javascript">
        /**
        * Получает массив объектов параметров из запроса
        */
        function getParamsArray(query){
            var params = [];
            var parse_params = query.split("&");
            
            for(var i=0;i<parse_params.length;i++){
                var param = parse_params[i].split("=");
                params.push({
                    name : param[0],
                    value : param[1],
                });
            }
            
            return params;
        }
    
        $(function() {
            /**
            * Назначаем действия, если всё успешно вернулось 
            */
            $('#userAddForm').on('crudSaveSuccess', function(event, response) {
                if (response.success && response.insertBlockHTML){ //Если всё удачно и вернулся HTML для вставки в блок
                    var insertBlock = $(this).data('order-block');             
                    $(insertBlock).html(response.insertBlockHTML).trigger('new-content');
                    if (response.user_id){ //Если указан id пользователя
                       $('input[name="user_id"]').val(response.user_id); 
                    }
                    //Посмотрим, если есть кнопки с добавлением доставки заказу, то припишем к запросу ещё и пользователя
                    if ($(".editDeliveryButton").length && response.user_id){      
                       $(".editDeliveryButton").each(function(){
                           var href = $(this).data('href') ? $(this).data('href') : $(this).attr('href');
                           //разберём запрос
                           var url_array = href.split('?');
                           var url       = url_array[0];
                           var params    = getParamsArray(url_array[1]);
                           //Допишем сведения о пользователе В запрос и обновим ссылку
                           params.push({
                               name  : 'user_id',
                               value : response.user_id
                           });
                           href = url + "?" + $.param(params);
                           $(this).attr('href', href);
                           $(this).data('href', href);
                       }); 
                    }
                }
            });
            
            //Смена типа регистрации пользователя 
            var regChange = function() {
                var value = $('input[name="is_reg_user"]:checked');
                $('.reg-tab').hide();
                $('#partner-'+value.attr('id')).show();
            }
            $('input[name="is_reg_user"]').change(regChange);
            regChange();
        });
    </script>

</div>

