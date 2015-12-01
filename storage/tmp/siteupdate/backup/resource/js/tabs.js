//Активатор табов
(function( $ ){
  $.fn.tabs = function() {
    return this.each(function() {
        var context = this;
        $('>.tab-container a', context).click(function() {
            var $tab = $(this).closest('li');
            $('>.tab-container .act', context).removeClass('act');
            $('>.frame, > form > .frame', context).addClass('nodisp');
            $tab.addClass('act');
            $('> form > .frame[data-name="'+$(this).data('view')+'"], > .frame[data-name="'+$(this).data('view')+'"]', context)
                .removeClass('nodisp')
                .trigger('on-tab-open')
                .trigger('contentSizeChanged');
        });
    });
  };
})( jQuery );


$(function() {
    $('.tabs').tabs();
    
    //Переинициализируем табы, если на странице появился новый контент
    $(window).bind('new-content', function(e) {
        $('.tabs', e.target).tabs();
    });
    
    //Проверяем, не установлена ли необходимая текущая вкладка
    var get = new String(window.location);
    var pos = get.indexOf('#');
    if (pos>-1)
    {
        var arr = get.substr(pos+1).split('-');
        if (arr.length == 2 && arr[0] == 'tab') {
            $(".tab[data-view='"+arr[1]+"']").click();
        }
    }
});