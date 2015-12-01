    /**
    * Plugin, активирующий вкладку "характеристики" у товаров
    */
(function($){
    $.fn.propertyBlock = function(method) {
        var defaults = {
            addButton: '.add-property',
            someAddButton: '.add-some-property',
            collapseButton: '.close',
            saveButton: '.property-table .add',
            someSaveButton: '.some-property-table .add-some',
            propertyForm: '.property-form',
            somePropertyForm: '.some-property-form',
            propertyActions: '.property-actions',
            propertyList: '.property-container',
            propertyLoading: '.ploading',
            propertyItem: '.property-item',
            successText: '.success-text',
            setSelfVal: '.set-self-val',
            errors: {
                title: '.p-title-block .field-error'
            },
            blocks: {
                title: '.p-title-block',
                proplist: '.p-proplist-block',
                values: '.p-values-block',
                val: '.p-val-block',
                value: '.p-value-block',
                group: '.p-group-block'
                
            },
            formFields: {
                propertyList: '.p-proplist',
                parent: '.p-parent_id',
                title: '.p-title',
                type: '.p-type',
                unit: '.p-unit',
                values: '.p-values',
                val: '.p-val',
                step: '.p-step',
                newGroup: '.p-new-group',
                useVal: '.h-useval',
                uValue: '.h-val, .h-val-linked',
                hPublic: '.h-public',
                someProps: '.some-props'
            },
            tools: {
                edit: '.p-edit',
                del: '.p-del'
            },
            getPropertyUrl: '' //Инициализируется из аттрибута data-get-property-url
            
        }, 
        fullPropertyList,
        whenListLoad = $.Deferred(),
        args = arguments;
        
        return this.each(function() {
            var $this = $(this), options;
            
            var methods = {
                init: function(initoptions) {                    
                    if ($this.data('propertyBlock')) return false;
                    $this.data('propertyBlock', {});
                    options = $.extend({}, defaults, initoptions);
                    options.getPropertyUrl = $this.data('getPropertyUrl');
                    $this
                        .on('click', options.addButton, methods.addProperty)
                        .on('click', options.collapseButton, methods.collapseForm)
                        .on('change', options.formFields.propertyList, onPropertySelect)
                        .on('change', options.formFields.type, onTypeChange)
                        .on('change', options.formFields.values, onValuesChange)
                        .on('keyup', options.formFields.newGroup, onNewGroupChange)
                        .on('click', options.saveButton, onSave)
                        .on('change', options.formFields.useVal, onUseValChange)
                        .on('click', options.tools.edit, editProperty)
                        .on('click', options.tools.del, deleteProperty)
                        .on('click', options.someAddButton, methods.addSomeProperty)
                        .on('click', options.someSaveButton, onInsertSome)
                        .on('click', options.setSelfVal, onSetSelfVal);
                        
                    $(options.formFields.useVal, $this).each(onUseValChange);
                },
                
                addProperty: function() {
                    var hasAct = $(this).hasClass('act');
                    methods.collapseForm();                    
                    if (!hasAct) {
                        $(this).addClass('act');                        
                        cancelEditProperty();
                        $(options.formFields.propertyList).val('new').trigger('change');
                        methods.expandForm();
                    }
                    $this.trigger('contentSizeChanged');
                    return false;
                },
                
                addSomeProperty: function() {
                    var hasAct = $(this).hasClass('act');
                    methods.collapseForm();
                    if (!hasAct) {
                        $(this).addClass('act');
                        cancelEditProperty();
                        loadPropertyList();
                        
                        $(options.somePropertyForm, $this).show();
                        $this.trigger('contentSizeChanged');
                    }                    
                    
                },
                
                expandForm: function() {
                    $form = $(options.propertyForm, $this);
                    loadPropertyList();
                    $form.show();
                    $this.trigger('disableBottomToolbar', 'property-edit');
                },
                
                collapseForm: function() {
                    cancelEditProperty();
                    $(options.propertyActions+' .act').removeClass('act');
                    $(options.somePropertyForm, $this).hide();
                    $this.trigger('enableBottomToolbar', 'property-edit');
                    return false;
                }
            }
            
            //private 
            
            /**
            * Загружаем список свойств
            */
            var loadPropertyList = function()
            {
                if (whenListLoad.state() != 'resolved' || !fullPropertyList) {
                    $(options.propertyLoading).show();
                    $(options.formFields.propertyList).prop('disabled', true);
                    $(options.formFields.parent).prop('disabled', true);
                    $(options.formFields.newGroup).prop('disabled', true);
                    
                    initList(function() {
                        $(options.propertyLoading).hide();
                        $(options.formFields.propertyList).prop('disabled', false);
                        $(options.formFields.parent).prop('disabled', false);
                        $(options.formFields.newGroup).prop('disabled', false);                        
                        
                        fillSelect();
                        whenListLoad.resolve();
                    });
                }
            },
            
            initList = function(callback)
            {
                $.ajaxQuery({
                    url: options.getPropertyUrl,
                    success: function(response) {                        
                        fullPropertyList = response;
                        callback.call();                        
                    }
                });
            },
            
            fillSelect = function()
            {
                //Загружаем значения в список
                var select = $(options.formFields.propertyList, $this).empty();
                var group_select = $(options.formFields.parent, $this).empty();
                var cur_group;
                var optgroup;
                
                select.append('<option value="new">Новая характеристика</option>');
                group_select.append('<option value="0">Без группы</option>');
                
                //Генерируем корректно отсортированный список характеристик
                fullPropertyList.properties = {};
                for(var i in fullPropertyList.properties_sorted) 
                {
                    var item = fullPropertyList.properties_sorted[i];
                    fullPropertyList.properties[item.id] = item;
                    
                    if (!cur_group || item.parent_id != cur_group) {
                        cur_group = item.parent_id;
                        optgroup = $('<optgroup></optgroup>').attr({
                                        label: fullPropertyList.groups[cur_group].title
                                    }).appendTo(select);
                    }
                    $('<option></option>').attr('value', item.id).text(item.title).appendTo(optgroup);
                }
                
                group_select.html('');
                for (i in fullPropertyList.groups) {
                    var item = fullPropertyList.groups[i];
                    $('<option></option>').attr('value', i).text(item.title).appendTo(group_select);
                }
                
                //Если присутствует множественная вставка свойств, то заполняем и её
                var $someSelect = $(options.formFields.someProps, $this);
                if ($someSelect.length) {
                    select.children().clone().appendTo($someSelect.empty());
                    $('option[value="new"]:first', $someSelect).remove();
                    $someSelect.removeAttr('disabled');
                }
                
                $(options.saveButton, $this).removeClass('disabled');
                $(options.someSaveButton, $this).removeClass('disabled');
            },
            
            onNewGroupChange = function()
            {
                $(options.formFields.parent).prop('disabled', $(this).val() != '');
                
            },
            
                
            decode = function(encodedStr) {
                return $("<div/>").html(encodedStr).text();
            },             
            
            onPropertySelect = function()
            {
                var index = $(this).val();
                if (index == 'new' || typeof(fullPropertyList) == 'undefined' ) {
                    var params = {
                        title: '',
                        type: '',
                        values: '',
                        unit: '',
                    }
                } else {
                    var params = fullPropertyList.properties[index];
                };

                //Устанавливаем значения в диалоговом окне
                $(options.formFields.title, $this).val( decode(params.title) );
                $(options.formFields.type, $this).val( params.type );
                $(options.formFields.values, $this).val( decode(params.values) );
                $(options.formFields.unit, $this).val( decode(params.unit) );
                $(options.formFields.step, $this).val( params.step );
                $(options.formFields.parent, $this).val( params.parent_id );
                
                if (index == 'new') {
                    //Режим создания нового свойства
                    $(options.blocks.value+','+options.blocks.title+','+options.blocks.group, $this).show();
                    //$(options.formFields.type +','+ options.formFields.parent +','+ options.formFields.title, $this).removeAttr('disabled');
                    $(options.blocks.proplist+','+options.blocks.group , $this).show();                    
                } else {
                    if ($(options.propertyForm, $this).data('propertyEditMode')) {
                        //Режим редактирования
                        $(options.blocks.title).show();
                        $(options.blocks.value+','+options.blocks.proplist+','+options.blocks.group , $this).hide();
                        $(options.formFields.type, $this).removeAttr('disabled');
                    } else {
                        //Режим добавления
                        $(options.blocks.title+','+options.blocks.group, $this).hide();
                        //$(options.formFields.type+','+options.formFields.parent, $this).attr('disabled','disabled');
                    }
                }
                
                onTypeChange();
            },
            
            onTypeChange = function()
            {                     
                var el = $(options.formFields.type, $this);
                if (el.val() == 'list') {
                    $(options.blocks.values, $this).show();
                } else { 
                    $(options.blocks.values, $this).hide();
                    $(options.formFields.values, $this).val('');
                }
                
                var new_input = getInputByType(el.val());
                
                if (new_input) {
                    $(options.blocks.val, $this).empty().append(new_input);
                    if (el.val() == 'list') onValuesChange();
                }
            },

            getInputByType = function(type, value)
            {
                if (type == 'bool') {
                    var val_input = $('<input type="checkbox" value="1" class="p-val">');
                } else if (type == 'list') {
                    var val_input = $('<select class="p-val"></select>');
                } else {
                    var val_input = $('<input class="p-val" type="text">');
                }
                
                return val_input;
            },
            
            onValuesChange = function()
            {
                var values = $(options.formFields.values, $this).val();
                var values_arr = values.split(/[;\n]/);
                var val_input = '';
                
                //Собираем текущие значения
                var checked = {};
                $(options.blocks.val).find('input:checked').each(function() {
                    checked[$(this).val()] = true;
                });
                
                $(options.blocks.val, $this).empty();
                if (values != '') {
                    for(var key in values_arr) {
                        var value = trim(values_arr[key]);
                        var input = $('<input type="checkbox" class="p-val">').attr({
                            id: 'pv_'+key,
                            value: value,
                            checked: (typeof(checked[value]) != 'undefined')
                        });
                        
                        var label = $('<label></label>').attr({
                            'for': 'pv_'+key,
                        }).text(value);
                        
                        $(options.blocks.val, $this).append(
                            $('<div class="list-item"></div>').append(input, label)
                        );
                    }
                } else {
                    $(options.blocks.val, $this).append('Укажите возможные значения')
                }
            },
            
            onInsertSome = function()
            {
                if ($(this).hasClass('disabled')) return false;
                
                var ids = [];
                $(options.formFields.someProps+' option:selected', $this).each(function() {
                    ids.push({
                        name: 'ids[]',
                        value: $(this).val()
                    });
                });
                
                $.ajaxQuery({
                    url: $this.data('getSomeProperties'),
                    type:'POST',
                    data: ids,
                    success: function(response) {
                        for(var i in response.result) {
                            if (!$(options.propertyItem+'[data-property-id="'+response.result[i].prop.id+'"]', $this).length) {
                                if (response.result[i].group.length == 0) {
                                    response.result[i].group.id = 0;
                                }
                                
                                var target_group = $('tbody[data-group-id="'+response.result[i].group.id+'"]', $this);
                                
                                if ( target_group.length ) {
                                    target_group.append(response.result[i].property_html);
                                } else {
                                    $(options.propertyList, $this).append( response.result[i].group_html );
                                    $('tbody[data-group-id="'+response.result[i].group.id+'"]', $this)
                                        .append(response.result[i].property_html)
                                        .trigger('new-content');
                                }
                                
                            }
                        }
                    }
                });
            },
            
            onSave = function()
            {
                if ($(this).hasClass('disabled')) return false;
                
                var $form = $(options.propertyForm, $this);
                var $item = $form.data('propertyItem'), $context,
                edit_mode = $form.data('propertyEditMode');
                
                if (edit_mode) {
                    val_class = '.h-val'; $context = $item;
                } else {
                    val_class = '.p-val'; $context = $form;
                }
                
                var item = {
                    title:          $(options.formFields.title, $form).val(),
                    type:           $(options.formFields.type, $form).val(),
                    values:         $(options.formFields.values, $form).val(),
                    value:          $(val_class, $context).val(),
                    unit:           $(options.formFields.unit, $form).val(),
                    step:           $(options.formFields.step, $form).val(),
                    parent_id:      $(options.formFields.parent, $form).val(),
                    new_group_title: $(options.formFields.newGroup, $form).val()
                },val_class
                
                var id = $(options.formFields.propertyList, $form).val();
                item.id = (id == 'new') ? 0 : id;
                item.is_my = 1;
                item.owner_type = $this.data('ownerType');
                
                if (edit_mode) {
                    item['public'] = $(options.formFields.hPublic+':checked', $item).length>0 ? 1 : 0;
                    item['useval'] = $(options.formFields.useVal+':checked', $item).length>0 ? 1 : 0;
                }
                
                if (id > 0) {
                    var exists = $(options.propertyItem+'[data-property-id="'+id+'"]', $this);
                    if (exists.length) {
                        item.is_my = exists.data('isMy'); //Определяем какой шаблон нам должен вернуть сервер
                    }
                }
                
                $(options.formFields.title, $form).removeClass('has-error');
                $(options.errors.title, $form).hide();

                
                if (item.type == 'bool') {
                    var checked = $(val_class+':checked', $context).length > 0;
                    item.value = (+checked);
                }
                
                if (item.type == 'list') {
                    var val_list = new Array();
                    $(val_class+':checked', $context).each(function() {
                        val_list.push($(this).val());
                    });
                    item.value = val_list;
                }                
                
                $.ajaxQuery({
                    url: $this.data('savePropertyUrl'),
                    type:'POST',
                    data: {
                        item: item
                    },
                    success: function(response) {
                        if (response.success) {
                            methods.collapseForm();                            
                            //Обновляем характеристику в списке
                            if (response.group.length == 0) {
                                response.group.id = 0;
                            }
                            
                            if ($('.property-item[data-property-id="'+response.prop.id+'"]', $this).length) {
                                $('.property-item[data-property-id="'+response.prop.id+'"]', $this).replaceWith(response.property_html);
                            } else {
                                var target_group = $('tbody[data-group-id="'+response.group.id+'"]', $this);
                                if ( target_group.length ) {
                                    target_group.append(response.property_html);
                                } else {
                                    $(options.propertyList, $this).append( response.group_html );
                                    $('tbody[data-group-id="'+response.group.id+'"]', $this)
                                        .append(response.property_html)
                                        .trigger('new-content');
                                }
                            }
                            
                            if (!edit_mode) {
                                $(options.successText, $this).fadeIn();
                                setTimeout(function() {
                                    $(options.successText, $this).fadeOut();
                                }, 7000);
                                
                            }

                            if (!fullPropertyList.properties[response.prop.id]) {
                                for(var j in fullPropertyList.properties_sorted) {
                                    if (fullPropertyList.properties_sorted[j].parent_id == response.prop.parent_id) {
                                        fullPropertyList.properties_sorted.splice(j, 0, response.prop);
                                        break;
                                    }
                                }                            
                            }
                            
                            if (response.group.id>0) {
                                fullPropertyList.groups[response.group.id] = response.group;
                            }
                            fullPropertyList.properties[response.prop.id] = response.prop;
                            

                            
                            fillSelect(); //Обновляем значения в сисках

                            $(options.formFields.useVal, $('.property-item[data-property-id="'+response.prop.id+'"]', $this)).each(onUseValChange);
                        } else {
                            for (var field in response.formdata.errors) {
                                
                                var errors_str = response.formdata.errors[field].errors.join('<br>');
                                if (field == '@system') field = 'title';
                                
                                $('.p-'+field, $this).addClass('has-error');
                                $('.field-error[data-field="'+field+'"]', $this).html(
                                    '<span class="text"><i class="cor"></i>'+errors_str+'</span>'
                                ).show();
                            }
                            $form.trigger('new-content');
                        }
                    }
                })
                
            },
            
            onUseValChange = function()
            {
                var item = $(this).closest('.property-item');
                if ($(this).prop('checked')) {
                    $(options.formFields.uValue, item).removeAttr('disabled');
                } else {
                    $(options.formFields.uValue, item).attr('disabled','disabled');
                }
            },
            
            deleteProperty = function()
            {
                if (confirm('Вы действительно хотите удалить характеристику?'))
                {
                    cancelEditProperty();
                    var div = $(this).closest(options.propertyItem);
                    var my_group = div.closest('tbody');
                    div.remove();
                    
                    if (!my_group.children().length) {
                        var group_head = my_group.prev('.group-body[data-gid]').remove();
                        my_group.remove();
                    }                   
                }
            },
            
            cancelEditProperty = function()
            {
                var inEditNow = $('.now-edit', $this);
                $(options.propertyForm, $this).data('propertyEditMode', false).hide();
                $('.has-error', $this).removeClass('has-error');
                $('.field-error', $this).empty().hide();
                
                if (inEditNow.removeClass('now-edit noover').length) {
                    $(options.propertyForm, $this).insertAfter($(options.propertyActions, $this));
                    inEditNow.next('.edit-form').remove();
                }
            },
            
            editProperty = function()
            {
                var item = $(this).closest(options.propertyItem);
                var needOpen = !item.hasClass('now-edit');
                
                methods.collapseForm();
                
                if (needOpen) {
                    var editform = $('<tr class="edit-form noover"><td colspan="6"><div class="bordered"></div></td></tr>');
                    item.addClass('now-edit noover').removeClass('over');
                    var $form = $(options.propertyForm, $this).data({
                        'propertyEditMode': true,
                        'propertyItem': item
                    });
                    editform.insertAfter(item).find('.bordered').append($form);
                    methods.expandForm();

                    whenListLoad.done(function() {
                        $(options.formFields.propertyList).val(item.data('propertyId')).trigger('change');
                    });
                }
                
            },
            trim = function ( str, charlist ) 
            {
                charlist = !charlist ? ' \\s\\xA0' : charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\$1');
                var re = new RegExp('^[' + charlist + ']+|[' + charlist + ']+$', 'g');
                return str.replace(re, '');
            },
            onSetSelfVal = function()
            {
                $(options.formFields.useVal, $this).prop('checked', true).trigger('change');
                return false;
            };
                            
            
            if ( methods[method] ) {
                methods[ method ].apply( this, Array.prototype.slice.call( args, 1 ));
            } else if ( typeof method === 'object' || ! method ) {
                return methods.init.apply( this, args );
            }
        });
    }
})(jQuery);    

$.contentReady(function() {
    $('#propertyblock').propertyBlock();
});