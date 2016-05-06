/**
* JavaScript API. Необходимы для административной части и режима отладки сайта
*/

$.extend({
    /**
    * Вызывает callback, когда весь DOM загружен и когда DOM обновлен.
    */
    contentReady: function(callback) {
        if (callback) {
            $(function() {
                if (typeof($LAB) == 'undefined' || !$LAB.loading) {
                    callback.call($('body').get(0));
                }
            });
            $(window).bind('new-content', function(e) {
                var _this = e.target;
                if ($LAB.loading) {
                    $(window).one('LAB-loading-complete', function(e) {
                        callback.call(_this);
                    });
                } else {
                    callback.call(_this);
                }
            });
        }
    },
    /**
    * Вызывается, когда загружен весь DOM и динамически загружены все скрипты
    * Расширяет стандартный $(document).ready
    */
    allReady: function(callback) {
        $(function() {
            if ($LAB.loading) {
                var _this = this;
                $(window).one('LAB-loading-complete', function(e) {
                    callback.call(_this);
                });
            } else {
                callback.call(this);
            }
        });
    },
    
    /**
    * jQuery.ajax с настроенными обработчиками по-умолчанию
    */
    ajaxQuery: function(options) {
        options = $.extend({
            loadingProgress: true,
            checkAuthorization: true,
            dataType: 'json'
        }, options);
        
        var clone_options = jQuery.extend({}, options);
        return $.ajax( $.extend(clone_options, 
        {
            beforeSend: function(jqXHR, settings) {
                if (options.loadingProgress) loading.show();
                if (options.beforeSend) options.beforeSend.call(this, jqXHR, settings);
            },
            success: function(data, textStatus, jqXHR) {
                if (options.dataType == 'json' && options.checkAuthorization) {
                    checkAuthorization(data);
                }
                if (options.dataType == 'json') {
                    checkWindowRedirect(data);
                    checkMessages(data);
                }                
                if (options.success) options.success.call(this, data, textStatus, jqXHR);
            },
            complete: function(jqXHR, textStatus) {
                if (options.loadingProgress) loading.hide();
                if (options.complete) options.complete.call(this, jqXHR, textStatus);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                if (options.loadingProgress) loading.error();
                if (options.error) options.error.call(this, jqXHR, textStatus, errorThrown);
            }
        }));
    }
    
});

