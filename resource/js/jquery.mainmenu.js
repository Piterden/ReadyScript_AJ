/**
* Плагин инициализирует работу выпадающего меню
* @author ReadyScript lab.
*/
(function( $ ){
  $.fn.mainmenu = function( options ) {  
    options = $.extend({
        escapeDelay: 30
    }, options);
      
    return $(this).each(function() {
        var menu = this;
        $('li', this).has('ul').hover(function() {
            var _this = this;
            clearTimeout($('>ul', this).data('timer'));
            $(this).addClass('over');
            $('>ul', this).addClass('visible');
        }, function() {
            var _this = this;            
            clearTimeout($('>ul', this).data('timer'));
            $('>ul', this).data('timer', setTimeout(function() {
                $('>ul', _this).removeClass('visible');
                $(_this).removeClass('over');
            }, options.escapeDelay));
        });
    });
  }
})( jQuery );