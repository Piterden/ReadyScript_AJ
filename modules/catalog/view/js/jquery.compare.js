/**
* Плагин, инициализирующий работу функции сравнения товаров
*/
(function( $ ){
    /**
    * Инициализирует блок сравнения товаров
    */
    $.compareBlock = function( method ) {
        var defaults = {
            compareBlock: '#compareBlock',    //Блок с отображением товаров для сравнения  
            compareButton:'.compare',         //Кнопка сравнить
            activeCompareClass: 'inCompare',  //Класс указывающий, что товар в сравнении
            ulCompare:'.compareProducts',     //Список товаров в сравнении
            doCompare:'.doCompare',           //Кнопка открытия отдельного окна сравнения товаров
            removeItem:'.remove'              //Кнопка удаления товара из сравнения
        },
        $this = $('#compareBlock');
        
        if (!$this.length) {
            console.log('element #compareBlock not found');
            return;    
        }
        
        var data = $this.data('compareBlock');
        if (!data) { //Инициализация
            data = {
                options: defaults
            }; 
            $this.data('compareBlock', data);
        }
        
        //public
        var methods = {
            /**
            * Иницилизация плагина, отработка событий
            * 
            * @param initoptions - массив с параметрами
            */
            init: function(initoptions) {
                data.options.url = $this.data('compareUrl');
                data.options     = $.extend(data.options, initoptions);
                $('body').on('click.compare', data.options.compareButton, toggleCompare);
                $this.on('click', data.options.removeItem, removeItem);
                $this.on('click', data.options.doCompare, methods.compare);  
            },
            
            /**
            * Добавление товара в сравнение
            * 
            * @param integer product_id - id товара
            */
            add: function(product_id) {
                $('[data-id="'+product_id+'"] '+data.options.compareButton).addClass(data.options.activeCompareClass);
                $.post(data.options.url.add, {id: product_id}, function(response) {
                   $(data.options.ulCompare).html(response.html);

                    if (!$this.is(':visible')) {
                        $this.slideDown();
                    }                   
           
                }, 'json');
            },
            
            /**
            * Удаление товара из сравнения
            * 
            * @param integer product_id - id товара
            */
            remove: function(product_id) {
                $('[data-id="'+product_id+'"] '+data.options.compareButton).removeClass(data.options.activeCompareClass);
                var item = $('[data-compare-id="'+product_id+'"]', $this).css('opacity', 0.5);
                $.post(data.options.url.remove, {id: product_id}, function(response) {
                    if (response.success) {
                        methods.removeVisual(product_id);
                    }                         
                }, 'json');                
            },
            
            /**
            * Удаление товара из сравнения (визуальное отображение удаления товара)
            * 
            * @param integer product_id - id товара
            */
            removeVisual: function(product_id) {
                $('[data-id="'+product_id+'"] '+data.options.compareButton).removeClass(data.options.activeCompareClass);
                var item = $('[data-compare-id="'+product_id+'"]', $this).css('opacity', 0.5);
                if (item.siblings().length) {
                    item.remove();    
                } else {
                    $this.slideUp(function() {
                        item.remove();
                    });
                }                                
            },
            
            /**
            * Открытие окна стравнения
            * 
            * @returns {Boolean}
            */
            compare: function() {
                var url = $(this).attr('href');
                window.open(url, 'compare', 'top=170, left=100, scrollbars=yes, menubar=yes, resizable=yes');
                return false;                
            }
        };
        
        //private
        /**
        * Переключение статусов на кнопке сравнения (Активно или нет) для одного товара
        * 
        */
        var toggleCompare = function() {
            var id = $(this).closest("[data-id]").data('id');
            
            if ($(this).hasClass(data.options.activeCompareClass)) {
                methods.remove( id );
            } else {
                methods.add( id );
            }
            return false;
        },
        
        /**
        * Проверяет пустой ли список сравнения или нет.
        * Если да то визуально разворачивает список, если нет сворачивает список
        * 
        */
        checkEmpty = function() {
            if (!$(data.options.ulCompare).children().length) {
                $this.slideUp();
            } else {
                if (!$this.is(':visible')) {
                    $this.slideDown();
                }
            }            
        },
        
        /**
        * Функция удаления из сравнения
        * 
        */
        removeItem = function() {
            var id = $(this).closest('[data-compare-id]').data('compareId');
            methods.remove(id);
        }
        
  
        if ( methods[method] ) {
            methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        }       
    }


})( jQuery );


$(function() {
    $.compareBlock();
});