$.fn.extend({
    
    /**
    * Активирует кнопки сортировки в диалоге настройки таблицы
    */
    tableOptions: function() {
            var $context = this;
            $('.ch-sort', $context).off('click').on('click', function() {
                var parent = $(this).parent;
                var set_sortn;
                $('.current-sort', $context).remove();
                if ($(this).hasClass('asc') && $(this).data('canBe') != 'ASC') {
                        set_sortn = 'desc';
                }
                if ($(this).hasClass('desc') && $(this).data('canBe') != 'DESC') {
                    set_sortn = 'asc';
                }
                if ($(this).hasClass('no')) {
                    if ($(this).data('canBe') != 'BOTH') {
                        set_sortn = $(this).data('canBe').toLowerCase();
                    } else {
                        set_sortn = 'asc';
                    }
                }
                if (set_sortn) {
                    $('.ch-sort', $context).removeClass('asc desc').addClass('no');
                    $(this).removeClass('no').addClass(set_sortn);
                }
            });
        },
        
    /**
    * Активирует растягивание колонки. Навешивается на левую колонку
    */
    columnResizer: function(method) {
        var defaults = {
            domHandler: '<div class="column-resizer"><div class="handler"></div></div>',
            handler: '.handler',
            marginTop: -26,
            leftColumnClass: '.left-column',
            leftMaxWidth: '50%',
            leftMinWidth: '300',
            rightColumnClass: '.right-column',
            linkLeft: '.bottomToolbar .left-column',
            linkRight: '.bottomToolbar .right-column',
            rightMarginCorrect: 20,
            saveInCookie: true,
            cookieId: ''
        }, 
        args = arguments;
        
        return this.each(function() {
            var $this = $(this), 
                local = {},
                data = $this.data('columnResizer');
            
            var methods = {
                init: function(initoptions) {                    
                    if (data) return;
                    data = {}; $this.data('columnResizer', data);
                    data.options = $.extend({}, defaults, initoptions);
                    data.resizer = $(data.options.domHandler).appendTo($this);
                    data.leftColumn = $this;
                    data.rightColumn = $this.parent().find(data.options.rightColumnClass);
                    
                    data.resizer.hover(onHover, onOutHover);
                    data.resizer.bind('mousedown.columnResizer', onMouseDown);
                    data.resizer.bind('dragstart.columnResizer', function(){return false;});
                    $('body').bind('mouseup.columnResizer', onMouseUp)
                    
                    if (data.options.saveInCookie) {
                        var defaultWidth = +($.cookie('columnResizer-width-'+data.options.cookieId));
                        if ( defaultWidth ) {
                            setWidth(defaultWidth);
                        }
                    } 
                }
            }
            
            //private 
            var onHover = function() {
                $(this).addClass('over');

                var offset = $this.offset().top - $(window).scrollTop();
                var middle = ($(window).height() - offset)/2 + data.options.marginTop;
                $(data.options.handler, $this).css({top: middle});                
            },
            
            onOutHover = function() {
                $(this).removeClass('over');
            },
            
            onMouseDown = function(e) {
                local.mouseX = e.pageX;
                local.leftWidth = $this.width();
                
                data.resizer.addClass('ondrag');
                $('body').bind('mousemove.columnResizer', onMove);
                return false;
            },

            onMouseUp = function() {
                data.resizer.removeClass('ondrag');
                $('body').unbind('mousemove.columnResizer');
                
                if (data.options.saveInCookie) {
                    var cookie_options = {
                        expires: new Date((new Date()).valueOf() + 1000*3600*24*365*5)
                    };
                    $.cookie('columnResizer-width-'+data.options.cookieId, $this.width(), cookie_options);
                }                
            },
            
            onMove = function(e) {
                var newLeftWidth = local.leftWidth + e.pageX - local.mouseX;
                setWidth(newLeftWidth);
                try {
                    window.getSelection().removeAllRanges();
                } catch(err) {
                    document.selection.empty(); // IE<9
                }
            },
            
            setWidth = function(newLeftWidth)
            {
                if (newLeftWidth < getInPixels(data.options.leftMinWidth)) {
                    newLeftWidth = getInPixels(data.options.leftMinWidth);
                }
                
                if (newLeftWidth > getInPixels(data.options.leftMaxWidth)) {
                    newLeftWidth = getInPixels(data.options.leftMaxWidth);
                }
                var newRightMargin = newLeftWidth + data.options.rightMarginCorrect;                
                data.leftColumn.css('width', newLeftWidth+'px');
                data.rightColumn.css('margin-left', newRightMargin+'px');
                
                $(data.options.linkLeft).css('width', newLeftWidth+'px');
                $(data.options.linkRight).css('margin-left', newRightMargin+'px');
            },
            
            getInPixels = function(value) {
                if ( value.indexOf('%') > -1) {
                    value = $this.parent().width() * (value.substr(0, value.indexOf('%'))/100);
                }
                return +(value);                
            };
            
            if ( methods[method] ) {
                methods[ method ].apply( this, Array.prototype.slice.call( args, 1 ));
            } else if ( typeof method === 'object' || ! method ) {
                return methods.init.apply( this, args );
            }
        });
    },
    
    /**
    * Добавляет HTML ошибок формы
    * 
    * @param errors - объект ошибок
    * @param $form - jquery Объект формы, в которой нахзоятся input
    * @param _options
    */
    fillError: function(errors, $form, _options) {
        var defaults = {
            containerClass: 'error-list',
            fieldError: '.field-error'
        },
        options = $.extend(defaults, _options);
                
            var ul = $('<ul></ul>').addClass(options.containerClass);
            
            for(var key in errors) {
                var marker = $('<div></div>')
                    .addClass(errors[key]['class'])
                    .append(errors[key]['fieldname']+'<i class="cor"></i>')
                
                var err_msg = '';
                for(var n in errors[key]['errors']) {
                    err_msg = err_msg + errors[key]['errors'][n]+'<br>';
                }
                ul.append( $('<li></li>').append(marker, $('<div class="text"></div>').append(err_msg)) );
                
                if (key[0] != '@' && $form) {
                    $('[name="'+key+'"]', $form)
                        .addClass('has-error')
                        .on({
                            'focus.formerror': function() {
                                $(options.fieldError+'[data-field="'+$(this).attr('name')+'"]', $form).show();
                            },
                            'blur.formerror': function() {
                                $(options.fieldError+'[data-field="'+$(this).attr('name')+'"]', $form).hide();
                            }
                        });
                        
                    $(options.fieldError+'[data-field="'+key+'"]', $form).html(
                        $('<span class="text"></span>').append('<i class="cor"></i>'+err_msg)
                    );
                    
                    if ($('[name="'+key+'"]', $form).is(':focus')) {
                        $('[name="'+key+'"]', $form).focus(); //Посылаем событие
                    }
                }
            }
        $(this).html(ul);
    }
    
});

