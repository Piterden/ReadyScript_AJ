/**
* Плагин, инициализирующий показ значения поля по-умолчанию
*/
(function( $ ){
    $.fn.deftext = function( method ) {
        var defaults = {
            text: null,
            force: false,
            defclass: 'def-value',
            deftextAttribute: 'deftext'
        },
        args = arguments;
        
        return this.each(function() {
            var $this = $(this), 
                data = $this.data('j-deftext');

            var methods = {
                init: function(initoptions) {
                    if ((!initoptions || !initoptions.force) && data) return false;
                    data = {}; $this.data('j-deftext', data);
                    data.options = $.extend({}, defaults, initoptions);
                    
                    $this.off('.deftext').on({
                        'focus.deftext': methods.setNormal,
                        'blur.deftext':methods.setDefault
                    })
                    .closest('form')
                    .bind('submit', methods.setNormal);
                    $(this).closest('form').on('beforeAjaxSubmit', methods.setNormal); //post с помощью ajax
                    
                    methods.setDefault();
                },
                setDefault: function() {
                    if (($.trim($this.val()).length == 0 || $this.hasClass(data.options.defclass)) && !$this.is(':focus')) {
                        var text = data.options.text ? data.options.text : $this.data(data.options.deftextAttribute);
                        $this.val(text);
                        $this.addClass(data.options.defclass);
                    }
                },
                setNormal: function() {
                    if ( $this.hasClass(data.options.defclass) ) {
                        $this.val('');
                        $this.removeClass(data.options.defclass);
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

})( jQuery );

$(function() {
    $('input[data-deftext], textarea[data-deftext]').deftext();    
});