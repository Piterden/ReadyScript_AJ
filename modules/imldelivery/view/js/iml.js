/**
 * jQuery Iml Delivery Plugin
 *
 * Require to be included:
 *
 * 		http://api-maps.yandex.ru/2.1/?lang=ru_RU
 *
 * Original author: @piterden
 *
 */
;
(function($, window, document, ymaps, undefined) {

    'use strict';

    var pluginName = 'Iml',

        /**
         * Default options
         * @type {Object}
         */
        defaults = {
            url: '/', // AJAX request url
            delivery_id: 1, // RS_Param
            context_var: 'delivery_', // Map instance wrapper prefix

            /**
             * CSS mappings
             */
            el_id: { // Ids of elements
                modal: 'mapModal',
                map: 'map',
                combo_select: 'selectRegionCombo',
                combo_list: 'sdlist',
            },
            cls: { // Classes of elements
                overlay: 'loading',
                map_container: 'mapContainer',
                map_wrapper: 'mapWrapper',
                map_ymaps: 'ymaps-2-1-31-map ymaps-2-1-31-i-ua_js_yes ymaps-2-1-31-map-ru',
                combo_select: 'selectRegion',
            },
            style: { // Styles of elements
                map_map: 'height:400px; width:70%; float:left;',
                map_ymaps: 'width: 70%; height: 400px;',
                map_li_wrap: 'overflow: auto; height:400px; width:30%; padding:0; font-size:12px;',
            },

            /**
             * Map settings
             */
            map: {
                clusterize: true,
                gridSize: 32,
            },

            /**
             * Request params
             */
            request: {
                Job: 'С24',
                RegionFrom: 'САНКТ-ПЕТЕРБУРГ',
                RegionTo: 'САНКТ-ПЕТЕРБУРГ',
                Volume: '1',
                Weigth: '1',
                SpecialCode: '1',
            },

            templates: {
                loading: function(o) {
                    return $('<div/>', { class: o.cls.overlay });
                },
                map: function(o) {
                    var d_id = o.delivery_id,
                        c = o.cls,
                        i = o.id,
                        s = o.style;
                    return $('<div/>', { class: c.map_container }).append(
                        $('<select/>', { 'id': i.combo_select + '_' + d_id, 'class': c.combo_select }).data('size', '8')).append(
                        $('<div/>', { 'class': c.map_wrapper }).append(
                            $('<div/>', { 'id': i.map + '_' + d_id, 'style': s.map_map }).append(
                                $('<ymaps/>', { 'class': c.map_ymaps, 'style': s.map_ymaps })))).append(
                        $('<div/>', { 'style': s.map_li_wrap }).append(
                            $('<ul/>', { 'id': i.combo_list + '_' + d_id })));
                },
                modal: function(o) {
                    return $('<div/>', { 'class': 'modal fade', 'id': +o.id.modal, 'tabindex': '-1', 'role': 'dialog', 'aria-labelledby': 'mapModalLabel' }).append(
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

        },

        currState = {}, // App state storage
        html = {}, // DOM elements storage

        //<<<<<<<// 			Defaults End 		  //>>>>>>>>//


        /********************
         *
         * Plugin body
         *
         * ******************
         */

        /** @type {Object} [description] */
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
                this.currency = op.currency || {};
                this.delivery_cost_json = op.delivery_cost_json || {};
                this.service_id = op.service_id || {};

                // construct chain
                this._build()._bindInitEvents();


                /**********
                 * @public
                 */
                return {
                    /**
                     * Получает данные по выбранному ПВЗ
                     * @param {number}  RequestCode
                     * @return {json} 	Sd Data
                     */
                    getCurrentSdData: function(rc) {
                    	return rc;
                    },

                };
            },


            /***********
             * @private
             */
            /** Генерируем HTML разметку */
            /** Building HTML and caching it */
            _build: function() {
                for (var tpl in this.o.templates) {
                    if (this.o.templates.hasOwnProperty(tpl)) {
                        this.o.html[tpl] = this.o.templates[tpl].call(this, this.o);
                    }
                }
                return this;
            },

            /**
             * Вешаем обработчики при загрузке страницы
             */
            _bindInitEvents: function() {
                var self = this;

                $('#order-form').unbind('submit')
                    .on('submit', function() {
                        if (self.o.delivery_id == 7 && self.o) {
                            showError('Выберите, пожалуйста, пункт выдачи заказа!');
                            return false;
                        }
                    });

                this.$el.unbind('click')
                    .on('click', function(e) {
                        // console.log(self);
                        // console.log('BindInit', e);
                        // return false;
                        e.preventDefault();

                        $(this).addClass('map-open');
                        $(self.html.loading).appendTo('body');
                        self._ajaxRequest('loadMap', {}, self._showModalProcess);
                    });
                return this;
            },

            /**
             * Вешаем обработчики при открытии карты
             */
            _bindMapEvents: function() {
                var self = this;

                $('#' + o.el_id.combo_select).unbind('change')
                    .on('change', function() {
                        $(o.cls.overlay).appendTo('.modal-body');
                        self._initMap($(this).children('option:selected').val());
                    });

                $('#' + o.el_id.combo_list + ' .selectSd').unbind('click')
                    .on('click', function(e) {
                        e.preventDefault();
                        self.toggleActive($.data(this, 'code'));
                    });

                $('#' + o.el_id.modal).unbind('hide.bs.modal')
                    .on('hide.bs.modal', function() {
                        $(this).closest('.modal-body').empty();
                    });

                $('button.submitModal').unbind('click')
                    .on('click', function() {
                        self._saveDataPrepare();
                        return false;
                    });
            },

            /**
             * [AJAX Callback]
             * Показывает модальное окно с картой. Подгружает API Ya.Maps.
             *
             * @param  object 	response
             */
            _showModalProcess: function(data) {
                var $mb = $('.modal-body');

                $('body').children('.' + this.o.cls.loading).remove();
                $(this.html.loading).appendTo($mb);
                $('#' + this.o.id.modal_id).modal('show');
                $mb.append($(data.data));
                this._loadRegions();
                this._initMap();
            },

            /**
             * Чистит контейнер карты и делает запрос
             * по одному региону.
             * @param  string 	Код региона
             */
            _initMap: function(reg) {
                $('#' + this.o.id.map + '_' + this.o.delivery_id).empty();
                this._ajaxRequest('getSdListByRegion', { region_id: this.o.reg }, this._getSdListByRegionProcess);
            },

            /** Запрос списка доступных регионов */
            _loadRegions: function() {
                this._ajaxRequest('getSdRegions', [], _createSelect);
            },

            /** Генерирует селект с городами */
            _createSelect: function(data) {
                // console.log(data);
                var $sel = $(self.o.cls.combo_select);
                $.each(data.data, function(id, val) {
                    $sel.append($('<option/>', { 'value': id }).html(val));
                });
                $sel.val(self.o.region_id_to);
            },

            /**
             * Получает индекс указанного ПВЗ
             *
             * @param  number
             * @param  array
             * @return number
             */
            _getIndexSdById: function(code, data) {
                for (var i = data.length - 1; i >= 0; i--) {
                    if (data[i].request_code == code) return i;
                }
            },

            /**
             * [AJAX Callback]
             * Получает ПВЗ в одном регионе
             *
             * @param  object 	response
             */
            _getSdListByRegionProcess: function(data) {
                var o = this.o,
                    sd_id = 0,
                    // Чтобы метки начали кластеризоваться, выставляем опцию.
                    // ObjectManager принимает те же опции, что и кластеризатор.
                    om = new ymaps.ObjectManager({ clusterize: true, gridSize: 32 }),
                    list = { 'type': 'FeatureCollection', 'features': [] },
                    store = {};
                data = data.data;

                $('.' + o.cls.combo_list).empty();
                window['myMap'] = new ymaps.Map(o.id.map + '_' + o.delivery_id, {
                    center: [Number(data[sd_id].Latitude), Number(data[sd_id].Longitude)],
                    zoom: 10
                });

                store.sd_data = [];
                store.region_id_to = data[sd_id].RegionCode;
                store.request_code = data[sd_id].RequestCode;
                store.sd_object = data[sd_id];

                $.each(data, function(i, el) {
                    var fitting = el.FittingRoom ? 'есть' : 'нет';
                    _el = el;
                    list.features.push({
                        'type': 'Feature',
                        'id': el.RequestCode,
                        'geometry': {
                            type: 'Point',
                            coordinates: [el.Latitude, el.Longitude]
                        },
                        'properties': {
                            balloonContent: '<span style="font-weight: bold">' + el.Name + '</span>' + '<br />' +
                                el.Address + '<br />' +
                                'Телефон: ' + el.Phone + '<br />' +
                                'Оплата картой: ' + el.PaymentCard + '<br />' +
                                'Примерочная: ' + fitting + '<br />' +
                                'Время работы: ' + el.WorkMode +
                                '<br /><button class="balloonSelectSd" onclick="Iml.submitModal(event, ' + el.RequestCode +
                                ');" id="bal_sd_' + o.delivery_id + '_' + el.RequestCode + '">Выбрать</button>',
                            clusterCaption: 'Пункты самовывоза',
                            hintContent: 'Пункт самовывоза' + (el.Name) ? el.Name : '№' + el.RequestCode
                        }
                    });
                    // создание списка пунктов самовывоза рядом с картой
                    var active = (i == sd_id) ? ' active' : '',
                        li_html = '<li class="selectSd' + active + '" data-code="' + el.RequestCode + '" id="sd_' + s.delivery_id + '_' + el.RequestCode + '"><span style="font-weight: bold">' + el.Name + '</span>' + '<br />' +
                        el.Address + '<br />' +
                        'Телефон: ' + el.Phone + '<br />' +
                        'Оплата картой: ' + el.PaymentCard + '<br />' +
                        'Примерочная: ' + fitting + '<br />' +
                        'Время работы: ' + el.WorkMode + '<br /></li>';
                    $(list).append(li_html);
                });
                this.o.sd_data[el.RequestCode]

                om.objects.options.set('preset', 'islands#greenDotIcon');
                om.clusters.options.set('preset', 'islands#greenClusterIcons');
                myMap.geoObjects.add(om);
                om.add(o.cls.combo_list);
                $('.modal-body > .loading').remove();
                this._updatePricesPrepare();
                this.bindEvents();
            },
            /**
             * Готовит запрос к сохранению
             */
            _saveDataPrepare: function() {
                var o = this.o,
                    params = {
                        service_id: o.service_id,
                        region_id_from: o.region_id_from,
                        region_id_to: o.region_id_to,
                        request_code: o.request_code,
                        sd_data: o.sd_object,
                        sd_html: o.sd_html
                    };
                this._ajaxRequest('updateExtraData', params, this._saveDataProcess);
            },

            /**
             * Сохраняет выбранные данные в экстре заказа
             * @param  {Object} data ответ сервера
             * @return {[type]}
             */
            _saveDataProcess: function(data) {
                if (data.success) {
                    var cost_obj;
                    for (var prop in self.o.delivery_cost_json) {
                        if (self.o.delivery_cost_json.hasOwnProperty(prop)) {
                            cost_obj = self.o.delivery_cost_json[prop];
                        }
                    }
                    $('input[name="delivery"][value="' + self.o.delivery_id + '"]').trigger('click');
                    $('.deliveryItem').removeClass('hidden');
                    $('.addressOfDelivery').empty().append('<span class="text-capitalize lead">' + self.o.region_id_to + '</span><br>' + self.o.sd_html);
                    $('.priceOfDelivery').add('#scost_' + self.o.delivery_id + ' .text-success')
                        .empty().append(cost_obj.Price + ' ' + self.o.currency.stitle);
                    var $aod = $('.addressOfDelivery'),
                        $inf_blk = $aod.parents('.infoBlock'),
                        ib_heigth = $inf_blk.height(),
                        aod_heigth = $aod.height();
                    $inf_blk.height(ib_heigth + aod_heigth);
                    $inf_blk.siblings().height(ib_heigth + aod_heigth);
                    $('#' + self.o.id.modal_id).modal('hide');
                    self.o.map_closed = true;
                }
            },
            /**
             * Обновление цен. Подготовка запроса.
             */
            _updatePricesPrepare: function() {
                var o = this.o,
                    params = {
                        service_id: o.service_id,
                        region_id_from: o.region_id_from,
                        region_id_to: o.region_id_to,
                        request_code: o.request_code
                    };
                // $(o.loading).appendTo('.modal-footer');
                this._ajaxRequest('getImlDeliveryCost', params, this._updatePricesProcess);
            },
            /**
             * Callback после обновления цен.
             * @param  {Object} 	Ответ сервера.
             */
            _updatePricesProcess: function(data) {
                self.o.delivery_cost_json = data.data;
                self.o.sd_html = $('.selectSd.active').html();
                $('.modal-footer .loading').fadeOut('fast', function() {
                    $.each(data.data, function(i, v) {
                        var $priceBlock = $('.priceText[data-code="' + i + '"]', $('#delivery_' + self.o.delivery_id));
                        $priceBlock.removeClass('text-info text-warning text-danger');
                        if (v.Price) {
                            $priceBlock.addClass('text-info').html(v.Price + ' ' + self.o.currency.stitle.replace('р.', '<i class="fa fa-rub"></i>'));
                        } else if (v.Code && v.Mess) {
                            $priceBlock.addClass('text-warning').attr({
                                dataErrorCode: v.Code,
                                dataErrorMess: v.Mess
                            }).text('Цена отсутствует.');
                        } else {
                            $priceBlock.addClass('text-danger').text('Неизвестная ошибка!');
                        }
                    });
                    $(this).remove();
                });
            },

            /**
             * [moveMap description]
             * @param  {[type]} la   [description]
             * @param  {[type]} lo   [description]
             * @param  {[type]} code [description]
             * @return {[type]}      [description]
             */
            moveMap: function(la, lo, code) {
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
             * @param  {*string*}   action   Имя вызываемого PHP метода.
             *          Класс - \Imldelivery\Model\DeliveryType\Iml::action()
             * @param  {*array*|*object*}   params   Параметры, передающиеся методу
             * @param  {*Function*} callback
             * @return
             */
            _ajaxRequest: function(action, params, callback) {
                var imlData = self.o;
                $.ajax({
                    url: imlData.url,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        module: 'Imldelivery',
                        typeObj: 0, //Доставка
                        typeId: imlData.delivery_id,
                        'class': 'Iml',
                        userAct: action,
                        params: params
                    },
                    success: callback
                });
            },

            /**
             * [toggleActive description]
             * @param  {[type]} e    [description]
             * @param  {[type]} code [description]
             * @return {[type]}      [description]
             */
            toggleActive: function(code) {
                $this = $('#sd_' + self.o.delivery_id + '_' + code);
                $this.addClass('active').siblings('.selectSd').removeClass('active');
                self.o.request_code = code;
                self.o.sd_object = self.o.sd_data[code];
                this.moveMap(self.o.sd_object.Latitude, self.o.sd_object.Longitude);
                this._updatePricesPrepare();
            },

            /**
             * [toggleActive description]
             * @param  {[type]} e    [description]
             * @param  {[type]} code [description]
             * @return {[type]}      [description]
             */
            submitModal: function(e, code) {
                e.preventDefault();
                self.o.request_code = code;
                self.o.sd_object = self.o.sd_data[code];
                this._saveDataPrepare();
            }

        }; //<<<<<<<// 			Plugin End 			//>>>>>>>>//

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
    // $(document).ready(function() {
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
                    $.data(this, 'speaker', I);
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
    // });

})(jQuery, window, document, ymaps);




// while (l--) {
//     returns.push(I);
//     if (l = 1) {

//         // return returns || I;
//     };
// }