/**
* Выполняет редирект на страницу авторизации, в случае необходимости
* @param response - ответ сервера от ajax запроса
*/
function checkAuthorization(response)
{
    if (typeof(response) == 'object' && response.needAuthorization) {
        var param = (global.authUrl.indexOf('?') == -1) ? '?referrer='+location.href : '&referer='+location.href;
        location.href = global.authUrl + param;
        return false;
    }
    return true;
}


/**
* Проверяет нужно ли повторять запрос
* 
* @param response - ответ сервера от ajax запроса
*/
function checkRepeat(response){
    if (response.repeat && !response.needAuthorization) {
        return true;
    }
    return false;
}

function checkWindowRedirect(response)
{
    if (typeof(response) == 'object' && response.windowRedirect) {
        location.href = response.windowRedirect;
        return false;        
    }
    return true;
}

function checkMessages(response)
{
    try {
        if (typeof(response) == 'object' && response.messages) {
            for(var i in response.messages) {
                messageObject = {
                    text: response.messages[i].text
                }
                if (typeof(response.messages[i].options) == 'object') {
                    messageObject = $.extend(response.messages[i].options, messageObject);
                }
                $.messenger('show', messageObject);
            }
        }
    } catch(err) {};
    return true;
}

/**
* Открывает определенный url в диалоговом окне
* @param object options
*/
function openDialog(options)
{
    options = $.extend({
        url: '',
        ajaxOptions: {},        
        dialogOptions: {},
        close: function() {},        
        afterOpen: function() {},
        dialogId: null,
        extraParams: {}
    }, options);
    
    if (options.ajaxOptions && options.ajaxOptions.data) {
        if (typeof(options.ajaxOptions.data) == 'object' && options.ajaxOptions.data instanceof Array) {
            options.ajaxOptions.data.push({name: 'ajax', value: '1'}, {name: 'ajaxDialog', value: '1'});
        }
        if (typeof(options.ajaxOptions.data) == 'object') {
            options.ajaxOptions.data = $.extend(options.ajaxOptions.data, {ajax:1, dialogMode:1});
        }
    }

    if (options.dialogId) {
        var dialog = $('#'+options.dialogId);
        if (!dialog.length) {
            var dialog = $('<div></div>').attr('id', options.dialogId);
        }
    } else {
        var dialog = $('<div></div>');
    }
    
    var initDialog = function(response) {  
        if (!response || !response.needAuthorization) {
            
            if (response) {                
                try{                    
                    if (options.dialogOptions.crudOptions.onLoadTrigger) {
                        $(window).trigger(options.dialogOptions.crudOptions.onLoadTrigger, response);
                    }
                } catch(e) {}
                if (response.close_dialog) return;
            }
            
            $(dialog)
            .addClass('dialog-window')
            .dialog($.extend({
                modal: true,
                maxWidth:1000,
                create: function() {
                    //Оборачиваем диалог, чтобы сохранить его стили 
                    $(this).closest('.ui-dialog').wrap('<div class="admin-style admin-dialog-wrapper" />');

                    var initContent = function() {
                        //Устанавливаем заголовок окна
                        var titleElement = $('.titlebox:first', dialog).hide();
                        if (titleElement.length) {
                            dialog.dialog('option', 'title', titleElement.length ? titleElement.html() : '');
                        }
                        var dataLocalOptions = {width: 'auto'};
                        
                        var localOptions = $('[data-dialog-options]', dialog);                        
                        if (localOptions.length) {
                            dataLocalOptions = $.extend(dataLocalOptions, localOptions.data('dialogOptions'));
                        }
                        
                        dialog.dialog('option', dataLocalOptions);        
                    };
                    
                    if (response) {
                        $(this).html(response.html);
                        $(this).parent().append('<div class="footer-margin"></div>')
                        $(this).bind('initContent', initContent);
                        initContent.call(this);                        
                    }
                },
                open: function() {
                    options.afterOpen($(this));
                    
                    if (!$(this).data('dialogAlreadyLoaded')) {
                        
                        $(this).data('dialogAlreadyLoaded', true);
                        $(this).dialog('option', 'position', { my: "center", at: "center", of: window, collision:"fit" }).trigger('resize');
                        
                        if (typeof($LAB) != 'undefined' && $LAB.loading) {
                            $(window).one('LAB-loading-complete', function() {
                                //Если идет загрузка скриптов, то откладываем событие, 
                                //чтобы код с криптах мог подписаться на события
                                $(dialog).trigger('new-content');
                            });
                        } else {
                            $(dialog).trigger('new-content');
                        }
                        
                        //При возникновении данного события в диалоговом окне, окно центрируется
                        $(this).on('contentSizeChanged.dialogWindow', function() {
                            $(this).dialog('option', 'position', { my: "center", at: "center", of: window, collision:"fit" }).trigger('resize');
                        });
                    }
                },
                close: function() {
                    options.close.call(dialog, dialog);
                    if (!options.dialogId) {
                        $(dialog).trigger('dialogBeforeDestroy');
                        var wrapper = $(this).closest('.admin-dialog-wrapper');
                        $(this).dialog('destroy');
                        wrapper.remove();
                    }
                }
            }, options.dialogOptions));
        }
    }
    
    try{                    
        if (options.dialogOptions.crudOptions.beforeCallback) {
            var result = eval(options.dialogOptions.crudOptions.beforeCallback+'(options)');
            if (result) options = result;
        }
    } catch(e) {}
    
    if (!dialog.data('dialogAlreadyLoaded')) {
        $.ajaxQuery($.extend({
            url: options.url,
            dataType: 'json',
            data: $.extend({ajax:1, dialogMode:1}, options.extraParams),
            success: initDialog
        }, options.ajaxOptions));
    } else {
        initDialog(null);
    }
        
    return false;
}

