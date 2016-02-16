/**
* Скрипт активирует необходимые функции на странице просмотра товаров
*/
$(window).load(function() {
    /**
    * Прокрутка нижних фото у товара в карточке
    */ 
    var carousel = $('.productGalleryWrap .gallery').jcarousel().swipeCarousel(),
        recommended = $('.recommended .gallery').jcarousel().swipeCarousel();
     
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
});

$(function() {
   /**
   * Открытие главного фото товара в colorbox
   */
   $('.no-touch .product .viewbox[rel="bigphotos"]').colorbox({
       rel:'bigphotos',
       className: 'titleMargin',
       opacity:0.2
   });
   
   /**
   * Нажатие на маленькие иконки фото
   */
   $('.gallery .preview').click(function() {
        var n = $(this).data('n');
        $('.product .mainPicture').addClass('hidden');
        $('.product .mainPicture[data-n="'+n+'"]').removeClass('hidden');
        return false;
    });
    
    var photoEx = new RegExp('#photo-(\\d+)');
    var res = photoEx.exec(location.hash);
    if ( res != null ) {
        $('.gallery .preview[data-n="'+res[1]+'"]').click();
    }
    
    //Переключение показа написания комментария
    $('.gotoComment').click(function() {
        $('.writeComment .title').switcher('switchOn');
    });
});