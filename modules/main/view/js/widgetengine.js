/**
* Plugin, активирующий виджеты
*/
(function($){
    $.fn.adminWidgets = function(method) {
        var defaults = {
            dialogId: 'addWidgetDialog',
            buttons: {
                addDialog: '.addwidget'
            },
            widgetDialog: {
                item: '.item',
                add: '.add',
                remove: '.remove'
            },
            urls: {}
        }, 
        $this = this, 
        WControl,
        options;
        
        /**
        * Класс управления виджетами на рабочем столе
        */
        CWidgetControl = function(opt)
        {
            var _this = this,
                defaultWidgetColumn = '#col_center';
            
            this.init = function()
            {
                //Устанавиливаем высоту виджет зоны
                var widget_offset = $('#widget-zone').offset();
                var wzone_height = ($(window).height() - widget_offset.top - 20)+'px';
                $('#widget-zone,#widget-zone>div').css('min-height', wzone_height);
                
                
                //Инициализируем функции перетаскивания виджетов
                $("#col_left").sortable({
                    handle: '.widget-title',
                    tolerance: 'pointer',
                    connectWith: '#col_right,#col_center',
                    cursor: 'move',
                    placeholder: "sortable-placeholder",
                    forcePlaceholderSize: true,            
                    stop: function(e, ui) {_this.onUpdate(e, ui);}
                });
                
                $("#col_right").sortable({
                    handle: '.widget-title',
                    tolerance: 'pointer',
                    connectWith: '#col_left,#col_center',
                    cursor: 'move',
                    placeholder: "sortable-placeholder",
                    forcePlaceholderSize: true,            
                    stop: function(e, ui) {_this.onUpdate(e, ui);}
                });
                
                $("#col_center").sortable({
                    handle: '.widget-title',
                    tolerance: 'pointer',
                    connectWith: '#col_left,#col_right',
                    cursor: 'move',
                    placeholder: "sortable-placeholder",
                    forcePlaceholderSize: true,            
                    stop: function(e, ui) {_this.onUpdate(e, ui);}
                });
                
                $('.widget .widget-title').disableSelection();
                
                $(window).on('widgetAdd', _this.addWidget);
                $(window).on('widgetRemove', _this.removeWidget);
                
                _this.initWidget();
                $(window).on('widgetAfterAdd.wc', _this.initWidget);
                
                //$(window).on('widgetupdate.wc', _this.initWidget);
                _this.calculateZoneHeight();
                $(window).on('widgetAdd widgetRemove widgetMoved', _this.calculateZoneHeight);
            }
            
            this.calculateZoneHeight = function() {
                $('#col_left, #col_right, #col_center').css('min-height', 'auto');
                var min_height = $('#widget-zone').height();
                $('#col_left, #col_right, #col_center').css('min-height', min_height);
            }
            
            this.onUpdate = function(event, ui)
            {
                $(ui.item).trigger('widgetMoved');
                
                $.ajaxQuery({
                    url: opt.urls.moveWidget, 
                    data: {            
                        wid: ui.item.attr('wid'),
                        col: ui.item.parents('[colname]:first').attr('colname'),
                        pos: ui.item.prevAll().length
                    }
                });
            }
            
            this.close = function()
            {
                var $widget = $(this).closest('.widget');
                var wclass = $widget.attr('wclass');
                $widget.trigger('widgetRemove', [wclass, $('#'+options.dialogId).get(0)]);
            }
            
            this.checkEmptyDesktop = function()
            {
                if ($('#widget-zone .widget').length) {
                    $('#noWidgetText').hide();
                } else {
                    $('#noWidgetText').show();
                }
            }
            
            this.initWidget = function(e, wclass) 
            {
                 var $context = (wclass) ? $(".widget[wclass='"+wclass+"']") : $('body');
                 $('.widget-tools .close', $context).off('.wc').on('click.wc', _this.close);
            }
            
            this.addWidget = function(e, wclass)
            {
                $.ajaxQuery({
                    url: opt.urls.addWidget,
                    data: {
                        wclass:wclass
                    },
                    success: function(response) {
                        var widget = $(response.html);
                        $(defaultWidgetColumn).append(widget);
                        _this.checkEmptyDesktop();
                        
                        var onWidgetAdd = function() {
                            $(widget).trigger('widgetAfterAdd', [wclass]); //Сообщаем, что виджет добавлен в колонку
                            $(widget).trigger('new-content'); //Сообщаем, что на странице появился новый контент
                        };

                        //Вызываем событие о добавлении виджета только после загрузки всех скриптов виджета
                        if (typeof($LAB) != 'undefined' && $LAB.loading) {
                            $(window).one('LAB-loading-complete', onWidgetAdd);
                        } else {
                            onWidgetAdd();
                        }                        
                    }
                });
            },
            
            this.removeWidget = function(e, wclass)
            {
                var widget = $('.widget[wclass="'+wclass+'"]');
                widget.remove();
                
                _this.checkEmptyDesktop();
                $.ajaxQuery({
                    url: opt.urls.removeWidget,
                    data: {wclass:wclass}
                });
            }

        }        
            
        //public
            
        var methods = {
            init: function(initoptions) 
            {
                if ($this.data('propertyBlock')) return false;
                $this.data('propertyBlock', {});
                options = $.extend(defaults, initoptions);
                options.urls = $this.data('widgetUrls');
                
                //Инициализируем управление виджетами на рабочем столе
                WControl = new CWidgetControl({
                    urls: options.urls
                });
                
                WControl.init();
                //initAjax();

                $this.on('click', options.buttons.addDialog, methods.addWidgetDialog);
                $(window)
                    .on('widgetAdd', onAddWidget)
                    .on('widgetRemove', onRemoveWidget);
            },
            
            addWidgetDialog: function() 
            {
                openDialog({
                    url:options.urls.widgetList,
                    dialogId: options.dialogId,
                    afterOpen: function(dialog) {
                        if (!dialog.data('dialogAlreadyLoaded')) {
                            $(options.widgetDialog.add, dialog).click(function() {
                                var wclass = $(this).closest(options.widgetDialog.item).data('wclass');
                                $(window).trigger('widgetAdd', [wclass, dialog]);
                            });

                            $(options.widgetDialog.remove, dialog).click(function() {
                                var wclass = $(this).closest(options.widgetDialog.item).data('wclass');
                                $(window).trigger('widgetRemove', [wclass, dialog]);
                            });
                        }
                    }
                })
                
                return false;
            }

        }
        
        //private 
        var onAddWidget = function(e, wclass, dialog) 
        {
            if (dialog) {
                var $widget = $(options.widgetDialog.item+'[data-wclass="'+wclass+'"]', dialog);
                $widget.addClass('already-exists');
            }
        },
        
        onRemoveWidget = function(e, wclass, dialog)
        {
            if (dialog) {
                var $widget = $(options.widgetDialog.item+'[data-wclass="'+wclass+'"]', dialog);
                $widget.removeClass('already-exists');
            }
        };
        
        
        if ( methods[method] ) {
            methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        }
    }
})(jQuery);    

$(function() {
    $('#widgets-block').adminWidgets();
});