/**
* Отвечает за отображение индикатора загрузки
*/
var loading = {
    stackLength: 0,
    element: null,
    inProgress: false,
    /**
    * Отображает индикатор загрузки
    */
    show: function() {
        this.hide(true);
        this.inProgress = true;
        if (!this.element) {
            this.element = $('<div class="loading"></div>')
                            .append('<div class="overlay"></div><span>'+lang.t('Загрузка...')+'</span>');
            this.element.appendTo('.admin-body');        
        }
        this.stackLength++;
    },
    
    /**
    * Скрывает индикатор загрузки
    */
    hide: function(noDec) {
        if (this.stackLength>0 && !noDec) {
            this.stackLength--;
        }

        if (this.element && this.stackLength == 0) {
            this.inProgress = false;
            this.element.remove();
            this.element = null;
        }
    },
    
    /**
    * Отображает текст ошибки загрузки отправки данных
    */
    error: function(errtext) {
        this.stackLength = 0;        
        this.hide();
        if (!errtext) {
            errtext = lang.t('Ошибка передачи данных. Повторите попытку еще раз');
        }
        
        this.element = $('<div class="loading loading-error"></div>')
            .append('<div class="overlay"></div>')
            .append( $('<span></span>').html(errtext) )
            .appendTo('.admin-body');
    }
}


