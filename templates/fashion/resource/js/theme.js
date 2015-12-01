$(function() {    
   $('#up').click(function () { 
        $('body, html').animate({ scrollTop: 0 });  
        return false; 
    });
    
    //Инициализируем корзину
    $.cart({
        saveScroll: '.scrollBox',
        cartItemRemove: '.cartTable .iconRemove',
        cartTotalPrice: '.floatCartPrice',
        cartTotalItems: '.floatCartAmount'
    }); 
    
    $('.inDialog').openInDialog();
    $('.tabs').activeTabs();

    //Инициализируем быстрый поиск по товарам
    $(window).resize(function() {
        $( ".query.autocomplete" ).autocomplete( "close" );
    });
    
    /**
    * Автозаполнение в строке поиска 
    */
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
    
    //Инициализируем открытие картинок во всплывающем окне
    $('a[rel="lightbox"], .lightimage').colorbox({
       rel:'lightbox',
       className: 'titleMargin',
       opacity:0.2
    });     
  
}); 

$(window).load(function() {
    $('.products .photoView').on('mouseover', function() {
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
});