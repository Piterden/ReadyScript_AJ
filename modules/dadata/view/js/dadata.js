$(function(){
    /**
    * Навешивание подсказки корректировки адреса
    */
    function dadataBindHints()
    {
        /**
        * Определим по IP город, которые были заранее определены
        */
        if (global.dadata_ip_city && $("[name^='addr_city']").length && !$("[name^='addr_city']").val()){
            $("[name^='addr_city']").val(global.dadata_ip_city);
        }
        /**
        * Определим по IP регион, которые были заранее определены
        */
        if (global.dadata_ip_region && $("[name^='addr_region_id']").length){
            var option = $("[name^='addr_region_id'] option:contains(\""+global.dadata_ip_region+"\")");
            if (option.length){
                option.prop('selected', true);
            }else{
                var option = $("[name^='addr_region_id'] option:contains(\""+global.dadata_ip_region_text+"\")");
                if (option.length){
                   option.prop('selected', true); 
                }
            }
        }
        
        
        /**
        * Инициализируем общие функции по подсветке подсказок
        * 
        * @type Object
        */
        var init_params = {
            serviceUrl     : "https://dadata.ru/api/v2",
            count          : global.dadata_config.count ? global.dadata_config.count : 5, //кол
            token          : global.dadata_config.api_key,
            hint           : false, 
            addon          : 'none', //Не показывать в правом углу ничего лишнего
            deferRequestBy : 100, //Задержка между запросами
            minChars       : 2, //Минимальное количество символов
            //Уберём лишние пробелы
            onSelect: function(suggestion) {
                $(this).val($.trim($(this).val()));
            }
        };
        
        if (global.dadata_config.fio_show_hint){
            /**
            * Включает подсказки в Наименовании компании 
            */
            $("[name^='user_fio']").suggestions($.extend(init_params, {
                type           : "NAME",
                gender         : "UNKNOWN",
                /*
                * Возвращает строку для вставки в поле ввода при выборе подсказки
                */
                formatSelected: function(suggestion) {
                    //Вернём правильный результат
                    return suggestion.value;
                }
            }));  
        }
        
        if (global.dadata_config.surname_show_hint){
            /**
            * Включает подсказки в Наименовании компании 
            */
            $("[name^='reg_surname']").suggestions($.extend(init_params, {
                type           : "NAME",
                gender         : "UNKNOWN",
                params: {
                    parts: ["SURNAME"], //Поле Фамилия 
                },
                /*
                * Возвращает строку для вставки в поле ввода при выборе подсказки
                */
                formatSelected: function(suggestion) {
                    //Вернём правильный результат
                    return suggestion.value;
                }
            }));  
        }
        
        if (global.dadata_config.name_show_hint){
            /**
            * Включает подсказки в Наименовании компании 
            */
            $("[name^='reg_name']").suggestions($.extend(init_params, {
                type           : "NAME",
                gender         : "UNKNOWN",
                params: {
                    parts: ["NAME"], //Поле Фамилия 
                },
                /*
                * Возвращает строку для вставки в поле ввода при выборе подсказки
                */
                formatSelected: function(suggestion) {
                    //Вернём правильный результат
                    return suggestion.value;
                }
            }));  
        }
        
        if (global.dadata_config.midname_show_hint){
            /**
            * Включает подсказки в Наименовании компании 
            */
            $("[name^='reg_midname']").suggestions($.extend(init_params, {
                type           : "NAME",
                gender         : "UNKNOWN",
                params: {
                    parts: ["PATRONYMIC"], //Поле Фамилия 
                },
                /*
                * Возвращает строку для вставки в поле ввода при выборе подсказки
                */
                formatSelected: function(suggestion) {
                    //Вернём правильный результат
                    return suggestion.value;
                }
            }));  
        }
        
        
        
        
        if (global.dadata_config.company_show_hint){
            /**
            * Включает подсказки в Наименовании компании 
            */
            $("[name^='reg_company']").suggestions($.extend(init_params, {
                serviceUrl     : "https://dadata.ru/api/v2",
                count          : global.dadata_config.count ? global.dadata_config.count : 5,
                token          : global.dadata_config.api_key,
                hint           : false, 
                addon          : 'none', //Не показывать в правом углу ничего лишнего
                deferRequestBy : 100, //Задержка между запросами
                minChars       : 2, //Минимальное количество символов
                type           : "PARTY",
                
                /*
                * Возвращает строку для вставки в поле ввода при выборе подсказки
                */
                formatSelected: function(suggestion) {
                    if (global.dadata_config.company_inn_input){
                        //Вставим ИНН компании
                        $("[name^='reg_company_inn']").val(suggestion.data.inn);
                    }
                    //Вернём правильный результат
                    return suggestion.value;
                }
            }));  
        }
        
        
        if (global.dadata_config.email_show_hint){
            /**
            * Включает подсказки в E-mail
            */
            $("[name^='reg_e_mail'], [name^='user_email']").suggestions($.extend(init_params, {
                suggest_local  : global.dadata_config.email_show_all ? false : true,
                type           : "EMAIL",
                /*
                * Возвращает строку для вставки в поле ввода при выборе подсказки
                */
                formatSelected: function(suggestion) {
                    //Вернём правильный результат
                    return suggestion.value;
                }
            }));  
        }
          
        
        if (global.dadata_config.city_show_hint){
            /**
            * Включает подсказки в городе
            */
            $("[name^='addr_city']").suggestions($.extend(init_params, {
                type           : "ADDRESS",
                /*
                * Возвращает строку для вставки в поле ввода при выборе подсказки
                */
                formatSelected: function(suggestion) {
                    //Вернём правильный результат
                    return suggestion.data.city;
                }
            }));  
        }    
        
        if (global.dadata_config.address_show_hint){
            /**
            * Включает подсказки в адресе, вставляет улицу, дом и квартиру
            */
            $("[name^='addr_address']").suggestions($.extend(init_params, {
                type           : "ADDRESS",
                constraints    : $("[name^='addr_city']"),
                /*
                * Возвращает строку для вставки в поле ввода при выборе подсказки
                */
                formatSelected: function(suggestion) {
                    //Получим возвращаемый адрес с адресом улицы и дома
                    var address = [];
                    address.push(suggestion.data.street_with_type);
                    console.log(suggestion.data);
                    //Если указан дом
                    if (suggestion.data.house){
                        address.push(suggestion.data.house);
                    }
                    if (suggestion.data.block_type){
                        address.push(suggestion.data.block_type+" "+suggestion.data.block);
                    }
                    if (suggestion.data.flat_type){
                        address.push(suggestion.data.flat_type+" "+suggestion.data.flat);
                    }
                    //Если есть ZIP код и поле не заполнено, то заполним его самостоятельно.
                    if (suggestion.data.postal_code.length && !$("[name^='addr_zipcode']").val().length){
                        $("[name^='addr_zipcode']").val(suggestion.data.postal_code);
                    }
                    //Вернём правильный результат
                    return address.length ? address.join(', ') : suggestion.data.value;
                }
            }));
        }
    } 
    
    //Привяжем всё
    dadataBindHints();
    
    //Если обновился контент, то заново привяжем
    /*$(window).on('new-content', function(){
        dadataBindHints();
    });*/
    
});