/**
* Отвечает за ajax выполнение стандартных процедур CRUD 
*/
var crud = {
    bottomDisableStack: [],
    init: function(context) {
        if (!context) {
            context = $('body');
        }
        $('.crud-add').off('.crud').on('click.crud', {action: 'add'}, crud.handler);
        $('.crud-edit').off('.crud').on('click.crud', {action: 'edit'}, crud.handler);
        $('.crud-remove').off('.crud').on('click.crud', {action: 'remove'}, crud.handler);
        $('.crud-post-selected').off('.crud').on('click.crud', {action: 'postSelected'}, crud.handler);
        $('.crud-remove-one').off('.crud').on('click.crud', {action: 'removeOne'}, crud.handler);
        $('.crud-multiedit').off('.crud').on('click.crud', {action: 'multiedit'}, crud.handler);
        $('.crud-dialog').off('.crud').on('click.crud', {action: 'dialog'}, crud.handler);
        
        $('.crud-form').off('.crud').on('submit.crud', {action: 'formSave'}, crud.handler);
        $('.crud-form-save').off('.crud').on('click.crud', {action: 'formSave'}, crud.handler);
        $('.crud-form-cancel').off('.crud').on('click.crud', {action: 'formCancel'}, crud.handler);        
        $('.crud-switch').off('.crud').on('switched.crud', crud.itemSwitch);        
        
        $('.crud-get').off('.crud').on('click.crud', {action: 'get'}, crud.handler);        
        $('.crud-post').off('.crud').on('click.crud', {action: 'post'}, crud.handler);        
        
        //Обработка общих событий
        $('body').off('.crud').on({
            'disableBottomToolbar.crud': function(e, key) {
                $('.bottomToolbar .button').addClass('disabled');
                if (crud.bottomDisableStack.indexOf(key) == -1) {
                    crud.bottomDisableStack.push(key);
                }
            },
            'enableBottomToolbar.crud': function(e, key) {
                var index = crud.bottomDisableStack.indexOf(key);
                if (index != -1) {
                    crud.bottomDisableStack.splice(index, 1);
                }
                //Разблокируем кнопки, только если не осталось ни одного блокирующего элемента
                if (!crud.bottomDisableStack.length) {
                    $('.bottomToolbar .button').removeClass('disabled');
                }
            }
        });
    },
    
    /**
    * Общий обработчик всех событий CRUD
    */
    handler: function(e) {
        if (e.ctrlKey) return true;
        
        if (loading.inProgress) {
            return false;
        }
        
        e.currentTarget = this;
        return crud[e.data.action](e);
    },
    
    /**
    * Выполняет get запрос (url берется из data-url) и обновляет содержимое видимой области
    */
    get: function(e) {
        var button = e.currentTarget;
        if ( $(button).is('.disabled') ) return false;
        
        if (crud._checkConfirm(button)) {
            
            var success = false;            
            var url = $(button).data('url') ? $(button).data('url') : $(button).attr('href');
            var dialog = crud._getMyDialog(button);
            
             var ajaxOptions = {
                url: url,
                success: function(response) {
                    success = true;
                    
                     //Данные успешно отправлены
                
                    if (checkRepeat(response)){
                       var params = $.extend({
                           type:'POST',
                           dataType:'json',
                           data: {},
                           url:url,
                           success: ajaxOptions.success,
                           error: ajaxOptions.error,
                       }, response.queryParams);
                       
                       $.ajaxQuery(params);
                       
                    }else{
                        if (typeof(response) == 'object' && !response.noUpdate) {
                            crud._updateTarget(button, null, dialog ? dialog.dialog('option', 'crudOptions') : null);
                        }
                        
                        if ($(button).hasClass('crud-close-dialog') && dialog) {
                            dialog.dialog('close');
                        }
                    }
                    
                }
            };
            
            $.ajaxQuery(ajaxOptions);
        }
        return false;
    },
    
    /**
    * Отправляет запрос на сервер, после действия выключателя
    */
    itemSwitch: function(e) {
        var url = $(this).data('url');
        $.ajaxQuery({
            loadingProgress: false,
            url:url
        });
    },
    
    /**
    * Отправляет форму на сервер, отображает ошибки в случае их наличия
    */
    formSave: function(e) {
        e.preventDefault();        
        var button = e.currentTarget;
        if ( $(button).is('.disabled') ) return false;
        if (crud.bottomDisableStack.length) return false;
        
        var $group = crud._getGroup(e.currentTarget);
        var $form = crud._getForm(e.currentTarget);
        var dialog = crud._getMyDialog(e.currentTarget);
        
        var afterSubmit = function() {
            $('button[type="submit"], input[type="submit"]',$form).removeAttr('disabled'); //Защита от двойного клика            
        }
        
        /**
        * Финальная обработка пришедшего запроса
        * 
        * @param object dialog   - объект диалогового окна
        * @param object responce - объект ответа с сервера
        */
        var finalProcess = function (dialog, response){    
            if (checkAuthorization(response) && checkWindowRedirect(response)) {
                checkMessages(response);
                if (response.success) {                                                 
                    $form.trigger('crudSaveSuccess', response);
                    var redirect = response.redirect ? response.redirect : null;                        
                    //Сохранение прошло успешно
                    if (dialog) { //Если всё было в диалоговом окне
                        if (response.html && response.html != '') {
                            //Обновляем контент в диалоге
                            dialog.html(response.html)
                                .trigger('new-content')
                                .trigger('initContent')
                                .trigger('contentSizeChanged');
                            return;
                        }
                        
                        //Редактирование в диалоге
                        if (response.callCrudAdd) {
                            var a = $('<a />').data('url', response.callCrudAdd);
                            crud.add({currentTarget: a});
                        } else {
                            if (!response.noUpdateTarget){ //Если нет флага о том, что нужно перезагрузить область
                               crud._updateTarget(button, redirect, dialog.dialog('option', 'crudOptions')); //обновляем область с опциями
                            }
                        }
                        dialog.dialog('close');
                                                    
                    } else {
                        //Редактирование на отдельной странице
                        $('.crud-form-success', $group).html(response.formdata.success_text).show();
                        if (response.success_text_timeout) {
                            setTimeout(function() {
                                $('.crud-form-success', $group).slideUp();
                            }, response.success_text_timeout ? response.success_text_timeout : 7000);
                        }
                        if ( $(button).data('callUpdateAfter') ) {
                            updatable.updateTarget(button, redirect);
                        }
                    }
                    
                } else {
                    $form.trigger('crudSaveFail', response);
                    //Во время сохранения возникли ошибки
                    if (response.formdata) {
                        $('.crud-form-error', $group).fillError(response.formdata.errors, $form);
                        if (dialog) {
                            dialog.trigger('contentSizeChanged');
                        }                            
                    }
                }
            }
        }
        
        var ajaxOptions = {
            data: {ajax: 1},
            dataType:'json',
            beforeSerialize: function(form, options) {
                $('select.selectAllBeforeSubmit option', form).prop('selected', true);
                form.trigger('beforeAjaxSubmit', form, options);
            },
            beforeSubmit: function(arr, form, options) {
                $('button[type="submit"], input[type="submit"]',$form).attr('disabled','disabled'); //Защита от двойного клика
                loading.show();
            },
            success: function(response) { 
                $('.crud-form-error, .crud-form-success', $group).html('');
                $('.has-error', $form).removeClass('has-error');
                $('.field-error', $form).hide();
                $('[name]', $form).off('.formerror');
                                
                //Данные успешно отправлены
                
                if (checkRepeat(response)){
                   var params = $.extend({
                       type:'POST',
                       dataType:'json',
                       data: {},
                       url:'',
                       success: ajaxOptions.success,
                       error: ajaxOptions.error,
                   }, response.queryParams);
                   
                   $.ajaxQuery(params);
                   
                }else{
                    loading.hide();        
                    finalProcess(dialog,response); 
                    afterSubmit();                   
                }  
            },
            error: function() {
                //Ошибка отправки формы
                loading.error();
                afterSubmit();
            }
        };

        $form.ajaxSubmit(ajaxOptions);
        
        //return false;
    },
    
    /**
    * Закрывает диалоговое окно, если таковое имеется
    */
    formCancel: function(e) {
        if ( $(e.currentTarget).is('.disabled') ) return false;
        
        var dialog = crud._getMyDialog(e.currentTarget);
        if (dialog) {
            dialog.dialog('close');
            return false;
        }
    },
    
    /**
    * Открывает диалог создания записи
    */
    add: function(e, checked) {
        var crudOptions = $(e.currentTarget).data('crudOptions');
        var extendOptions = {};
        try {
            if (crudOptions.updateThis) {
                crudOptions.updateElement = e.currentTarget;
            }
            if (crudOptions.dialogId) {
                extendOptions.dialogId = crudOptions.dialogId;
            }
        } catch(err) {};
        
        openDialog($.extend({
            dialogOptions: {//Передаем в диалоговое окно дополнительные данные инициатора действия
                crudOptions: $(e.currentTarget).data('crudOptions')
            },
            url: $(e.currentTarget).data('url') ? $(e.currentTarget).data('url') : $(e.currentTarget).attr('href'),
            ajaxOptions: {
                data: checked
            },
            afterOpen: function(dialog) {
                try {
                    if ($(e.currentTarget).hasClass('crud-replace-dialog')) {
                        var my_dialog = crud._getMyDialog(e.currentTarget);
                        if (my_dialog) my_dialog.dialog('close');
                    }
                } catch(err) {};
                
                var initDialogForm = function() {
                    crud._removeToolbar(dialog);
                    //var nextIndex = 1+(+dialog.closest('.ui-dialog').css('z-index'));
                    var bottomToolbar = $('.bottomToolbar',dialog).addClass('dialogBottomToolbar').css('z-index', 2000);
                    bottomToolbar.data('crudToolbarForm', crud._getForm(bottomToolbar)); //Сохраняем форму, на котоую ссылаются кнопки toolbar
                    bottomToolbar.appendTo('.admin-body'); //Выносим toolbar из окна
                    dialog.data('bottomToolbar', bottomToolbar);
                }
                dialog.bind('initContent', initDialogForm);
                initDialogForm();
            },
            close: function(dialog) {
                crud._removeToolbar(dialog);
            }
        }, extendOptions));
        
        if (typeof (e.preventDefault) == 'function' ) e.preventDefault();
    },
    
    /**
    * Открывает диалог редактирования записи
    */
    edit: function(e, checked) {
        return crud.add.call(this, e, checked);
    },
    
    /**
    * Удаляет выбранные записи
    */
    remove: function(e) {
        var target = $(e.currentTarget);
        if (!target.data('confirmText')) {
            target.data('confirmText', lang.t('Вы действительно хотите удалить выбранные элементы (%count)?'));
        }
        return crud.post(e);
    },
    
    /**
    * Выполняет POST формы на заданный URL
    * 
    * @param e
    */
    postSelected: function(e) {
        return crud.post(e);        
    },
    
    /**
    * Выполняет Ajax'ом POST запрос для выбранных элементов
    */
    post: function(e) {
        var $form = crud._getListForm(e.currentTarget);
        var url = $(e.currentTarget).data('url');
        var confirmText = $(e.currentTarget).data('confirmText');
        
        if ($('.select-all:checked', $form).length && $('.total_value', $form.parent()).length) {
            var total = $('.total_value', $form.parent()).text();
        } else {
            var total = $('input[name="chk[]"]:checked', $form).length;
        }
        
        if (!total) {
            return false;
        }
        
        if (confirmText && !confirm( confirmText.replace('%count', total) )) {
            return false;
        }
        
        loading.show();
        $form.ajaxSubmit({
            dataType: 'json',
            data: {
                ajax: 1
            },
            url: url,
            success: function(response) {
                loading.hide();
                checkAuthorization(response);
                checkWindowRedirect(response);
                checkMessages(response);
                if (response.success) {
                    updatable.updateTarget(e.currentTarget);
                }
            },
            error: function() {
                loading.error();
            }
        });
        
        return false;  
    },
    
    /**
    * Удаляет один элемент
    */
    removeOne: function(e) {
        if (!confirm(lang.t('Вы действительно хотите удалить выбранный элемент?'))) {
            return false;
        }
        
        var button = $(e.currentTarget),
            crudOptions = button.data('crudOptions'),
            url = $(e.currentTarget).data('url') ? $(e.currentTarget).data('url') : $(e.currentTarget).attr('href');
        
        $.ajaxQuery({
            url: url,
            data: {
                ajax:1
            },
            success: function() {
                crud._updateTarget(button, null, crudOptions ? crudOptions : null);
            }
        });
        
        return false;
    },
    
    /**
    * Открывает диалог мультиредактирования
    */
    multiedit: function(e) {
        var $form = crud._getListForm(e.currentTarget);
        var data = $form.serializeArray();
        
        var checked = $('input[name="chk[]"]:checked', $form);

        if (!checked.length) {
            return false;
        }
        
        if (checked.length == 1) { //Перебрасываем на обычное редактирование
            var edit_one = $('.crud-edit[data-id="'+checked[0]['value']+'"]', $form);
            if (edit_one.length) {
                edit_one.click();
            } else {
                alert(lang.t('Выберите как минимум 2 элемента для мультиредактирования'));
            }
            return false;
        } else {        
            return crud.edit.call(this, e, data)
        }
    },
    
    /**
    * Открывает диалог и загружает в него контент, при закрытии диалога обновляет содержимое основного поля
    */
    dialog: function(e)
    {
        openDialog({
            url: $(e.currentTarget).data('url') ? $(e.currentTarget).data('url') : $(e.currentTarget).attr('href'),
            dialogOptions: {
                title: $(e.currentTarget).attr('title'),
                close: function() {
                    updatable.updateTarget(e.currentTarget);
                }
            }
        });
        return false;
    },
    
    _removeToolbar: function(dialog) {
        if (dialog.data('bottomToolbar')) {
            dialog.data('bottomToolbar').remove();
        }        
    },

    /**
    * Возвращает элемент crud группы в которой находится element
    */
    _getGroup: function(element) {
        if ($(element).closest('.dialogBottomToolbar').data('crudToolbarForm')) {
            var element = $(element).closest('.dialogBottomToolbar').data('crudToolbarForm');
        }
        return $(element).closest('.crud-ajax-group');
    },
    
    /**
    * Возвращает форму, которую будет использовать element
    */
    _getForm: function(element) {
        if ($(element).data('form')) {
            var $form = $( $(element).data('form') );
        } else if (element.tagName == 'FORM') {
            var $form = $(element);
        } else if ($(element).closest('.dialogBottomToolbar').data('crudToolbarForm')) {
            var $form = $(element).closest('.dialogBottomToolbar').data('crudToolbarForm');
        } else if ($(element).closest('form').length) {
            var $form = $(element).closest('form');
        } else {
            var $form = $('.crud-form', crud._getGroup(element));
        }
        return $form;
    },
    
    /**
    * Возвращает форму, которую будет использовать element
    */
    _getListForm: function(element) {
        if ($(element).data('form')) {
            var $form = $( $(element).data('form') ); //Если форма явно задана в атрибуте элемента
        } else if ($(element).closest('[data-linked-form]').length) {
            var $form = $($(element).closest('[data-linked-form]').data('linkedForm')); //Если элемент обернут в блок с атрибутом data-linked-form
        } else if ($(element).closest('form').length) {
            var $form = $(element).closest('form'); //Если выше есть какая-нибудь форма
        } else {
            var $form = $('.crud-list-form', crud._getGroup(element)); //Ищем в ajax-group элемент с классом crud-list-form
        }
        return $form;
    },    
    
    /**
    * Возвращает элемент окна, в котором находится элемент или false
    * 
    * @param element
    */
    _getMyDialog: function(element)
    {
        var $linkedForm = $(element).closest('.dialogBottomToolbar').data('crudToolbarForm');
        if ($linkedForm) {
            var element = $linkedForm.get(0);
        }
        
        var $win = $(element).closest('.dialog-window');
        return $win.length ? $win : false;
    },
    
    /**
    * Спрашивает у пользователя подтверждение, если это необходимо
    */
    _checkConfirm: function(element)
    {
        if ($(element).data('confirmText')) {
            return confirm($(element).data('confirmText'));
        }
        return true;
    },
    
    /**
    * Обновляет необходимый контейнер возле элемента element
    */
    _updateTarget: function(element, redirect, crudOptions)
    {
        if (crudOptions && crudOptions.updateElement) {
            element = crudOptions.updateElement;
        }
        
        var ajaxParam = (crudOptions && crudOptions.ajaxParam) ? crudOptions.ajaxParam : null;
        
        if (!updatable.updateTarget(element, redirect, null, ajaxParam)) {
            location.reload(true);
        }
    }
}

