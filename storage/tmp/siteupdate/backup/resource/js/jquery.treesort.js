/**
* Плагин, активирует просмотр древовидных списков
*/
(function( $ ){

  $.fn.treesort = function( type ) {  
    var $tree = this;
    
    return $('.treesort',this).each(function() {
        var _this = this;
        var $treesort = $(this);
            
        var down = function(e)
        {
            _this.current = $(e.currentTarget).parents('li:first');
            _this.localcontext = $(e.currentTarget).parents('ul:first');
            
            $tree.data('overDisabled', true); //Устанавливаем глобальную переменную - не выделять при наведении элементы дерева
            $('body').one('mouseup', up);        
            _this.current.one('mouseup', function() {
                $(this).addClass('over');
            });
            
            _this.current.addClass('drag').removeClass('over');
            _this.lastItem = null;            
            unselection();
            bind();
        }
        
        var up = function(e)
        {
            if (!_this.current) return false;
            
            _this.current.removeClass('drag');
            clear();
            
            $tree.data('overDisabled', false);
            
            if (_this.lastItem && _this.current) {
                $.getJSON($treesort.data('sortUrl'), 
                    {
                        from: _this.current.data('id'),
                        to: _this.lastItem.data('id'),
                        flag: _this.lastDirection
                    }, function(data) {
                        if (!data.success) {
                            //Должна происходить отмена перемещения
                        }
                    });
            }
        }    
        
        var clear = function()
        {
            if (_this.prevLI) $('>.line',_this.prevLI).unbind('.treemove');
            if (_this.nextLI) $('>.line',_this.nextLI).unbind('.treemove');
        }
        
        var bind = function()
        {        
            clear();
            _this.prevLI = _this.current.prev('li:not([notmove]):first');
            if (_this.prevLI.length>0 && !_this.prevLI.hasClass('tree_head') ) {
                $('>.line',_this.prevLI).one('mouseover.treemove',moveup);
            }
            
            _this.nextLI = _this.current.next('li:not([notmove]):first');
            if (_this.nextLI.length>0) {
                $('>.line',_this.nextLI).one('mouseover.treemove',movedown);
            }        
        }
        
        var moveup = function(e)
        {
            if (!_this.current) return false;
            if (!_this.prevLI) return false;        
            move(_this.current, _this.prevLI);
            _this.lastDirection = 'up';
            bind();
        }
        
        var movedown = function(e)
        {            
            if (!_this.current) return false;
            if (!_this.nextLI) return false;        
            move(_this.current, _this.nextLI);
            _this.lastDirection = 'down';
            bind();
        }
            
        var move = function(from, to)
        {        
            var clon = from.clone();
            clon.insertAfter(from);
            
            from.insertAfter(to);
            to.insertAfter(clon);
            clon.remove();
            
            _this.lastItem = to;
            checkTreeIcons(from, to);
        }
        
        var unselection = function()
        {
            $treesort.css('-moz-user-select', 'none');
            $treesort.css('-khtml-user-select', 'none');
            $treesort.css('user-select', 'none');
            document.ondragstart = function() { return  false }
            document.body.onselectstart = function() { return false }        
        }
        
        var checkTreeIcons = function(from, to)
        {
            return false;
            
            var from_class = null;
            var to_class = null;
            
            var classList = ['branch', 'plus', 'minus', 'endplus', 'endminus', 'end'];
            for (i=0; i<classList.length; i++) {
                if (!from_class && from.hasClass(classList[i])) from_class = classList[i];
                if (!to_class && to.hasClass(classList[i])) to_class = classList[i];
            }
            if (to_class.indexOf('end') == -1 && from_class.indexOf('end') == -1) return false;
            
            if (to_class.indexOf('end')>-1) 
            {
                var new_from = 'end';
                if (from_class == 'plus') new_from = new_from + 'plus';
                if (from_class == 'minus') new_from = new_from + 'minus';
                
                var new_to = 'branch';
                if (to_class == 'endplus') new_to = 'plus';
                if (to_class == 'endminus') new_to = 'minus';
            }

            if (from_class.indexOf('end')>-1) 
            {
                var new_from = 'branch';
                if (from_class == 'endplus') new_from = 'plus';
                if (from_class == 'endminus') new_from = 'minus';
                
                var new_to = 'end';
                if (to_class == 'plus') new_to = new_to + 'plus';
                if (to_class == 'minus') new_to = new_to + 'minus';
            }        
            
            from.removeClass('plus minus endplus endminus branch end');
            to.removeClass('plus minus endplus endminus branch end');
            from.addClass(new_from);
            to.addClass(new_to);
        }
        
        $('.move', $treesort).mousedown(down);
    });
  }
})( jQuery );

$.contentReady(function() {
    $('.activetree').treesort();
});