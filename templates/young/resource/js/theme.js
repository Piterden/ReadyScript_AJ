/**
* Скрипт инициализирует стандартные функции для работы темы
*/
$.detectMedia = function( checkMedia ) {
    var init = function() {
        var detectMedia = function() {
            var 
                currentMedia = $('body').data('currentMedia'),
                newMedia = '';
                
            if ($(document).width() < 760) {
                newMedia = 'mobile';
            }
            if ($(document).width() >= 760 && $(document).width() <= 980) {
                newMedia = 'portrait';
            }
                        
            if (currentMedia != newMedia) {
                $('body').data('currentMedia', newMedia);
            }
        }        
        $(window).on('resize.detectMedia', detectMedia);
        detectMedia();
    }
    
    var check = function(media) {
        return $('body').data('currentMedia') == media;
    }
    
    if (checkMedia) {
        return check(checkMedia);
    } else {
        init();
    }
};


$(function() {
    
    //Решение для корректного отображения масштаба в Iphone, Ipad
    if (navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i)) {
        var viewportmeta = document.querySelector('meta[name="viewport"]');
        if (viewportmeta) {
            viewportmeta.content = 'width=device-width, minimum-scale=1.0, maximum-scale=1.0, initial-scale=1.0';
            document.body.addEventListener('gesturestart', function () {
                viewportmeta.content = 'width=device-width, minimum-scale=0.25, maximum-scale=1.6';
            }, false);
        }
    }//----
    
    //Инициализируем корзину
    $.cart({
        saveScroll: '.scrollCartWrap',
        cartItemRemove: '.iconX'
    }); 
    
    $('.inDialog').openInDialog();
    $.detectMedia();

    //Инициализируем быстрый поиск по товарам
    $(window).resize(function() {
        $( ".query.autocomplete" ).autocomplete( "close" );
    });
    
    //Инициализируем ссылки Купить в 1 клик, Заказать, В корзину в мобильной версии
    $('a[data-href]:not(.addToCart):not(.applyCoupon)').on('click', function() {
        if ($.detectMedia('mobile')) {
            location.href = $(this).data('href');
        }
    });     
    
    //Коррекция меню категорий для ipad
    if ($('html').hasClass('touch')) {
        $('.topMenu > li.node > a').click(function(e) { e.preventDefault(); });
    }
    
    $( ".query.autocomplete" ).each(function() {
        $(this).autocomplete({
            source: $(this).data('sourceUrl'),
            appendTo: '#queryBox',
            minLength: 3,
            select: function( event, ui ) {
                location.href=ui.item.url;
                return false;
            },
            messages: {
                noResults: '',
                results: function() {}
            }
        }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
            ul.addClass('searchItems');
            var img = $('<img />').attr('src', item.image).css('visibility', 'hidden').load(function() {
                $(this).css('visibility', 'visible');
            });
            
            return $( "<li />" )
            .append($('<div class="image" />').append(img))
            .append( '<a><span class="label">' + item.label + 
                     '</span><span class="barcode">' + item.barcode + '</span><span class="price">' + item.price + '</span> </a>' )
            .appendTo( ul );
        };
    });    
}); 

//Инициализируем обновляемые зоны
$(window).bind('new-content', function(e) {
    $('.inDialog', e.target).openInDialog();
    $('.rs-parent-switcher', e.target).switcher({parentSelector: '*:first'});
});