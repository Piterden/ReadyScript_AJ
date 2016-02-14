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

    $(window).resize(function() {
        var $gal = $('.gallery');
        var $galWrap = $gal.children('.wrap');
        $galWrap.jcarousel('scroll', 0);
        $galWrap.height($gal.prev().height() - 30);
    });
    $(window).trigger('resize');

    $('.control').on({
        'inactive.jcarouselcontrol': function() {
            console.log('inactive', this);
            $(this).addClass('disabled');
        },
        'active.jcarouselcontrol': function() {
            console.log('active', this);
            $(this).removeClass('disabled');
        }
    });

    // Нажатие на стрелки маленьких иконок фото
    $('.control.prev3').jcarouselControl({
        target: '-=3'
    });
    $('.control.next3').jcarouselControl({
        target: '+=3'
    });

    // Нажатие на стрелки больших фото
    $('.control.prev').on('click', function() {
        var $act = $('.gallery li.active');
        if ($act.prev('li').length > 0) {
            $act.prev('li').children('a').trigger('click');
        } else {
            $act.siblings('li').eq(-1).children('a').trigger('click');
            $('.gallery .wrap').jcarousel('scroll', -1);
        }
    });
    $('.control.next').on('click', function() {
        var $act = $('.gallery li.active');
        if ($act.next('li').length > 0) {
            $act.next('li').children('a').trigger('click');
        } else {
            $act.siblings('li').eq(0).children('a').trigger('click');
            $('.gallery .wrap').jcarousel('scroll', 0);
        }
    });




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

    //Открытие главного фото товара в colorbox
    $('.product .main a.item[rel="bigphotos"]').colorbox({
        rel: 'bigphotos',
        className: 'titleMargin',
        opacity: 0.2
    });

    //Нажатие на маленькие иконки фото
    $('.gallery .preview').on('click', function() {
        $(this).parent('li').addClass('active').siblings().removeClass('active');
        var n = $(this).data('n');
        $('.product .main .item').addClass('hidden');
        $('.product .main .item[data-n="' + n + '"]').removeClass('hidden');

        return false;
    });

    var photoEx = new RegExp('#photo-(\\d+)');
    var res = photoEx.exec(location.hash);
    if (res !== null) {
        $('.gallery .preview[data-n="' + res[1] + '"]').trigger('click');
    }

    //Переключение показа написания комментария
    //$('.gotoComment').click(function() {
    //    $('.writeComment .title').switcher('switchOn');
    //});

    // Colorbox материалов и функций
    $('#materials .title').add('#functions .title').colorbox({
        width: 770,
        opacity: 0.4,
        html: function() {
            return $(this).next('.description').clone().show();
        }
    });

    $('[data-toggle="tooltip"]').tooltip({
        placement: 'top'
    });

});
