/**
* Плагин, активирует просмотр древовидных списков
*/
(function( $ ){

  $.fn.treeview = function( type ) {  
      
    return this.each(function() {
        if ($(this).data('treeview')) return false;
        $(this).data('treeview', {});
        
        var activetree = this;
        var $treeview = $('.treebody', this);
        
        var toggle = function( e )
        {
            var parent = $(e.target).closest('li');
            var jquery_el = $(e.target);
            var el_class = jquery_el.attr('class');
            if (el_class.indexOf('plus') > -1) {
                //Отображаем подпункты
                var newClass = jquery_el.attr('class').replace('plus','minus');
                $('>ul', parent).show();
                
            } else if (el_class.indexOf('minus') > -1) {
                //Скрываем подпункты
                var newClass = jquery_el.attr('class').replace('minus','plus');
                $('>ul', parent).hide();
            }
            
            if (newClass) jquery_el.attr('class', newClass);
            updatecookie.call(activetree);
            return false;
        }
        
        var openAll = function()
        {
            $('ul.childroot', $treeview).show();
            $('.plus', $treeview).attr('class','minus');
            $('.endplus', $treeview).attr('class','endminus');
            
            updatecookie.call(activetree);
        }
        
        var closeAll = function()
        {
            $('ul.childroot', $treeview).hide();
            $('.minus', $treeview).attr('class','plus');
            $('.endminus', $treeview).attr('class','endplus');
            
            updatecookie.call(activetree);
        }
        
        var updatecookie = function()
        {
            if (!$(this).data('uniq')) return false;

            var ids = new Array();
            $('.plus, .endplus', $treeview).each(function() {
                ids.push($(this).closest('li').data('id'));
            });
            
            if (ids.length > 0)
            {
                $.cookie($(this).data('uniq'), ids.join(','), {expires: 365})
            } else {
                $.cookie($(this).data('uniq'), null);
            }
        }

        $('.toggle', this).click(toggle);
        $('.allplus', this).click(openAll);
        $('.allminus', this).click(closeAll);
        
        /**
        * Устанавливаем красные маркеры
        */
        $('.chk input[type="checkbox"]', $treeview).change(function() {
            if (this.checked) {
                $(this).closest('li').find('>.line>.redmarker').addClass('r_on');
                $(this).parents('.treebody li').each(function() {
                    $('>.line>.redmarker', this).addClass('r_on');
                })
                
            } else {
                if (!$(this).closest('li').find('.r_on').length) {
                    $(this).closest('li').find('>.line>.redmarker').removeClass('r_on');
                }
                
                $(this).parents('.treebody li').each(function() {
                    if (!$('ul .r_on', this).length && !$('.chk input:checked', this).length) {
                        $('>.line>.redmarker', this).removeClass('r_on');
                    }
                });
            }
        });            
        
        
        $('.line, .chk', $treeview).each(function() {
            $(this).hover(function() {
                if (!$(activetree).data('overDisabled')) {
                    $(this).closest('li').addClass('over');
                }
            }, function() {
                $(this).closest('li').removeClass('over');
                $('.over', this).removeClass('over');
            });
        });
        
        //Инициализируем все checkbox'ы которые уже активны
        $('.chk input[type="checkbox"]:checked', $treeview).trigger('change');
                
    }); //each

  };
})( jQuery );

$.contentReady(function() {    
    $('.activetree').treeview();
    
    //Инициализайия сокращенного вида
    $('.category-selector').rsDropdownButton({
        handler: '.dropdown-handler, .current',
        box: '.list'
    });
});