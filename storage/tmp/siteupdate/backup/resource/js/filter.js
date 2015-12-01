/**
* Plugin, активирующий фильтрацию данных в таблице в административной панели
*/
$.fn.filterBox = function(method) {
    var defaults = {
        filter: '.filter',
        form: '.formfilter',
        more: '.more',
        buttons: {
            openFilter: '.openfilter',
            expand: '.expand',
            close: '.close'
        }
    }, 
    args = arguments;
    
    return this.each(function() {
        var $this = $(this), options,
            $form = $('.formfilter', this);
        
        var methods = {
            init: function(initoptions) {                    
                if ($this.data('filterBox')) return false;
                $this.data('filterBox', {});
                options = $.extend({}, defaults, initoptions);
                
                $this
                    .on('filter.hide', methods.hide)
                    .on('click.filter', options.buttons.openFilter, methods.show);
                    
                $(options.buttons.expand, $form).on('click', methods.expandToggle);
                $(options.buttons.close, $form).on('click', methods.hide);
                
                $form.rsCheckOutClick(methods.hide);
                
                $('input[datetime]', $form).each(methods.initField.datetime);
                $('input[datefilter]', $form).each(methods.initField.date);
            },
            
            show: function() {
                $(options.filter).trigger('filter.hide');
                $form.show();
                return false;
            },
            
            hide: function() {
                $form.hide();
            },
            
            expandToggle: function() {
                $(options.more, $this).toggle();
                return false;
            },
            
            initField: {
                datetime: function() {
                    $(this).datetime();
                },
                date: function() {
                    $(this).datepicker({
                        showSecond: true,
                        dateFormat: 'yy-mm-dd',
                        buttonImage: global.folder+'/resource/img/adminstyle/calend.gif',
                        buttonImageOnly: true,
                        showOn: 'button',
                        showAnim: '',
                        firstDay: 1,
                        monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
                        dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
                        nextText: 'Следующий месяц',
                        prevText: 'Предыдущий месяц',
                        currentText: 'Сегодня',
                        closeText: 'Закрыть',
                        timeText: 'Время',
                        hourText: 'Часы',
                        minuteText: 'Минуты',
                        secondText: 'Секунды',
                        beforeShow: function(element, options) {
                            var widget = $(this).datepicker('widget');
                            if (!widget.parent().hasClass('admin-body')) {
                                widget.appendTo('.admin-body');
                            }
                            options.dpDiv.off('.listenclick').on('click.listenclick', function() {
                                return false;
                            });
                        }
                        
                    });
                }
            }
        }
        
        if ( methods[method] ) {
            methods[ method ].apply( this, Array.prototype.slice.call( args, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, args );
        }
    });
}

$.contentReady(function() {
    $('.filter').filterBox();
});