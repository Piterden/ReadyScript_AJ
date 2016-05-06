/**
* Инициализирует обновляемые блоки на странице
*/
var updatable = {
    initialized: false,
    dom: {
        defaultContainer: '.updatable:first', //Контейер, который будет обновлен по умолчанию
        container: '.updatable',
        callUpdate: '.call-update',
        formCallUpdate: 'form.form-call-update'
    },
    
    init: function() {
        $(updatable.dom.container).off('.updatable').on('rs-update.updatable', updatable.updateContainer);
        $(updatable.dom.callUpdate).off('.updatable').on('click.updatable', updatable.callUpdate);
        $(updatable.dom.formCallUpdate).off('.updatable').on('submit.updatable', updatable.formCallUpdate);
        updatable.initState();
        updatable.initialized = true;
    },
    
    initState: function() {
        if (updatable.initialized) return;
        var blocksUrl = updatable.getParsedHash();
        
        $.each(blocksUrl, function(block, url) {
            if (block == 'u') {
                var div = updatable.dom.defaultContainer;
            } else {
                var div = '.updatable[data-update-block-id="'+block+'"]';
            }
            $(div).trigger('rs-update', [decodeURIComponent(url), null, {noUpdateHash:true}]);
        });
    },
    
    getParsedHash: function() {
        var result = {};
        var hash = decodeURIComponent(location.hash.slice(1));
        
        if (hash.match(/([\w-]+='.*?')/g)) {
            var parts = hash.match(/([\w-]+='.*?')/g);
            $.each(parts, function(i, value) {
                var item = value.match(/([\w-]+)='(.*?)'/);
                result[item[1]] = item[2];
            });
        }
        return result;
    },
    
    setHash: function(blocksUrl) {
        var hash = [];
        $.each(blocksUrl, function(block, url) {
            hash.push( block+"='"+encodeURIComponent(url)+"'");
        });
        
        location.hash = hash.join(';');
    },
    
    updateContainer: function(e, url, data, ajaxParams) {
        if (!ajaxParams) ajaxParams = {};
        
        if (!url) {
            url = $(this).data('url');
        }
        if (url == '') return false;
        
        var $frame = $(this);
        var url_get = url;
        if (data) {
            url_get = url+(url.indexOf('?') > -1 ? '&'+$.param(data) : '?'+$.param(data));
        }
        $frame.data('url', url_get); //Устанавливаем последний 
       
        $.ajaxQuery($.extend({
            url: url,
            dataType: 'json',
            data: data,
            success: function(response) {
                if (response.close_dialog) { //Закрываем внешнее окно, если оно присутствует.
                    $frame.closest('.dialog-window').dialog('close');
                } else {
                    //Изменяем URL
                    if (!ajaxParams.noUpdateHash) {
                        var blockId = $frame.data('updateBlockId') ? $frame.data('updateBlockId') : 'u';
                        var blocks = updatable.getParsedHash();
                        blocks[blockId] = url;
                        updatable.setHash(blocks);
                    }
                    
                    if ($frame.data('updateReplace')) {
                        var newFrame = $(response.html);
                        $frame.replaceWith(newFrame);
                        $frame = newFrame;
                    } else {
                        $frame.html(response.html);
                    }
                    $frame.trigger('new-content');
                }
            }
        }, ajaxParams));

        e.stopPropagation();
    },
    
    callUpdate: function(e) {
        var href = $(this).data('updateUrl') ? $(this).data('updateUrl') : $(this).attr('href'),
            ajaxParams = {};
        
        if ($(this).hasClass('no-update-hash')) ajaxParams.noUpdateHash = true;
        updatable.updateTarget(e.target, href, null, ajaxParams);
        return false;
    },
    
    formCallUpdate: function(e) {
        var data = $(this).serializeArray();
        var url = $(this).attr('action') ? $(this).attr('action') : location.pathname;
        var ajaxParams = {};
        if ($(this).hasClass('no-update-hash')) ajaxParams.noUpdateHash = true;
        updatable.updateTarget(e.target, url, data, ajaxParams);
        return false;
    },
    
    /**
    * Обновляет контейнер, на который указывает элемент или контейнер по умолчанию
    */
    updateTarget: function(element, url, data, ajaxParams) {
        var $el = $(element), targetContainer;

        if ($el.data('updateContainer')) {
            targetContainer = $el.data('updateContainer');
        } else if ($el.closest(updatable.dom.container).length) {
            targetContainer = $el.closest(updatable.dom.container);
        } else {
            targetContainer = updatable.dom.defaultContainer;
        }

        var target = $(targetContainer);
        if (target.length && (url || target.data('url'))) {
            target.trigger('rs-update', [url, data, ajaxParams]);
            return true;
        }
        return false;
    }
}

$.contentReady(function() {
    updatable.init();
});