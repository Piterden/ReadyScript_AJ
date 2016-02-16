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
    
   $('#up').click(function () { 
        $('body, html').animate({ scrollTop: 0 });  
        return false; 
    });
    
    //Инициализируем корзину
    $.cart({
        saveScroll: '.scrollBox',
        cartItemRemove: '.cartTable .iconX',
        cartTotalPrice: '.floatCartPrice',
        cartTotalItems: '.floatCartAmount'
    }); 
    
    $('.inDialog').openInDialog();
    $('.rsTabs').activeTabs();
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

$(window).load(function() {
    $('body').on('mouseover', '.products .photoView', function() {
        if (!$(this).data('gallery')) {
    
            $('.gallery [data-change-preview]', this).mouseenter(function() {
                $(this).addClass('act').siblings().removeClass('act');
                $(this).closest('.photoView').find('.middlePreview').attr('src', $(this).data('changePreview') );
                return false;
            });            

            $('.products .photoView').mouseleave(function() {
                $('.gallery [data-change-preview]:first', this).trigger('mouseenter');
            });            
            
            $('.scrollable .scrollBox', this).jcarousel({
                vertical: true
            });
            $(window).unbind('resize.jcarousel');            
            
            $('.scrollable .control', this).on({
                'inactive.jcarouselcontrol': function() {
                    $(this).addClass('disabled');
                },
                'active.jcarouselcontrol': function() {
                    $(this).removeClass('disabled');
                }
            });            

            $('.scrollable .control.up', this).jcarouselControl({
                target: '-=3'
            });
            $('.scrollable .control.down', this).jcarouselControl({
                target: '+=3'
            });
            $(this).data('gallery', true);
        }
    });
});


//Инициализируем обновляемые зоны
$(window).bind('new-content', function(e) {
    $('.inDialog', e.target).openInDialog();
    $('.rs-parent-switcher', e.target).switcher({parentSelector: '*:first'});
});