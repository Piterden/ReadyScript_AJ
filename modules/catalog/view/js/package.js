
/**
* Plugin, активирующий вкладку "характеристики" у товаров
*/
(function($){
    
$.fn.packageBlock = function(method) {

    var defaults = {
        addButton: '.add-package',
        collapseButton: '.close',
        saveButton: '.add',
        packageForm: '.package-form',
        packageContainer: '.package-container > tbody',
        packageItem: '.package-item',
        packageActions: '.package-actions',
        packageListItems: '.package-list-items > tbody',
        successText: '.success-text',
        addLine: '.add-line',
        removeLine: '.del-line',
        typeHelp: {
            list: '.type-list',
            string: '.type-string'
        },
        list: {
            znak: ['+','-','='],
            type: []
        },
        blocks: {
            values: '.p-values-block'
        },
        formFields: {
            title: '.p-title',
            type: '.p-type',
            valName: '.p-val-name',
            valZnak: '.p-val-znak',
            val: '.p-val',
            valType: '.p-val-type',
            valCode: '.p-val-code'
        },
        inputs: {
            title: '.h-title',
            type: '.h-type',
            val: '.h-val'
        },
        tools: {
            edit: '.edit',
            del: '.delete'
        }
    }, 
    args = arguments;
    
    return this.each(function() {
        var $this = $(this), options;
        var methods = {
            init: function(initoptions) {
                if ($this.data('packageBlock')) return;
                $this.data('packageBlock', {});
                options = $.extend({}, defaults, initoptions);
                
                var curr = $(options.packageForm, $this).data('baseCurrency');
                options.list.type = [curr, '%'];
                
                $this
                    .on('click', options.addButton, methods.addPackage)
                    .on('click', options.collapseButton, methods.collapseForm)
                    .on('click', options.tools.edit, editPackage)
                    .on('click', options.tools.del, deletePackage)
                    .on('click', options.addLine, addLine)
                    .on('click', options.removeLine, removeLine)
                    .on('change', options.formFields.type, changeType)
                    .on('click', options.saveButton, savePackage);
                    
                $(options.packageContainer, $this).bind('new-content', function() {
                    bindSortable();
                });
                bindSortable();
            },
            
            addPackage: function() {
                if ($(this).hasClass('act')) {
                    methods.collapseForm();
                } else {
                    $(this).addClass('act');
                    cancelEditPackage();
                    methods.expandForm();
                    $(options.formFields.type, $this).trigger('change');                        
                }
                return false;
            },
            
            expandForm: function() {
                $form = $(options.packageForm, $this);
                $form.show();
                $this.trigger('contentSizeChanged');
            },
            
            collapseForm: function() {
                cancelEditPackage();
                cleanErrors();
                $(options.packageActions+' .act', $this).removeClass('act');
                $this.trigger('contentSizeChanged');
                return false;
            }
        }
        
        //private 
        
        var cancelEditPackage = function() {
            
            var inEditNow = $('.now-edit', $this);
            
            $(options.packageListItems).empty();
            $(options.formFields.title, $this).val('');
            $(options.formFields.type, $this).val('list');
            
            $(options.packageForm, $this).data('packageEditMode', false).hide();
            if (inEditNow.removeClass('now-edit noover').length) {
                $(options.packageForm, $this).insertAfter($(options.packageActions, $this));
                inEditNow.next('.edit-form').remove();
            }
        },
        
        bindSortable = function() {
             $(options.packageContainer, $this).tableDnD({
                dragHandle: "sort-td",
                onDragClass: "in-drag"
            });
        },
        
        cleanErrors = function() {
            var $form = $(options.packageForm, $this);
            $('.field-error', $form).hide();
            $('.has-error', $form).removeClass('has-error');
        },
        
        savePackage = function() {
            var $form = $(options.packageForm, $this);
            edit_mode = $form.data('packageEditMode');
            
            var item = {
                title: $(options.formFields.title, $form).val(),
                type: $(options.formFields.type, $form).val(),
                type_str: $(options.formFields.type+' option:selected', $form).text(),
            };
            
            cleanErrors();
            
            if (item.type == 'list') {
                item.values = [];
                $(options.packageListItems+' .line', $form).each(function() {
                    var one = {
                        name: $(options.formFields.valName, this).val(),
                        znak: $(options.formFields.valZnak, this).val(),
                        val: $(options.formFields.val, this).val(),
                        type: $(options.formFields.valType, this).val(),
                        code: $(options.formFields.valCode, this).val()
                    }
                    
                    one.name = one.name.split('#').join('-'); //заменяем # на -
                    
                    if (one.name == '') {
                        $(options.formFields.valName, this).addClass('has-error');
                        $('.field-error', this).show();
                    }
                    item.values.push(one);                        
                });
            }
            
            //Проверка ошибок
            if (item.title == '') {
                $(options.formFields.title, $form).addClass('has-error');
                addError($('.field-error[data-field="title"]', $this), lang.t('Название не может быть пустым'));
                hasError = true;
            }
            
            if (!$form.find('.has-error').length) {
                methods.collapseForm();
                if (edit_mode) {
                    $form.data('packageItem').replaceWith( $(tmpl('tmpl-value-line', item)) );
                } else {
                    $(options.packageContainer, $this).append(tmpl('tmpl-value-line', item));
                    $(options.successText, $this).fadeIn();
                    setTimeout(function() {
                        $(options.successText, $this).fadeOut();
                    }, 7000);                        
                }
                $(options.packageContainer, $this).trigger('new-content');
            }
        },
        
        addError = function(element, message) {
            $(element).html('<span class="text"><i class="cor"></i>'+message+'</span>').show();
        },
        
        editPackage = function() {
            var item = $(this).closest(options.packageItem);
            var needOpen = !item.hasClass('now-edit');
            
            methods.collapseForm();
            
            if (needOpen) {
                var editform = $('<tr class="edit-form noover"><td colspan="5"><div class="bordered"></div></td></tr>');
                item.addClass('now-edit noover').removeClass('over');
                var $form = $(options.packageForm, $this).data({
                    'packageEditMode': true,
                    'packageItem': item
                });
                editform.insertAfter(item).find('.bordered').append($form);
                methods.expandForm();

                //Заполняем форму
                var values = {
                    title: $(options.inputs.title, item).val(),
                    type: $(options.inputs.type, item).val(),
                }
                $(options.inputs.val, item).each(function() {
                    var vals = $(this).val().split('#');
                    addLine(null, {
                        name: vals[0],
                        znak: vals[1],
                        val: vals[2],
                        type: vals[3],
                        code: vals[4]
                    });
                });
                
                $(options.formFields.title, $this).val(values.title);
                $(options.formFields.type, $this).val(values.type);
                
                $(options.formFields.type, $this).trigger('change');
                
                
                //$(options.formFields.propertyList).val(item.data('propertyId')).trigger('change');
            }
        },
        
        deletePackage = function() {
            if (confirm(lang.t('Вы действительно хотите удалить комплектацию?'))) {
                var $item = $(this).closest(options.packageItem);                    
                if ($item.hasClass('now-edit')) methods.collapseForm();                    
                $(this).closest(options.packageItem).remove();
            }
        },
        
        addLine = function(e, data) {
            if (!data) {
                data = {};
            }
            
            if (!data.val) data.val = '0';
            
            data.list = options.list;
            data.isFirst = $(options.packageListItems+'>*', $this).length == 0;
            $(options.packageListItems, $this).append( $(tmpl("tmpl-line", data)) );
        },
        
        removeLine = function() {
            $(this).closest('.line').remove();
        },
        
        changeType = function() {
            if ($(this).val() == 'list') {
                $(options.typeHelp.list+','+options.blocks.values, $this).show();
                $(options.typeHelp.string, $this).hide();
                
                if (!$(options.packageListItems+'>*', $this).length) {
                    addLine();
                }
            } else {
                $(options.typeHelp.list+','+options.blocks.values, $this).hide();
                $(options.typeHelp.string, $this).show();
            }
        }
                        
        if ( methods[method] ) {
            methods[ method ].apply( this, Array.prototype.slice.call( args, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, args );
        }
    });
}
})(jQuery);

$.contentReady(function() {
    $('#packageblock').packageBlock();
});