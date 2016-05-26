/**
 * jQuery Iml Delivery Plugin
 *
 * Require to be included:
 *
 * 		http://api-maps.yandex.ru/2.1/?lang=ru_RU
 *
 * Original author: @piterden
 * efremov.a.denis@gmail.com
 *
 */
;(function($, window, document, ymaps, undefined) {

    'use strict';
    // console.log(ymaps);

    var pluginName = 'Iml',
        /**
         * Default options
         * @type {Object}
         */
        defaults = {
            url: '/', // AJAX request url
            delivery_id: 1, // RS_Param

            /** Map settings */
            map: {
                clusterize: true,
                grid_size: 32,
            },

            /** Request params */
            request: {
                Job: 'С24',
                RegionFrom: 'САНКТ-ПЕТЕРБУРГ',
                RegionTo: 'САНКТ-ПЕТЕРБУРГ',
                Volume: '1',
                Weigth: '1',
                // SpecialCode: '1',
            },

            /** CSS mappings */
            id_el: { // Ids of elements
                modal: 'mapModal',
                map: 'map',
                region_list: 'selectRegionCombo',
                sd_list: 'sdlist',
            },
            cls: { // Classes of elements
                overlay: 'loading',
                map_container: 'mapContainer',
                map_wrapper: 'mapWrapper',
                map_ymaps: 'ymaps-2-1-31-map ymaps-2-1-31-i-ua_js_yes ymaps-2-1-31-map-ru',
                region_list: 'selectRegion',
            },
            style: { // Styles of elements
                map_map: 'height:400px; width:70%; float:left;',
                map_ymaps: 'width: 70%; height: 400px;',
                map_li_wrap: 'position:relative; overflow: auto; height:400px; width:30%; padding:0; font-size:12px;',
            },

            /** Templates */
            templates: {
                overlay: function(o) {
                    return $('<div/>', { class: o.cls.overlay });
                },
                map: function(o) {
                    var d_id = o.delivery_id,
                        c = o.cls,
                        i = o.id_el,
                        s = o.style;
                    return $('<div/>', { class: c.map_container }).append(
                        $('<select/>', { 'id': i.region_list + '_' + d_id, 'class': c.region_list }).data('size', '8')).append(
                        $('<div/>', { 'class': c.map_wrapper }).append(
                            $('<div/>', { 'id': i.map + '_' + d_id, 'style': s.map_map }).append(
                                $('<ymaps/>', { 'class': c.map_ymaps, 'style': s.map_ymaps })))).append(
                        $('<div/>', { 'style': s.map_li_wrap, 'class': 'scrollParent' }).append(
                            $('<ul/>', { 'id': i.sd_list + '_' + d_id })));
                },
                modal: function(o) {
                    return $('<div/>', { 'class': 'modal fade', 'id': o.id_el.modal, 'tabindex': '-1', 'role': 'dialog', 'aria-labelledby': 'mapModalLabel' }).append(
                        $('<div/>', { 'class': 'modal-dialog modal-lg', 'role': 'document' }).append(
                            $('<div/>', { 'class': 'modal-content' }).append(
                                $('<div/>', { 'class': 'modal-header' }).append(
                                    $('<button/>', { 'class': 'close', 'type': 'button', 'aria-label': 'Отменить', })
                                    .data('dismiss', 'modal').append(
                                        $('<span/>', { 'aria-hidden': 'true', 'content': '&times;' })))).append(
                                $('<div/>', { 'class': 'modal-body' })).append(
                                $('<div/>', { 'class': 'modal-footer' }).append(
                                    $('<div/>', { 'class': 'buttons' }).append(
                                        $('<button/>', { 'type': 'submit', 'class': 'btn btn-default' })
                                        .html('Отменить')
                                        .data('dismiss', 'modal')).append(
                                        $('<button/>', { 'type': 'submit', 'class': 'btn btn-primary submitModal' })
                                        .html('Выбрать')
                                        .data('dismiss', 'modal'))))));
                }
            },
            chunks: {
                balloon: function(el, o) {
                    return $('<div/>').append($('<span/>', { 'style': 'font-weight: bold;' }).html(el.Name)).append('<br/>')
                        .append($('<span/>').html(el.Address)).append('<br/>')
                        .append($('<span/>').html('Телефон: ' + el.Phone)).append('<br/>')
                        .append($('<span/>').html('Оплата картой: ' + el.PaymentCard)).append('<br/>')
                        .append($('<span/>').html('Примерочная: ' + el.fitting)).append('<br/>')
                        .append($('<span/>').html('Время работы: ' + el.WorkMode)).append('<br/>')
                        .append($('<button/>', {
                                'class': 'balloonSelectSd',
                                'id': 'bal_sd_' + o.delivery_id + '_' + el.RequestCode,
                                // 'onclick': 'this.submitModal("' + o.request.RegionTo + '","' + el.RequestCode + '");'
                                'onclick': '$("#sd_");$(".modal").modal("hide");$("#region_id_to").val("' +
                                    o.request.RegionTo + '");$("#request_code").val("' +
                                    el.RequestCode + '").trigger("change");'
                            })
                            // .data('rc', el.RequestCode)
                            // .data('rt', el.RegionTo)
                            .html('Выбрать'));
                },
                sd_list_item: function(el, o) {
                    return $('<li/>', { 'class': 'selectSd' + el.active, 'id': 'sd_' + o.delivery_id + '_' + el.RequestCode })
                        .data('code', el.RequestCode).append(
                            $('<span/>', { 'style': 'font-weight: bold;' }).html(el.Name)).append('<br/>')
                        .append($('<span/>').html(el.Address)).append('<br/>')
                        .append($('<span/>').html('Телефон: ' + el.Phone)).append('<br/>')
                        .append($('<span/>').html('Оплата картой: ' + el.PaymentCard)).append('<br/>')
                        .append($('<span/>').html('Примерочная: ' + el.fitting)).append('<br/>')
                        .append($('<span/>').html('Время работы: ' + el.WorkMode)).append('<br/>');
                }
            }
        },

        html = {},

        /********************
         * Plugin body
         * ******************
         * @type {Object}
         */
        Iml = {
            /**
             * Конструктор здесь
             * @param mixed
             * @param button element
             * @return this
             */
            init: function(op, el) {
                // public vars
                this.el = el;
                this.$el = $(this.el);
                this.o = $.extend(true, {}, defaults, op);
                this._defaults = defaults;
                this._name = pluginName;
                this.ctx = '#delivery_' + this.o.delivery_id;

                this.currency = this.o.currency || {};
                this.delivery_cost_json = this.o.delivery_cost_json || {};
                this.service_id = this.o.service_id || {};

                // construct chain
                this._build();
                this._bindInitEvents();

                // this.;
                /**********`
                 * @public
                 */
                return;
            },

            /***********
             * @private
             */
            /** Генерирует HTML разметку */
            /** Building HTML and caching it */
            _build: function() {
                this.html = this.html || {};
                for (var tpl in this.o.templates) {
                    if (this.o.templates.hasOwnProperty(tpl)) {
                        this.html[tpl] = this.o.templates[tpl].call(this, this.o);
                    }
                }
                return this;
            },

            /**
             * Вешаем обработчики при загрузке страницы
             */
            _bindInitEvents: function() {
                var _this = this;

                $('#order-form').unbind('submit')
                    .on('submit', function(e) {
                        if ($('#sd_is_selected').val() == 0) {
                            showError('Выберите, пожалуйста, пункт выдачи заказа!');
                            return false;
                        }
                    });

                $('#request_code').unbind('change')
                    .on('change', function() {
                        var $this = $(this),
                            rc = $this.val(),
                            rt = $this.prev().val();
                        _this.updateInfo(rt, rc);
                        $('#sd_is_selected').val(1);
                    });

                $(this.el).unbind('click')
                    .on('click', function(e) {
                        e.preventDefault();
                        var $body = $('body'),
                            $mapBl = $(_this.html.map[0]),
                            $modBl = $(_this.html.modal[0]);

                        $(this).addClass('map-open');
                        $modBl.appendTo($body).modal('show');

                        $modBl.find('.modal-content').addClass('overlay');
                        $modBl.find('.modal-body').append($mapBl);
                        $modBl.find('.modal-header').css('height', '56px');

                        _this._loadRegions();
                        _this._initMap(false);
                    });
                return this;
            },

            /**
             * Вешаем обработчики при открытии карты
             */
            _bindMapEvents: function() {
                var _this = this,
                    o = this.o;

                $('#' + o.id_el.region_list + '_' + o.delivery_id).unbind('change')
                    .on('change', function() {
                        $(o.cls.overlay).appendTo('.modal-body');
                        _this._initMap($(this).children('option:selected').val());
                    });

                $('#' + o.id_el.sd_list + '_' + o.delivery_id).unbind('click')
                    .on('click', '.selectSd', function() {
                        _this._toggleSd(this);
                    });

                $('#' + o.id_el.modal).unbind('hide.bs.modal')
                    .on('hide.bs.modal', function() {
                        $(this).closest('.modal-body').empty();
                    });

                $('button.submitModal').unbind('click')
                    .on('click', function() {
                        _this.submitModal(o.request.RegionTo, o.request.RequestCode);
                    });

                return this;
            },

            /**
             * Делает запрос ПВЗ по одному региону. Переключает карту.
             * @param  string 	Код региона
             */
            _initMap: function(reg) {
                var _this = this,
                    o = _this.o,
                    sd_id = o.request.RequestCode || false;
                if (reg) sd_id = false;
                o.request.RegionTo = reg || o.request.RegionTo;

                $('#' + o.id_el.map + '_' + o.delivery_id).empty();

                this._ajaxRequest('getSdListByRegion', {
                    region_id: o.request.RegionTo
                }, function(data) {

                    data = data.data || data;
                    sd_id = sd_id || data[0].RequestCode;
                    o.sd_data = {};

                    var $sd_list = $('#' + o.id_el.sd_list + '_' + o.delivery_id).empty(),
                        // $sd_list,
                        om = new ymaps.ObjectManager({ clusterize: o.map.clusterize, gridSize: o.map.grid_size }),
                        cluList = { 'type': 'FeatureCollection', 'features': [] }; // коллекция для карты

                    data.forEach(function(el, i) {
                        el.i = i;
                        el.fitting = el.FittingRoom ? 'есть' : 'нет';
                        el.active = (el.RequestCode == sd_id) ? ' active' : '';
                        el.balloon = _this.o.chunks.balloon.call(this, el, o);
                        el.li_html = _this.o.chunks.sd_list_item.call(this, el, o);

                        // Map points
                        cluList.features.push({
                            type: 'Feature',
                            id: el.RequestCode,
                            geometry: {
                                type: 'Point',
                                coordinates: [el.Latitude, el.Longitude]
                            },
                            properties: {
                                balloonContent: el.balloon.html(),
                                clusterCaption: 'Пункты самовывоза',
                                hintContent: 'Пункт самовывоза' + (el.Name) ? el.Name : '№' + el.RequestCode
                            }
                        });

                        // SD list
                        $sd_list.append(el.li_html);

                        // Store
                        o.sd_data[el.RequestCode] = el;
                    });
                    o.sd_object = o.sd_data[sd_id];
                    o.request.RequestCode = sd_id;

                    window['myMap'] = new ymaps.Map(o.id_el.map + '_' + o.delivery_id, {
                        center: [Number(o.sd_object.Latitude), Number(o.sd_object.Longitude)],
                        zoom: 10
                    });

                    om.objects.options.set('preset', 'islands#greenDotIcon');
                    om.clusters.options.set('preset', 'islands#greenClusterIcons');
                    om.add(cluList);
                    myMap.geoObjects.add(om);

                    setTimeout(function() {
                        $('#sdlist_' + o.delivery_id).parent()
                            .scrollTop($('.selectSd.active').offset().top - $('.selectSd').eq(0).offset().top);
                        _this._bindMapEvents();
                        $('.overlay').removeClass('overlay');
                        _this._moveMap(o.sd_object.Latitude, o.sd_object.Longitude);
                    }, 400);
                });


                return this;
            },

            /**
             * Запрос списка доступных регионов
             * @return {[type]} [description]
             */
            _loadRegions: function() {
                var o = this.o;

                this._ajaxRequest('getSdRegions', {}, function(data) {
                    var $s = $('#' + o.id_el.region_list + '_' + o.delivery_id),
                        d = data.data;
                    for (var r in d) {
                        if (d.hasOwnProperty(r)) {
                            $s.append($('<option/>', { 'value': r }).html(d[r]));
                        }
                    }
                    $s.val(o.request.RegionTo);
                });

                return this;
            },

            /**
             * Переключает ПВЗ на карте
             * @param  {object} el Нажатый элемент
             */
            _toggleSd: function(el) {
                $(el).addClass('active').siblings().removeClass('active');

                this.o.request.RequestCode = $.data(el, 'code');
                this.o.sd_object = this.o.sd_data[this.o.request.RequestCode];

                this._moveMap(this.o.sd_object.Latitude, this.o.sd_object.Longitude);
                return this;
            },

            submitModal: function(reg, code) {
                $('.modal').modal('hide');
                $('#region_id_to').val(reg);
                $('#request_code').val(code).trigger('change');
            },

            /**
             * Обновляет инфо о ценах после выбора пункта ПВЗ пользователем
             * @param  {string} rt регион куда
             * @param  {string} rc код пункта
             */
            updateInfo: function(rt, rc) {
                var _this = this,
                    o = _this.o,
                    $sdInfo = $('<div/>', {'class': 'sdInfo'}).html(o.sd_object.li_html[0].innerHTML),
                    $sdInfoWrap = $('.sdInfoWrap', _this.ctx).empty(),
                    $costBlock = $('.total-cost', _this.ctx);

                $sdInfoWrap.append($('<div/>', {'class': 'text-capitalize'}).html(o.sd_object.RegionCode.toLowerCase()));
                $sdInfoWrap.append($sdInfo);
                $costBlock.css('color', 'transparent').addClass('overlay');

                this._ajaxRequest('getImlDeliveryCost', {
                    service_id: o.request.Job,
                    region_id_from: o.request.RegionFrom,
                    region_id_to: rt,
                    request_code: rc,
                    update: true,
                    sd_info: $sdInfo[0].outerHTML
                }, function(data) {
                    _this.delivery_cost_json = data.data;
                    var cost = _this.delivery_cost_json[o.request.Job].Price;
                	$costBlock.html(cost).removeClass('overlay').attr('style', '');
                });
            },

            /**
             * Двигает карту
             * @param  {float} 	la   широта
             * @param  {float} 	lo   долгота
             */
            _moveMap: function(la, lo) {
                myMap.panTo([la, lo], {
                    duration: 600,
                    timingFunction: 'ease-out',
                    callback: function() {
                        myMap.setZoom(10);
                    }
                });
            },

            /**
             * Контроллер AJAX
             * @param  {string}   		action   Имя вызываемого PHP метода.
             * @param  {object}   params   Параметры, передающиеся методу
             * @param  {Function} 		callback
             * @return
             */
            _ajaxRequest: function(action, params, callback) {
                var _this = this,
                    o = this.o;
                return $.ajax({
                    url: o.url,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        module: 'Imldelivery',
                        typeObj: 0,
                        typeId: o.delivery_id,
                        'class': 'Iml',
                        userAct: action,
                        params: params
                    },
                    success: callback,
                    error: _this._errCallback,
                    complete: _this._doneCallback
                });

            },

            _errCallback: function(res) {
                // console.log(res);
            },

            _doneCallback: function(res) {
                // console.log(res);
            },
        };

    // Make sure Object.create is available in the browser (for our prototypal inheritance)
    // console.log(typeof Object.create);
    if (typeof Object.create !== 'function') {
        Object.create = function(o) {
            function F() {}
            F.prototype = o;
            return new F();
        };
    }

    // $(element).defaultPluginName('functionName', arg1, arg2)
    $.fn[pluginName] = function(options) {
        if (options === undefined || typeof options === 'object') {
            var args = arguments,
                I = Object.create(Iml);

            if (!this.length) {
                I.init(options, this);
                return $.data(this, 'plugin_' + pluginName) || $.data(this, 'plugin_' + pluginName, I);
            } else {
                return this.each(function() {
                    var I = Object.create(Iml);
                    I.init(options, this);
                    $.data(this, 'plugin_' + pluginName, I);
                });
            }

        } else if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {
            var returns;
            this.each(function() {
                var instance = $.data(this, 'plugin_' + pluginName);
                if (instance instanceof Iml && typeof instance[options] === 'function') {
                    returns = instance[options].apply(instance, Array.prototype.slice.call(args, 1));
                }
                if (options === 'destroy') {
                    $.data(this, 'plugin_' + pluginName, null);
                }
            });

            return returns !== undefined ? returns : this;
        }
    };

})(jQuery, window, document, ymaps);
