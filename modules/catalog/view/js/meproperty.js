/**
* Plugin, для мультиредактирования характеристик
*/
(function($){
    $.fn.multiEditProperty = function(method) {
        var defaults = {
           
           propertyBlock      : '#multipropertyblock',   //Общий див
           propertyForm       : '.property-form',        //Форма со строкой характеристики
           propertyList       : '.p-proplist',           //Список со всеми характеристиками
           propertyListVal    : '.p-list-check',         //Список с выбором списковых значений 
           propertyVal        : '.p-val',                //Значение характеристик
           propertyType       : '.p-type',               //Значение типа характеристики
           propertyDelIt      : '.p-check-list-delit',   //Значение удалить выбранную характеристику
           propertyPublic     : '.p-public',             //Значение публичный характеристики
           propertySiteId     : '.p-siteid',             //Значение характеристик
           propertyXMLId      : '.p-xmlid',              //Значение характеристики xml_id
           propertyListBlock  : '.p-proplist-block',     //Блок со списком характеристик
           propertyListBlckL  : '.p-proplist-block span',//Контейней с картинкой загрузки
           propertyRowListVal : '.p-row-list-values',    //Строка с выбором списковых значений 
           propertyRowVal     : '.p-value-block',        //Строка со значением 
           propertyRowDelIt   : '.p-row-list-delit',     //Строка с флагом удаления характеристики
           propertyClone      : '.clone-it',             //Блок для клонирования
           
           errorContainer   : '.pfield-error',         //Контейней для ошибок ответа сервера
           addButton        : '.add-proprow',          //Кнопка добавления строки с характеристикой 
           removeButton     : '.close-property',      //Удаления характеристики из списка
           adderDiv         : '#multipropertyblock .prop-insert-div',    //Див в который будет добавляться блок с  
           
           getPropertyUrl   : ''                       //Инициализируется из аттрибута data-get-property-url 
        },
        propertyListArray = false; //Объкет с массивом, для последуюшей загрузки из него
        args = arguments;
        
        return this.each(function() {
            var 
                $this = $(this), 
                data  = $this.data('multiEditProperty');
            
            //public
            var methods = {
                /**
                * Метод инициализации плагина
                */
                init: function(initoptions) 
                {                 
                    if (data) return;     
       
                    data = {}; $this.data('multiEditProperty', data);    
                    data.options = $.extend({}, defaults, initoptions);    
                    data.options.getPropertyUrl = $this.data('getPropertyUrl');
                    $this
                        .on('change', data.options.propertyList, methods.onPropertyChange)  //Выбор характеристики
                        .on('click', data.options.propertyDelIt, delit)
                        .on('click', data.options.addButton, methods.addPropertyRow)        //Добавление строки характеристик
                        .on('click', data.options.removeButton, methods.removePropertyRow); //Удаление строки характеристик
                    
                },
                /**
                * Добавление строки добавления характеристики
                * 
                */
                addPropertyRow: function ()
                {
                   //Клонируем форму и заполняем данные 
                   var listDiv = $(data.options.propertyForm+data.options.propertyClone,$this).clone().show().appendTo(data.options.adderDiv); 
                   listDiv.removeClass('clone-it');
                   //Достаём список характеристик и добавляем в select
                   loadPropertyList(listDiv);
                },
                
                /**
                * Удаление одной строки со списком характеристик
                * 
                */
                removePropertyRow: function()
                {
                   $(this).closest(data.options.propertyForm).remove(); 
                },
                
                /**
                * Выбор характеристики, подстановка значений
                * 
                */
                onPropertyChange: function ()
                {
                   var property_id = $(this).val();                              //id характеристики
                   var listDiv     = $(this).closest(data.options.propertyForm); //Контейнер характеристик
                   addFormNames(property_id,listDiv);                            //Добавляем имена данным для отправки
                   
                   if (property_id>0){ //Если, что-то выбрано
                      $(data.options.propertyRowVal,listDiv).show(); 
                   }else{
                      $(data.options.propertyRowVal,listDiv).hide();  
                      return;
                   } 
                   
                   
                   fillPropertyValues(listDiv); //Заполним загруженными значениями свойство
                }
            }
            
            //private 
            var loadPropertyList = function(listDiv)
            {
                hideErrors();
                if (propertyListArray == false){ //Если надо загрузить
                  showLoader(listDiv);
                  $.ajax({ //Запрос харатеристик
                      type: 'POST',
                      url : data.options.getPropertyUrl,
                      dataType: 'json',
                      success: function (response){
                         hideLoader(listDiv); 
                         if (typeof(response)!='object'){ //Если вернулся не объект
                             showError(listDiv,'Возвращаемый список содержит ошибку. (Тип не объект)');
                         }else{
                             fillSelectProperties(listDiv,response);
                             propertyListArray = response;
                         }
                      }
                  });
               }else{//грузим из готового объекта
                  fillSelectProperties(listDiv,propertyListArray); 
               }
            },
            
            delit = function() {
                $(this)
                    .closest(data.options.propertyForm)
                    .find('.p-value-block, .p-row-list-values')
                    .toggleClass('hidden', this.checked);
            },
            
            /**
            * Заполнение <select> со всеми характеристиками 
            * 
            * @param listDiv  - контейнер где содержится редактируемый select
            * @param response - ответ сервера с объектом характеристик 
            */
            fillSelectProperties = function(listDiv,response)
            {
               $(data.options.propertyList,listDiv).empty(); 
               $(data.options.propertyList,listDiv).append('<option value="0">Не выбрано</option>'); 
               
               //Добавим группы характеристик
               for(var i in response.groups){
                  if (typeof(response.groups[i].id)=='undefined') response.groups[i].id = 0;  
                  optgroupText = '<optgroup id="opg'+response.groups[i].id+'" data-id="'+response.groups[i].id+'" label="'
                        +response.groups[i].title+'"></optgroup>'; 
                  $(data.options.propertyList,listDiv).append(optgroupText); 
               }
               
               //Добавим характеристики
               for(var i in response.properties_sorted){
                  var group_id = response.properties_sorted[i].parent_id; //Группа 
                  optionText = 
                    '<option value="'+response.properties_sorted[i].id
                            +'" data-values="'+response.properties_sorted[i].values
                            +'" data-sortn="'+response.properties_sorted[i].sortn
                            +'" data-site_id="'+response.properties_sorted[i].site_id
                            +'" data-public="'+response.properties_sorted[i].public
                            +'" data-xml_id="'+response.properties_sorted[i].xml_id
                            +'" data-type="'+response.properties_sorted[i].type+'">'
                        +response.properties_sorted[i].title
                    +'</option>'; 
                  $('select #opg'+group_id,listDiv).append(optionText);
                  
               }
            },
            
            /**
            * Заполняет подгруженными значениями выбранное свойство
            * 
            * @param listDiv     - контейнер где содержится редактируемый select
            */
            fillPropertyValues = function(listDiv)
            {                                                                        
                hideErrors();
                var selectedProp = $(data.options.propertyList+' :selected',listDiv); //объект выбранной характеристики
                
                var type         = selectedProp.data('type');                         //тип характеристики
                var site_id      = selectedProp.data('site_id');                      //id сайта
                var public       = selectedProp.data('public');                       //публичный флаг
                var xml_id       = selectedProp.data('xml_id');                       //xml_id
                var values       = selectedProp.data('values');                       //значение или список значений
                var property_id  = $(data.options.propertyList,listDiv).val();        //id характеристики
                
                //Подготовительные действия
                $(data.options.propertyRowListVal,listDiv).hide();
                $(data.options.propertyListVal,listDiv).hide().empty();
                $(data.options.propertyRowVal,listDiv).show();
                $(data.options.propertyVal,listDiv).show();         
               
                //Заполнение значений
                $(data.options.propertyVal,listDiv).val(values);
                $(data.options.propertyType,listDiv).val(type);
                $(data.options.propertyPublic,listDiv).val(public);
                $(data.options.propertyXMLId,listDiv).val(xml_id);
                $(data.options.propertySiteId,listDiv).val(site_id);
                
                if (type=='list'){ //Если список
                   $(data.options.propertyRowVal,listDiv).hide();  
                   $(data.options.propertyRowListVal,listDiv).show();  
                   values = $("<div/>").html(values).text();
                   var listvals = values.split(/[;\n]/); //Получим массивом все значения
                   //Выведем значения, чтобы можно было отметить    
                   if (listvals.length==0) return showError(listDiv,'Не значений списка');
                   $(data.options.propertyListVal,listDiv).show();
                   
                   for(var i=0;i<listvals.length;i++){
                       var checkboxText = '<div class="list-item">'
                                +'<input type="checkbox" name="_property_['+property_id+'][check][]" value="'+listvals[i]+'"/> '
                                +'<label>'+listvals[i]+'</label></div>';
                       $(data.options.propertyListVal,listDiv).append(checkboxText); 
                   }
                }else if(type=='bool'){ //Да или нет
                   $(data.options.propertyRowVal,listDiv).hide();
                   $(data.options.propertyRowListVal,listDiv).show();    
                   var checkboxText = '<div class="list-item">'
                                +'<input value="1" type="checkbox" name="_property_['+property_id+'][value]"/> '
                                +'<label>Значение</label></div>';
                   $(data.options.propertyListVal,listDiv).append(checkboxText).show();  
                }
            },
            
            /**
            * Добавляет необходимые имена строке с характеристиками
            * @param propertyId - id формы, он же id характеристики
            * @param listDiv    - контейнер где содержится редактируемый select
            */
            addFormNames = function (propertyId,listDiv)
            {
               if (propertyId>0){
                  $(data.options.propertyList,listDiv).attr('name','_property_['+propertyId+'][id]'); 
                  $(data.options.propertyVal,listDiv).attr('name','_property_['+propertyId+'][value]');  
                  $(data.options.propertyType,listDiv).attr('name','_property_['+propertyId+'][type]');  
                  $(data.options.propertySiteId,listDiv).attr('name','_property_['+propertyId+'][site_id]');  
                  $(data.options.propertyPublic,listDiv).attr('name','_property_['+propertyId+'][public]');  
                  $(data.options.propertyXMLId,listDiv).attr('name','_property_['+propertyId+'][xml_id]');  
                  $(data.options.propertyDelIt,listDiv).attr('name','_property_['+propertyId+'][delit]');  
                  $(data.options.propertyRowDelIt,listDiv).show();
               }else{
                  $(data.options.propertyList,listDiv).removeAttr('name'); 
                  $(data.options.propertyVal,listDiv).removeAttr('name');
                  $(data.options.propertyType,listDiv).removeAttr('name'); 
                  $(data.options.propertySiteId,listDiv).removeAttr('name');
                  $(data.options.propertyPublic,listDiv).removeAttr('name');
                  $(data.options.propertyXMLId,listDiv).removeAttr('name');
                  $(data.options.propertyDelIt,listDiv).removeAttr('name');
                  $(data.options.propertyRowDelIt,listDiv).hide();
               } 
               
            }, 
            
            /**
            * Скрывает процесс загрузки
            * @param listDiv  - контейнер где содержится редактируемый select
            */
            hideLoader = function (listDiv)
            {
               $(data.options.propertyListBlckL,listDiv).hide();  
            },
            
            /**
            * Показывает процесс загрузки
            * @param listDiv  - контейнер где содержится редактируемый select
            */
            showLoader = function (listDiv)
            {
               $(data.options.propertyListBlckL,listDiv).show();  
            },
            
            /**
            * Показывает ошибку ответа сервера
            * 
            * @param listDiv   - контейнер с редактируемым списком характеристик
            * @param errorText - текст ошибки
            */
            showError = function (listDiv,errorText)
            {
               $(data.options.errorContainer,listDiv).text(errorText).show(); 
            },
            
            /**
            * Скрывает все выводимые ранее ошибки
            * 
            */
            hideErrors = function()
            {
               $(data.options.errorContainer).text('').hide(); 
            };
            
            
            if ( methods[method] ) { //Если передан метод который необходимо выпустить
                methods[ method ].apply( this, Array.prototype.slice.call( args, 1 ));
            } else if ( typeof method === 'object' || ! method ) {
                return methods.init.apply( this, args );
            }
        });
    }
})(jQuery);    

$.contentReady(function() { //Инициализируем метод
    $('#multipropertyblock').multiEditProperty();
});