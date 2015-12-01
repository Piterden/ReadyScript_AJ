/**
* Плагин, инициализирующий работу фильтров
*/
(function( $ ){
    $.fn.productFilter = function( method ) {
        var defaults = {
            targetList       : '#products', //Селектор блока, в котором отображаются товары
            form             : '.filters', //Селектор формы которая будет отправляться
            multiSelectBlock : '.typeMultiselect', //Селектор обёртки множественного фильтра
            multiSelectRow   : 'li', //Селектор обёртки одного фильтра
            submitButton     : '.submitFilter',
            cleanFilter      : '.cleanFilter',    
            
        },
        args = arguments,
        timer;
        
        return this.each(function() {
            var $this = $(this), 
                data = $this.data('productFilter');

            var methods = {
                init: function(initoptions) {
                    if (data) return;
                    data                    = {}; $this.data('productFilter', data);
                    data.options            = $.extend({}, defaults, initoptions);
                    data.options.baseUrl    = $(data.options.form).attr('action');
                    data.options.cleanState = $(data.options.form).serializeArray();
                    
                    $(window).on('popstate', returnPageFilterFromFilter); //Функция возврата на предыдущую страницу по ajax через браузер
                    $(data.options.cleanFilter).click(methods.cleanFilters);
                    $('input[type="text"], input[type="hidden"], select', $this).each(function() {
                        $(this).data('lastValue', $(this).val());    
                    });
                                                                  
                    setMultiSelectStartPositions(); //Выставляем позиции по умолчанию
                    
                    bindChanges();      
                    changeMultiSelectCheckedRowsPosition();

                    $(data.options.submitButton).hide();                        
                },
                
                applyFilters: function(e, noApply) {
                    if (noApply) return false;
                    var newValues = $(data.options.form).serializeArray();     
                    //Исключаем из фильтра элементы, неустановленные элементы
                    var readyValues = [];
                    for(var key in newValues) {
                        var field = $('[name="' +  newValues[key].name + '"][data-start-value]', $this);
                        if (!field.length || field.data('startValue') != newValues[key].value) {
                            readyValues.push(newValues[key]);
                        }
                    }
                    
                    url = buildFilterUrl(readyValues); //ссылка для истории браузера
                    // заносим ссылку в историю
                    history.pushState(readyValues, null, url );
                    //Сменим позиции мульти выбора у выбранных элементов
                    clearTimeout(timer);                    
                    timer = setTimeout(function() {
                        changeMultiSelectCheckedRowsPosition(); 
                    }, 300);
                    
                    // выполним запрос
                    methods.queryFilters(readyValues);      
                    return false;
                },
                /**
                * Запрос результата применения фильтров
                * 
                * @param newValues - массив объектов запроса
                */
                queryFilters: function(newValues){
                   $this.addClass('inLoading'); 
                   $(data.options.cleanFilter, $this).toggleClass('hidden', newValues.length == 0);
                    
                   $.ajax({
                       url: data.options.baseUrl,
                       dataType:'json',
                       data: newValues,
                       success: function(response) {
                            $(data.options.targetList).html(response.html).trigger('new-content');
                            $this.removeClass('inLoading');   
                            $this.trigger('filters.loaded');                    
                       }
                   }); 
                },
                
                cleanFilters: function(e, noApply) {
                    $('input[type="text"], input[type="hidden"], select', $this).each(function() {
                        $(this).val( $(this).data('startValue') ? $(this).data('startValue') : "" ).trigger('change', true);
                    });
                    $('input[type="checkbox"]', $this).prop('checked', false).trigger('change', true);
                    if (!noApply) methods.applyFilters();
                    //Сменим позиции мульти выбора у выбранных элементов
                    changeMultiSelectCheckedRowsPosition(); 
                    return false;
                }
            };        
            
            //private 
            /**
            * Строит url для подстановки в строку браузере для Ajax запроса
            * Возвращает подготовленный url
            * 
            * @param array valuesArray - массив объектов параметров запросов фильтров
            * @return string
            */
            var buildFilterUrl = function(valuesArray){
                //Разберём для построения
                queryStr = "";                 //Строка запроса
                valcnt   = valuesArray.length; //Количество фильтров    
                
                for(nval in valuesArray){      //Составим запрос
                    queryStr += valuesArray[nval]['name']+"="+encodeURIComponent(valuesArray[nval]['value']);
                    if (nval<valcnt-1){
                       queryStr += "&"; 
                    }
                }
                url = "//"+document.location.hostname+document.location.pathname;  //url для подстановки в браузер
                if (valcnt>0){
                   url +="?"+queryStr; 
                }
                
                return url;
            };         
            
            /**
            * Возвращается через AJAX на страницу с прошлым фильтром, если таковая имеется в истории браузера.
            * 
            */
            var returnPageFilterFromFilter = function()
            {
                methods.cleanFilters(null, true);
                var params = history.state ? history.state : [];
                $(params).each(function(i, value) {
                    setFilterParam(value);
                });
                methods.queryFilters(params);
            };   
            
            /**
            * Устанавливает в HTML форме фильтров значения из переданного объекта
            * @param filter_obj - объект со значниями фильтра
            */
            var setFilterParam = function(filter_obj){
                var filter = $("[name='"+filter_obj.name+"']",data.options.form);
                tagName    = filter[0].tagName.toLowerCase(); 
                switch(tagName){
                    case "select":
                        $('option',filter).prop('selected',false);
                        $("select[name='"+filter_obj.name+"'] option[value='"+filter_obj.value+"']").prop('selected',true);
                        break;
                    
                    case "input":
                        if (filter.length>1){ //Если несколько объектов подходящих(checkbox)
                            //То выберем нужный
                            filter = $("[name='"+filter_obj.name+"'][value='"+filter_obj.value+"']",data.options.form);                           
                        } 
                        var type = filter.attr('type').toLowerCase();
                        
                        switch (type){
                            case "checkbox":    //checkbox
                                filter.prop('checked', true);
                                break;
                                
                            default:   //Текстовое поле
                                filter.val(filter_obj.value);       // Если просто input
                                break;
                        }
                        break;
                        
                    default:
                        filter.val(filter_obj.value);
                        break;
                }
                filter.trigger('change', true);
            };    
            
            /**
            * Устанавливает стартовую позицию всем элементам в мультивыборе галочек
            * 
            */
            var setMultiSelectStartPositions = function(){
               // Если блоки есть 
               $(data.options.multiSelectBlock, $this).each(function(){
                   $(data.options.multiSelectRow, $this).each(function(){
                       $(this).data('start-position', $(this).index());
                   });
               });
            };  
            
            /**
            * Меняет позиции выбранным элементам в блоках с мультивыбором
            * 
            */
            var changeMultiSelectCheckedRowsPosition = function (){
               // Если блоки есть 
               $(data.options.multiSelectBlock, $this).each(function(){
                   var have_checked = false;
                   var block        = $(this);       
                   $('input', $(this)).each(function(){
                        var wrapper = $(this).closest(data.options.multiSelectRow); //Обёртка
                        if ($(this).prop('checked')){ //Если установлена галочка
                           have_checked = true; 
                           wrapper.insertBefore($(data.options.multiSelectRow+":eq(0)", block));
                        }
                   });
                   //Если выбранных нет, то вернём всё по своим местам, как было сначала.
                   if (!have_checked){
                        $(data.options.multiSelectRow, block).each(function(){
                            var position = $(this).data('start-position'); //Первоначальная позиция
                            $(this).insertBefore($(data.options.multiSelectRow+":eq("+position+")", block)); 
                        });
                   }
               }); 
            };

            /**
            * Фиксирует факт изменения параметров в фильтрах и вызывает метод applyFilters
            */
            var 
            bindChanges = function() {
                $(data.options.form).submit(methods.applyFilters);
                $('select, input[type="checkbox"], input[type="hidden"]', $this).change(methods.applyFilters);
                $('input[type="text"]', $this).keyup(function(e) {
                    clearTimeout(this.keyupTimer);
                    if (e.keyCode == 13) {
                        return;
                    }                    
                    this.keyupTimer = setTimeout(function() {
                         methods.applyFilters();
                    }, 500);
                });
            };
            
            if ( methods[method] ) {
                methods[ method ].apply( this, Array.prototype.slice.call( args, 1 ));
            } else if ( typeof method === 'object' || ! method ) {
                return methods.init.apply( this, args );
            }
        });
    }

})( jQuery );


$(function() {
    $('.filterSection').productFilter();
});