/**
* Редактор блоков
*/

(function( $ ){
  $.fn.blockEditor = function(options) {  
      
      options = $.extend({
          sortSectionUrl: '',
          sortBlockUrl: ''
      }, options);
      
      return this.each(function() {
          var context = this;
          
          
          //Разворачиваем инструменты у широких блоков, секций, ...
          var expandTools = function() {
              $('.gs-manager .rs-list-button').each(function() {
                  var common_tools = $(this).parent();
                  var width = 0;
                  $(this).find('.rs-dropdown li').each(function() {
                      width = width + 28;
                  });
                  
                  var need_width = ($(this).offset().left - common_tools.offset().left) + width;
                  $(this).closest('.block, .area, .row, .gs-manager').toggleClass('wide', common_tools.width() > need_width);
              });
          }          
          
          expandTools();
          
          var redrawColumns = function() {
              var current_device = $('.device-selector li.act').data('device');              
              var devices = ['lg', 'md', 'sm', 'xs'];
              $('.pageview .section-width').each(function() {
                  
                  var start = devices.indexOf(current_device);
                  var result = '';
                  for(var i=start; i<4; i++) {
                      var width = $(this).data(devices[i] + '-width');
                      if (width) {
                          result = width; 
                          break;
                      }
                  }
                  $(this).text(result ? result : '100%');
              });
          };
          
          redrawColumns();
          //Активируем переключатель устройств
          $('.device-selector li').off('.blockeditor').on('click.blockeditor', function() {
              var current_device = $(this).addClass('act').data('device');
                            
              $(this).siblings().removeClass('act');
              $('.pageview').removeClass('xs sm md lg').addClass(current_device);
              
              $.cookie('page-constructor-device', current_device);
              redrawColumns();
              expandTools();
          });
          
          //Активируем переключатель активности сетки
          $('.gs-manager .grid-switcher').off('.blockeditor').on('click.blockeditor', function() {
              var is_off = $(this).toggleClass('off').is('.off');
              var container = $(this).closest('.gs-manager').toggleClass('grid-disabled', is_off);
              $.cookie('page-constructor-disabled-' + container.data('container-id'), is_off ? 1 : null);
              expandTools();
          });
          
          $('.gs-manager .block .iswitch').off('.blockeditor').on('click.blockeditor', function() {
              var block = $(this).closest('.block');
              block.toggleClass('on');
              
              $.ajaxQuery({
                  url: options.toggleViewBlock,
                  data: {
                    id: block.data('blockId')
                  }
              });                  
          });
          
          //Включаем сортировку секций
          $('.sort-sections', context).sortable({
              forcePlaceholderSize: true,
              placeholder: 'sortable-placeholder',
              handle: '.drag-handler',
              start: function(event, ui) {
                  ui.placeholder.addClass(ui.item.attr('class')).append('<div class="border"></div>');
              },
              change: function(event, ui) {
                  if (!ui.item.parent().hasClass('container')) { //Если перемещение произошло в секции
                      $('>.area', ui.item.parent()).removeClass('alpha omega');
                      $('>.area:not(.ui-sortable-helper):first', ui.item.parent()).addClass('alpha');
                      $('>.area:not(.ui-sortable-helper):last', ui.item.parent()).addClass('omega');
                  }
              },
              stop: function(event, ui) {
                  if (!ui.item.parent().hasClass('container')) { //Если перемещение произошло в секции
                      $('>.area', ui.item.parent()).removeClass('alpha omega');
                      $('>.area:first', ui.item.parent()).addClass('alpha');
                      $('>.area:last', ui.item.parent()).addClass('omega');
                  }
                  
                  $.ajaxQuery({
                      url: options.sortSectionUrl,
                      data: {
                        section_id: ui.item.data('sectionId'), 
                        position: ui.item.prevAll().length
                      }
                  });
              }
          });
          
          //Включаем сортировку блоков
          $('.sort-blocks', context).sortable({
              handle: '.drag-block-handler',
              stop: function(event, ui) {
                  $.ajaxQuery({
                      url: options.sortBlockUrl,
                      data: {
                        block_id: ui.item.data('blockId'), 
                        position: ui.item.prevAll().length
                      }
                  });                  
              }
          });
          
          //private
          var 
              sourceContainer,
              onStartContainerDrag = function(e) {
                  sourceContainer = $(this).closest('.gs-manager').get(0);
                  $(sourceContainer).addClass('sourceContainer');
                  $('.gs-manager:not(".sourceContainer")')
                    .bind('mouseenter.cDrag', onContainerEnter)
                    .bind('mouseleave.cDrag', onContainerLeave)
                    
                  
              },
              onContainerEnter = function(e) {
                  if (sourceContainer) {
                      $('.destinationContainer').removeClass('destinationContainer');
                      $(this).addClass('destinationContainer').append('<div class="dstOverlay" />');
                  }
              },
              onContainerLeave = function(e) {
                  if (sourceContainer) {
                      $(this).removeClass('destinationContainer');
                      $('.dstOverlay', this).remove();
                  }
              },
              onStopContainerDrag = function(e) {
                  if (sourceContainer) {
                      //Перемещаем контейнеры
                      var dst = $('.destinationContainer:first');
                      
                      if (dst.length) {
                          var dst_clone = dst.clone().insertAfter(dst);
                          dst.insertAfter(sourceContainer);
                          dst_clone.replaceWith(sourceContainer);
                          
                          var 
                            source_id = $(sourceContainer).data('containerId'),
                            destination_id = dst.data('containerId');
                          
                          $.ajaxQuery({
                              url: options.sortContainerUrl,
                              data: {
                                source_id: source_id,
                                destination_id: destination_id
                              }
                          });
                      }
                      
                      //Завершаем перемещение
                      $(sourceContainer).removeClass('sourceContainer');
                      sourceContainer = null;
                      $('.gs-manager').unbind('.cDrag');
                      $('.destinationContainer').removeClass('destinationContainer');
                      $('.dstOverlay').remove();
                  }
              };
          
          $('.gs-manager > .commontools > .drag-handler').on({
              mousedown: onStartContainerDrag,
          });
          
          $('body').mouseup(onStopContainerDrag);
          
      });
      
  };
})( jQuery );