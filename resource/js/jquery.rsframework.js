/**
* Плагин, активирует элементы интерфейса. Кнопки, выпадающие списки, и.т.д
* @author ReadyScript lab.
*/

(function( $ ){
    $.runPlugin = function(args, methods, method) {
        if ( methods[method] ) {
            methods[ method ].apply( this, Array.prototype.slice.call( args, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, args );
        }
    }
    
    /**
    * Вызывает callback, когда был произведен клик вне зоны элемента или по нажатию Esc.
    * @param function callback - функция, которую необходимо вызвать при возникновении события
    * @param except - элемент исключения, при нажатии на который не будет происходить вызов callback
    */
    $.fn.rsCheckOutClick = function( callback, except ) {  
        return $(this).each(function() {
            var _this = this;
            $(this).on('click.checkout', function(e){e.stopPropagation();});
            $('html').on('click.checkout', function(e) {
                if (e.target != except) {
                    callback.call(_this, e)
                }
            });
            $('html').on('keypress.checkout', function(e) {if (e.keyCode == 27 ) callback.call(_this, e)} );
        });
    }    
    
    $.fn.rsDropdownButton = function( method ) {  
        var defaults = {
            type: '.rs-list-button',
            handler: '.rs-dropdown-handler',
            extHandler: '.rs-dropdown-ext-handler',
            except: '.rs-active',
            box: '.rs-dropdown',
            overClass: 'over'
        }, 
        args = arguments;

        return this.each(function() {
            var $this = $(this), 
                data = $this.data('rsDropdownButton');

            var methods = {
                init: function(initoptions) {
                    if (data) return;
                    data = {}; $this.data('rsDropdownButton', data);
                    
                    data.options = $.extend({}, defaults, initoptions);
                    $this
                        .on('click', data.options.handler, methods.toggle)
                        .on('click', data.options.extHandler, methods.toggle);
                        
                    $(data.options.box, $this).rsCheckOutClick(methods.close, $(data.options.except, $this).get(0));
                },
                toggle: function() {
                    if ($this.hasClass(data.options.overClass)) {
                        methods.close();
                    } else {
                        $(data.options.type).rsDropdownButton('close');
                        $this.addClass(data.options.overClass);
                    }
                    return false;
                },
                close: function() {
                    $this.removeClass(data.options.overClass);
                }
            }
            $.runPlugin.call(this, args, methods, method);
        });
    }    
    
    $.fn.rsGroup = function( method ) {  
        var defaults = {
            overClass: 'over'
        },
        args = arguments;

        return this.each(function() {
            var $this = $(this), 
                data = $this.data('rsGroup');

            var methods = {
                init: function(initoptions) {
                    if (data) return;
                    data = {}; $this.data('rsGroup', data);
                    data.options = $.extend({}, defaults, initoptions);
                    
                    $this.hover(function() {
                        $this.addClass(data.options.overClass);
                    }, function() {
                        $this.removeClass(data.options.overClass);
                    });
                }
            }
            $.runPlugin.call(this, args, methods, method);
        });
    }
    
    $.fn.rsSwitch = function( method ) {  
        var defaults = {
            xcorrection: 3,
            handler: '.rs-handle',
            onClass: 'on',
            offClass: 'off',
            onValue: null,
            offValue: null
        },
        args = arguments;

        return this.each(function() {
            var $this = $(this), 
                $checkbox,
                data = $this.data('rsSwitch');

            var methods = {
                init: function(initoptions) {
                    if (data) return;
                    data = {}; $this.data('rsSwitch', data);
                    data.options = $.extend({}, defaults, initoptions);
                    
                    if ($this.is(':checkbox')) {
                        var block = render();
                        block.toggleClass('on', $this.is(':checked')).toggleClass('off', !$this.is(':checked')).
                        insertAfter($this.hide());
                        $checkbox = $this;
                        $this = block;
                    }
                    $this.on('click.rsSwitch', methods.toggle);
                    data.options.onValue = $(this).data('onValue');
                    data.options.offValue = $(this).data('offValue');
                },
                
                toggle: function(e) {
                    var 
                        state = $this.hasClass('off'),
                        offset = $this.width() - $(data.options.handler).width() - data.options.xcorrection;
                    if (state) { //Включаем
                        $this.data('currentValue', data.options.onValue);
                        $(data.options.handler, $this).css({right: 'auto'}).animate({
                            left: offset
                        }, 100, function() {
                            $this.removeClass('off').addClass('on');
                            $(this).css({right: '', left: ''});
                        });
                    } else { //Выключаем
                        $this.data('currentValue', data.options.offValue);
                        $(data.options.handler, $this).css({left: 'auto'}).animate({
                            right: offset
                        }, 100, function() {
                            $this.removeClass('on').addClass('off');
                            $(this).css({right: '', left: ''});
                        });
                    }
                    if ($checkbox) $checkbox.prop('checked', state);
                    $this.trigger('switched', state);
                }
            };
            
            var render = function() {
                return $('<div class="rs-switch">'+
                    '<div class="rs-border">'+
                        '<div class="rs-back"></div>'+
                        '<div class="rs-handle"></div>'+
                    '</div>'+
                '</div>');
            }
            
            $.runPlugin.call(this, args, methods, method);
        });
    }
    
    //Инициализируем элементы

    var initRsElements = function() {
        $('.rs-list-button').rsDropdownButton()
        $('.rs-group').rsGroup();
        $('.rs-switch').rsSwitch();
    }

    $(function() {
        if ($.contentReady) {
            $.contentReady(initRsElements);
        } else {
            $(initRsElements);
        }    
    });

})( jQuery );

