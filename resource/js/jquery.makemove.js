/**
* ReadyScript
* Plug-in активирует кнопки влево, вправо в списке виджетов
*/
$.fn.makeMove = function(options) 
{
        var opt = $.extend({
                step: 380 //Шаг в px, с которым будет перемещаться линейка элементов
            }, options);
        
        $(this).each(function()
        {
            var context = $(this);
            var items_width = 0;
            
            getInt = function(value)
            {
                var result = parseInt(value);
                return (isNaN(result)) ? 0 : result;
            }
            
            checkArrowActive = function()
            {
                var start = getInt($('.scroll-container').css('margin-left'));
                var conainer_width = getInt($('.scroll-wrapper', context).get(0).offsetWidth);
                var max_margin = -(items_width-conainer_width);
                
                //Устанавливаем класс неактивности стрелки
                if (start >= 0) {
                    $('.prev', context).addClass('p_noactive');
                } else {
                    $('.prev', context).removeClass('p_noactive');
                }
                
                if (start <= max_margin) {
                    $('.next', context).addClass('n_noactive');
                } else {
                    $('.next', context).removeClass('n_noactive');
                }
            }            
            
            //Вычисляем общую ширину всего содержимого контейнера
            $('.scroll-container>*', context).each(function() {
                items_width = items_width + 
                    $(this).get(0).offsetWidth + 
                    getInt($(this).css('margin-left'))+
                    getInt($(this).css('margin-right'));                    
            });
            
            $('.next', context).mousedown(function() {
                var conainer_width = getInt($('.scroll-wrapper', context).get(0).offsetWidth);
                
                var max_margin = -(items_width-conainer_width);
                var start = getInt($('.scroll-container', context).css('margin-left'));
            
                if (max_margin < start) {
                    var delta = (max_margin <= start-opt.step) ? opt.step : start-max_margin;
                    $('.scroll-container', context).animate({marginLeft: start-delta}, 200, function() {
                        checkArrowActive();
                    });
                }
                
            });
            
            $('.prev', context).mousedown(function() {
                var start = getInt($('.scroll-container', context).css('margin-left'));
                if (start < 0)
                {
                    var delta = (0 >= start+opt.step) ? opt.step : -start;
                    $('.scroll-container', context).animate({marginLeft: start+delta}, 200, function() {
                        checkArrowActive();
                    });
                }
            });
            
            checkArrowActive();
        });
};

$(function() {
    $('.makemove').makeMove();
})