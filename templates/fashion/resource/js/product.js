/**
* Скрипт активирует необходимые функции на странице просмотра товаров
*/
$(window).load(function() {
    /**
    * Прокрутка нижних фото у товара в карточке
    */ 
    $('.gallery .wrap').jcarousel({
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
    })
    
    /**
    * Увеличение фото в карточке
    */
    $('.productImages .zoom').each(function() {
        $(this).zoom({
            url: $(this).data('zoom-src'),
            onZoomIn: function() {
                $(this).siblings('.winImage').css('visibility', 'hidden');
                
            },
            onZoomOut: function() {
                $(this).siblings('.winImage').css('visibility', 'visible');
            }            
        });
    });
    
    
});

$(function() {
   /**
   * Открытие главного фото товара в colorbox
   */ 
   $('.product .main a.item[rel="bigphotos"]').colorbox({
       rel:'bigphotos',
       className: 'titleMargin',
       opacity:0.2
   });
   
   /**
   * Нажатие на маленькие иконки фото
   */
   $('.gallery .preview').click(function() {
        var n = $(this).data('n');
        $('.product .main .item').addClass('hidden');
        $('.product .main .item[data-n="'+n+'"]').removeClass('hidden');
        
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