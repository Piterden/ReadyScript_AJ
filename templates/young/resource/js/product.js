/**
* Скрипт активирует необходимые функции на странице просмотра товаров
*/
$(window).load(function() {
    /**
    * Прокрутка нижних фото у товара в карточке
    */ 
    $('.gallery .scrollWrap').jcarousel({
        items: "li:visible"
    });
    
    $('.control').on({
        'inactive.jcarouselcontrol': function() {
            $(this).addClass('disabled');
        },
        'active.jcarouselcontrol': function() {
            $(this).removeClass('disabled');
        }
    });
    $('.control.prev').jcarouselControl({
        target: '-=3'
    });
    $('.control.next').jcarouselControl({
        target: '+=3'
    });
    
    $(window).resize(function() {
        $('.gallery .scrollWrap').jcarousel('scroll', 0);
    });
    
});

$(function() {
   /**
   * Открытие главного фото товара в colorbox
   */
    $('.no-touch .product .main[rel="bigphotos"]').colorbox({
       rel:'bigphotos',
       className: 'titleMargin',
       opacity:0.2
   });
   
   $('.gallery .preview').click(function() {
        var n = $(this).data('n');
        $('.product .main').addClass('hidden');
        $('.product .main[data-n="'+n+'"]').removeClass('hidden');
        return false;
    });
    
    var photoEx = new RegExp('#photo-(\\d+)');
    var res = photoEx.exec(location.hash);
    if ( res != null ) {
        $('.gallery .preview[data-n="'+res[1]+'"]').click();
    } 
    
    $('.gotoComment').click(function() {
        $('.writeComment .title').switcher('switchOn');
    });   
});