$.contentReady(function() {
    crud.init(this);
    $('.admin-style [title]').tooltip({track: false, delay:200, left:-5, top:30});
    
 var checkChange = function() {
        if (this.checked) {
            $(this).closest('.chk').addClass('checked');
        } else {
            $(this).closest('.chk').removeClass('checked');
            var $parentform = $(this).closest('form');
            $('.select-all', $parentform).prop('checked', false);
            $('.select-page', $parentform).prop('checked', false);
            $('thead .chk', $parentform).removeClass('checked-all');
        }
    };
    
    //Инициализируем подсветку выбранных чекбоксов
    $('.chk input:checkbox', this).off('.chk').on('change.chk', checkChange);
    
    //Инициализируем подсветку выбранных чекбоксов
    $('.chk', this).off('.chk').on('click.chk', function(e) {
        var checkbox = $('input:checkbox', this).get(0);
        if (!checkbox.disabled) {
            if (e.target.tagName != 'INPUT') {
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                    $(checkbox).change();
                }
            }
        }
    });
    
    var onSelectButton = function() {
        var name = $(this).data('name');
        var state = this.checked;
        var $parentform = $(this).closest('.localform');
        if (!$parentform.length) {
            $parentform = $(this).closest('form');
        }
        $('input:checkbox[name="'+name+'"]:enabled', $parentform).prop('checked', state);
        
        if (state) {
            $('.chk:has(input:enabled)', $parentform).addClass('checked');
            $('.redmarker', $parentform).addClass('r_on');
        } else {
            $('.select-all', $parentform).prop('checked', false);
            $(this).closest('.chk').removeClass('checked-all');
            $('.chk:has(input:enabled)', $parentform).removeClass('checked');
            $('.redmarker', $parentform).removeClass('r_on');
        }
    }
    
    $('.select-page', this).change(onSelectButton);
    $('.select-all', this).change(function() {
        var state = this.checked;
        var $parentform = $(this).closest('form');
        var selectPage = $('.select-page', $parentform).prop('checked', state).get(0);
        onSelectButton.call(selectPage);
        if (state) {
            $(this).closest('.chk').addClass('checked-all');
        } else {
            $(this).closest('.chk').removeClass('checked-all');
        }
    });    
    
    $('.rs-table thead .chk', this).hover(function() {
        $(this).addClass('chk-over');
    }, function() {
        $(this).removeClass('chk-over');
    });    
    
    /**
    * Смена состояния поля при мультиредактировании в админ панели
    * 
    */
    $('.doedit',this).on('change',function(){
       var row_block = $(this).closest('.editrow'); //Оборачивающий тег
       if ($(this).prop('checked')){
          $(".multi_edit_rightcol",row_block).removeClass('coveron'); 
       }else{
          $(".multi_edit_rightcol",row_block).addClass('coveron');  
       }
    });
    
    $('.multi_edit_rightcol .cover', this).on('click', function() {
        $(this).closest('tr').find('.doedit').prop('checked', true).trigger('change');
    });    
});

/**
* Добавляет класс при наведении мыши на объекты
*/
$(function() {       
    $('body').on('mouseenter', '.overable > *:not(".noover")', function() {
        $(this).addClass('over');
    });
    
    $('body').on('mouseleave', '.overable > *:not(".noover")', function() {
        $(this).removeClass('over');
    });
});