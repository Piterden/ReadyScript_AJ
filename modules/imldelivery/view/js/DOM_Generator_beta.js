// Iml - an object representing a concept that you want
// to model (e.g. a car)
var Iml = {

    options: {
        // Default predefined
        region_id_from: "САНКТ-ПЕТЕРБУРГ",
        region_id_to: "САНКТ-ПЕТЕРБУРГ",
        request_code: 1,
        sd_selected: false,

        // HTML
        loading_class: "loading",
        context_data_name: "delivery-id",

        prefixes: [
            { select_id_prefix: "selectRegionCombo_" },
            { list_id_prefix: "sdlist_" },
            { button_id_prefix: "showMap_" },
            { map_id_prefix: "map_" },
        ],

        // Data
        url: "",
        delivery_id: 7,
        currency: {},
        delivery_cost_json: {},
        sd_data: [],
        sd_html: "",
        sd_list: {},
        sd_object: {},
        service_id: {}
    },

    JSON_DOM: {
        "modal": {
            // <div class="modal fade" id="mapModal">
            "tag": "div",
            "class": "modal fade",
            "id": "mapModal",
            "tabindex": "-1",
            "role": "dialog",
            "aria-labelledby": "mapModalLabel",
            "children": {
                // <div class="modal-dialog modal-lg" role="document">
                "tag": "div",
                "class": "modal-dialog modal-lg",
                "role": "document",
                "children": {
                    // <div class="modal-content">
                    "tag": "div",
                    "class": "modal-content",
                    "children": [{
                        // <div class="modal-header">
                        "tag": "div",
                        "class": "modal-header",
                        "children": {
                            // <button type="button" class="close">
                            "tag": "button",
                            "class": "close",
                            "type": "button",
                            "data-dismiss": "modal",
                            "aria-label": "Отменить",
                            children: {
                                // <span aria-hidden="true">
                                "tag": 'span',
                                "aria-hidden": "true",
                                "content": "&times;",
                            }
                        }
                    }, {
                        // <div class="modal-body">
                        "tag": "div",
                        "class": "modal-body",
                        // "children": {}
                    }, {
                        // <div class="modal-footer">
                        "tag": "div",
                        "class": "modal-footer",
                        "children": [
                            /*{
                                                        // this._buildZonePrice()
                                                    }, */
                            {
                                // <div class="buttons">
                                "tag": "div",
                                "class": "buttons",
                                "children": [{
                                    // <button type="submit" class="btn btn-default" data-dismiss="modal">
                                    "tag": "button",
                                    "type": "submit",
                                    "class": "btn btn-default",
                                    "data-dismiss": "modal",
                                    "content": "Отменить",
                                }, {
                                    // <button type="submit" class="btn btn-primary submitModal">
                                    "tag": "button",
                                    "type": "submit",
                                    "class": "btn btn-primary submitModal",
                                    "content": "Выбрать"
                                }]
                            }
                        ]
                    }]
                }
            }
        },

        "map": {

            "tag": "div",
            "class": "mapContainer",
            "children": [{

                "tag": "select",
                "id": "selectRegionCombo_" /*+ this.options.delivery_id*/ ,
                "class": "selectRegion",
                "data-size": "8"
            }, {

                "tag": "div",
                "class": "mapWrapper",
                "children": [{

                    "tag": "div",
                    "id": "map_" /*+ this.options.delivery_id*/
                }, {

                    "tag": "div",
                    "children": {

                        "tag": "ul",
                        "id": "sdlist_" /*+ this.options.delivery_id*/
                    }
                }]
            }]
        },

    },

    init: function(options, button) {
        // console.log("init",this.createDOM);
        // Mix in the passed-in options with the default options
        this.options = $.extend({}, this.options, options);
        // Save the element reference, both as a jQuery
        // reference and a normal reference
        this.button = button;
        this.$button = $(button);
        // Build the DOM's initial structure
        this._build();

        this.bindEventHandlers(this.options);
        // return this so that we can chain and use the bridge with less code.
        return this;
    },

    _build: function() {
        // console.log("_build",this.createDOM);
        // pattern: this.$elem.html('<h1>'+this.options.name+'</h1>');

        // modal
        this.modal = this.createDOM(this.JSON_DOM.modal);
        this.$modal = $(this.modal);

        // map
        this.map = this.createDOM(this.JSON_DOM.map, this.modal.getElementsByClassName('modal-body')[0]);
        this.$map = $(this.map);

        // console.log(this.map);

    },

    /**
     * Навешивает обработчики на элементы
     * @return
     */
    bindEventHandlers: function(config) {
        _this = this;
        // console.log(_this);

        this.$button.unbind('click').on('click', function(e) {
            e.preventDefault();
            $(this).addClass('passive');

            // loading
            _this.loading = _this.createDOM({
                "class": config.loading_class
            });
            _this.$loading = $(_this.loading);

            // _this.$loading.appendTo('body').clone().appendTo('.modal-body');
            _this.ajaxRequest('loadMap', [], _this.modalShow);

            return false;
        });

        // $(Iml.config.select_id).unbind('change').on('change', function() {
        //     $(Iml.config.loading).appendTo('.modal-body');
        //     mapInit($(this).children(':selected').val());
        // });

        // $(Iml.config.list_id + ' .selectSd').unbind('click').on('click', function(e) {
        //     toggleActive(e, $(this).attr('data-code'));
        // });

        // $('#mapModal').unbind('hide.bs.modal').on('hide.bs.modal', function(e) {
        //     $this = $(this);
        //     $this.find('.modal-body').empty();
        // });

        // $('button.submitModal').unbind('click').on('click', function(e) {
        //     sdSave();
        //     return false;
        // });
    },


    /**
     * Показывает модальное окно с картой
     *
     * @param  (object) data    Ответ сервера
     * @return
     */
    modalShow: function(data) {
    	console.log(data);
    	var _this = window._this;
        _this.$loading.remove();
        _this.$modal.modal('show');
        $(_this.modal.getElementsByClassName('modal-body')[0]).append($(data.data));
        _this.regionsLoad();
        _this.mapInit();

        //$(data.html).find('.mapContainer').appendTo('.imlContainer_{$delivery.id}');
        //console.log($mapContainer);
    },

    /**
     * Подгружает карту из API Ya.Maps
     * @param  (string) region_id_to Код региона куда делать доставку
     * @return
     */
    mapInit: function(region_id_to) {
        // var params = {
        //     region_id: region_id_to || Iml.config.region_id_to
        // };

        // $('#map_' + Iml.config.delivery_id).empty();
        // Iml.ajaxRequest('getSdByRegion', params, Iml.sdByRegionLoad);
    },

    /**
     * Грузит список доступных регионов в select
     * @return
     */
    regionsLoad: function() {
        // ajaxRequest('getSdRegions', [], function(data) {
        //     //console.log(data);
        //     var $selectRegionList = $(Iml.config.select_id);
        //     $.each(data.data, function(id, val) {
        //         $selectRegionList.append('<option value="' + id + '">' + val + '</option>');
        //     });
        //     $selectRegionList.val(Iml.config.region_id_to);
        // });
    },

    /**
     * Получает пункты самовывоза в одном регионе. Создает HTML карты
     *
     * @param  object data response
     * @return
     */
    sdByRegionLoad: function(data) {
        // data = data.data;
        // // console.log();
        // var config = Iml.config,
        //     r_c = 0;
        // // if (Iml.config.request_code > 0 && Iml.config.region_id_to) {
        // // 	r_c = getIndexSd(Iml.config.request_code, data);
        // // 	console.log(r_c);
        // // }
        // // if (Iml.config.sd_object === undefined || Iml.config.region_id_to != data[0].RegionCode) {
        // // }
        // // else {
        // //     var r_c = getIndexSd(Iml.config.sd_object.request_code, data);
        // // }
        // Iml.config.sd_data = [];

        // //console.log(data);
        // $(config.list_id).empty();

        // myMap = new ymaps.Map('map_' + config.delivery_id, {
        //     center: [Number(data[r_c].Latitude), Number(data[r_c].Longitude)],
        //     zoom: 10
        // });
        // var objectManager = new ymaps.ObjectManager({
        //     // Чтобы метки начали кластеризоваться, выставляем опцию.
        //     clusterize: true,
        //     // ObjectManager принимает те же опции, что и кластеризатор.
        //     gridSize: 32
        // });
        // Iml.config.region_id_to = data[r_c].RegionCode;
        // Iml.config.request_code = data[r_c].RequestCode;
        // Iml.config.sd_object = data[r_c];
        // Iml.config.sd_list = {
        //     "type": "FeatureCollection",
        //     "features": []
        // };

        // $.each(data, function(i, el) {
        //     Iml.config.sd_data[el.RequestCode] = el;
        //     var fitting = el.FittingRoom ? 'есть' : 'нет';
        //     Iml.config.sd_list.features.push({
        //         "type": "Feature",
        //         "id": el.RequestCode,
        //         "geometry": {
        //             type: "Point",
        //             coordinates: [el.Latitude, el.Longitude]
        //         },
        //         "properties": {
        //             balloonContent: '<span style="font-weight: bold">' + el.Name + '</span>' + '<br />' +
        //                 el.Address + '<br />' +
        //                 'Телефон: ' + el.Phone + '<br />' +
        //                 'Оплата картой: ' + el.PaymentCard + '<br />' +
        //                 'Примерочная: ' + fitting + '<br />' +
        //                 'Время работы: ' + el.WorkMode +
        //                 '<br /><button class="balloonSelectSd" onclick="toggleActive(event, ' + el.RequestCode +
        //                 ');" id="bal_sd_' + config.delivery_id + '_' + el.RequestCode + '">Выбрать</button>',
        //             clusterCaption: 'Пункты самовывоза',
        //             hintContent: 'Пункт самовывоза' + (el.Name) ? el.Name : '№' + el.RequestCode
        //         }
        //     });

        //     // создание списка пунктов самовывоза рядом с картой
        //     var active = (i == r_c) ? ' active' : '',
        //         li_html = '<li class="selectSd' + active + '" data-code="' + el.RequestCode + '" id="sd_' + config.delivery_id + '_' + el.RequestCode + '"><span style="font-weight: bold">' + el.Name + '</span>' + '<br />' +
        //         el.Address + '<br />' +
        //         'Телефон: ' + el.Phone + '<br />' +
        //         'Оплата картой: ' + el.PaymentCard + '<br />' +
        //         'Примерочная: ' + fitting + '<br />' +
        //         'Время работы: ' + el.WorkMode + '<br /></li>';
        //     $(config.list_id).append(li_html);
        // });

        // objectManager.objects.options.set('preset', 'islands#greenDotIcon');
        // objectManager.clusters.options.set('preset', 'islands#greenClusterIcons');
        // myMap.geoObjects.add(objectManager);
        // objectManager.add(Iml.config.sd_list);

        // $('.modal-body > .loading').remove();
        // Iml.pricesLoad();
        // Iml.init();
    },

    /**
     * Загружает цены на выбранные в способе доставки услуги
     *
     * @return {[type]} [description]
     */
    pricesLoad: function() {
        // var params = {
        //     service_id: Iml.config.service_id,
        //     region_id_from: Iml.config.region_id_from,
        //     region_id_to: Iml.config.region_id_to,
        //     request_code: Iml.config.request_code
        // };
        // $(Iml.config.loading).appendTo('.modal-footer');
        // ajaxRequest('getDeliveryCostAjax', params, pricesAfterLoad);
    },

    /**
     * Callback после обновления цен
     * @param  ([type]) data [*description*]
     */
    pricesAfterLoad: function(data) {
        // Iml.config.delivery_cost_json = data.data;
        // Iml.config.sd_html = $('.selectSd.active').html();
        // $('.modal-footer .loading').fadeOut('fast', function() {
        //     $.each(data.data, function(i, v) {
        //         var $priceBlock = $('.priceText[data-code="' + i + '"]', $('#delivery_' + Iml.config.delivery_id));
        //         $priceBlock.removeClass('text-info text-warning text-danger');
        //         if (v.Price) {
        //             $priceBlock.addClass('text-info').html(v.Price + ' ' + Iml.config.currency.stitle.replace('р.', '<i class="fa fa-rub"></i>'));
        //         } else if (v.Code && v.Mess) {
        //             $priceBlock.addClass('text-warning').attr({
        //                 dataErrorCode: v.Code,
        //                 dataErrorMess: v.Mess
        //             }).text('Цена отсутствует.');
        //         } else {
        //             $priceBlock.addClass('text-danger').text('Неизвестная ошибка!');
        //         }
        //     });
        //     $(this).remove();
        // });
    },

    /**
     * Сохраняет выбранные данные в экстре заказа
     * Закрывает карту
     */
    sdSave: function() {
        // var params = {
        //     service_id: Iml.config.service_id,
        //     region_id_from: Iml.config.region_id_from,
        //     region_id_to: Iml.config.region_id_to,
        //     request_code: Iml.config.request_code,
        //     sd_data: Iml.config.sd_object,
        //     sd_html: Iml.config.sd_html
        // };
        // ajaxRequest('updateExtraData', params, function(data) {
        //     if (data.success) {
        //         for (var prop in Iml.config.delivery_cost_json) {
        //             if (Iml.config.delivery_cost_json.hasOwnProperty(prop)) {
        //                 var cost_obj = Iml.config.delivery_cost_json[prop];
        //             }
        //         }
        //         $('input[name="delivery"][value="' + Iml.config.delivery_id + '"]').trigger('click');
        //         $('.deliveryItem').removeClass('hidden');
        //         $('.addressOfDelivery').empty().append('<span class="text-capitalize lead">' + Iml.config.region_id_to + '</span><br>' + Iml.config.sd_html);
        //         $('.priceOfDelivery').add('#scost_' + Iml.config.delivery_id + ' .text-success')
        //             .empty().append(cost_obj.Price + ' ' + Iml.config.currency.stitle);
        //         var $aod = $('.addressOfDelivery'),
        //             $inf_blk = $aod.parents('.infoBlock'),
        //             ib_heigth = $inf_blk.height(),
        //             aod_heigth = $aod.height();

        //         $inf_blk.height(ib_heigth + aod_heigth);
        //         $inf_blk.siblings().height(ib_heigth + aod_heigth);
        //         $('#mapModal').modal('hide');
        //         // Iml.config.sd_selected = truefalse,
        //     }
        // });
    },
    mapMove: function(la, lo, code) {
        // myMap.panTo([la, lo], {
        //     duration: 600,
        //     timingFunction: 'ease-out',
        //     callback: function() {
        //         myMap.setZoom(10);
        //     }
        // });
    },

    /**
     * Запрос на стандартный AJAX контроллер (userAct)
     *
     * @param  (string)   action   Имя вызываемого публичного метода
     * @param  (array|object)   params   Параметры, передающиеся методу
     * @param  (function) callback
     * @return
     */
    ajaxRequest: function(action, params, callback) {
        var config = window._this.options;
        $.ajax({
            url: config.url,
            type: 'POST',
            dataType: 'json',
            data: {
                module: 'Imldelivery',
                typeObj: 0, //Доставка
                typeId: config.delivery_id,
                'class': 'Iml',
                userAct: action,
                params: params
            },
            success: callback
        });
    },


    toggleActive: function(e, code) {
        // e.preventDefault();
        // $this = $('#sd_' + Iml.config.delivery_id + '_' + code);
        // $this.addClass('active').siblings('.selectSd').removeClass('active');

        // Iml.config.request_code = code;
        // Iml.config.sd_object = Iml.config.sd_data[code];

        // mapMove(Iml.config.sd_object.Latitude, Iml.config.sd_object.Longitude);
        // Iml.pricesLoad();
    },

    createDOM: function(dom, parent) {
        var $this = this;
        parent = parent || document.getElementsByTagName('body')[0];
        if (Array.isArray(dom)) {
            // console.log("array", dom);
            dom.forEach(function(dom_part, id_dom_part) {
                $this.createDOM(dom_part, parent);
            });
        } else {
            var el = $this.newElem(dom);
            // console.log("new el", el);
            if (typeof dom.children === "object") {
                // console.log("have children", dom.children, parent);
                this.createDOM(dom.children, el);
            }
            // console.log("append", parent, el);
            parent.appendChild(el);
            return el;
        }
    },

    newElem: function(dom) {
        dom = dom || {};
        dom.tag = dom.tag || "div";
        var elem = document.createElement(dom.tag);
        // document.createElementNS ?
        // document.createElementNS('http://www.w3.org/1999/xhtml', dom.tag) :
        // console.log("create", elem);
        for (var prop in dom) {
            if (prop == "tag" || prop == "children") continue;
            if (prop == "content") {
                elem.innerHTML = dom[prop];
                continue;
            }
            if (prop == "id" && dom[prop].endsWith("_")) {
                dom[prop] = dom[prop] + this.options.delivery_id;
            }
            elem.setAttribute(prop, dom[prop]);
        }
        return elem;
    },

    myMethod: function(msg) {
        // You have direct access to the associated and cached
        // jQuery element
        // this.$elem.append('<p>'+msg+'</p>');
    },

};

// Object.create support test, and fallback for browsers without it
if (typeof Object.create !== 'function') {
    Object.create = function(o) {
        function F() {}
        F.prototype = o;
        return new F();
    };
}

// Create a plugin based on a defined object
$.plugin = function(name, object) {
    $.fn[name] = function(options) {
        return this.each(function() {
            if (!$.data(this, name)) {
                $.data(this, name, Object.create(object).init(
                    options, this));
            }
        });
    };
};

$.plugin('iml', Iml);
// (function($) {

//     var defaults = ;

//     var methods = {
//         init: function(initoptions) {

//             return this.each(function() {
//                 // console.log($(this));
//                 var $this = $(this),
//                     inited = $this.data('inited'),
//                     data;
//                 // tooltip = $('<div />', {
//                 //     text: $this.attr('title')
//                 // });


//                 // Если плагин ещё не проинициализирован
//                 if (!inited) {
//                     var config = $(this).data('config');
//                     data = defaults;
//                     $.extend(true, data, config);

//                     $(this).data('inited', true);
//                 }
//                 $.extend(true, initoptions, defaults);

//                 bindEventHandlers(data);
//                 console.log($.Iml);
//             });

//             // var data = $this.data('config');
//             // if (!data) { //Инициализация
//             //     data = defaults;
//             //     $this.data('config', data);
//             // }
//             // $.extend(true, data, initoptions);


//             // console.log('loaded', data);

//             // if (data) return;
//             // data = {}; $this.data('config', data);
//             // data.options = $.extend({}, defaults, initoptions);

//         }
//     };





//     $.fn.Iml = function(method) {

//         // return this.each(function() {
//         //     var $this = $(this),
//         //         data = $this.data(''),
//         //         options;

//         //     //private

//         if (methods[method]) {
//             return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
//         } else if (typeof method === 'object' || !method) {
//             return methods.init.apply(this, arguments);
//         } else {
//             $.error('Метод с именем ' + method + ' не существует для jQuery.tooltip');
//         }

//         // if (methods[method]) {

//         //     console.log('1', this);
//         //     methods[method].apply(this, Array.prototype.slice.call(args, 1));
//         // } else if (typeof method === 'object' || !method) {
//         //     console.log('2', methods.init.apply(this, args));
//         //     return methods.init.apply(this, args);
//         // }

//         // });


//     };

// })(jQuery);














// (function($) {

//     $.fn.Iml = function() {

//         var method,
//             args = arguments,


//             return this.each(function() {

//             });

//     };
// })(jQuery);

// (function($) {
//     "use strict";

//     // allow Iml to be extended from other scripts
//     window.Iml = window.Iml || {};




//     $.extend(window.Iml.config, defaults);

//     $.extend(window.Iml, {

//         init: function() {
//             console.log(this);
//             // console.log('init:'+this);
//             var config = $this.config;
//             methods.bindEventHandlers(config);
//         },

//         getIdsByPrefixes: function() {
//             var prefix,
//                 prefixes = this.config.prefixes;
//             for (prefix in prefixes) {
//                 if (prefixes.hasOwnProperty(prefix)) {
//                     // console.log(prefix);

//                 }
//             }
//             this.config.select_id = this.config.select_id_prefix + this.config.delivery_id;
//             return;
//         },







// ,

//     });
// })(jQuery);






// (function() {

//     // Define our constructor
//     this.Iml = function() {

//         var method,
//             args = arguments,
//             defaults = {
//                 // Default predefined
//                 region_id_from: "САНКТ-ПЕТЕРБУРГ",
//                 region_id_to: "САНКТ-ПЕТЕРБУРГ",
//                 request_code: 1,
//                 sd_selected: false,

//                 // HTML
//                 loading_class: "loading",
//                 context_data_name: "delivery-id",

//                 prefixes: [
//                     { select_id_prefix: "selectRegionCombo_" },
//                     { list_id_prefix: "sdlist_" },
//                     { button_id_prefix: "showMap_" },
//                     { map_id_prefix: "map_" },
//                 ],

//                 // Data
//                 url: "",
//                 delivery_id: null,
//                 currency: {},
//                 delivery_cost_json: {},
//                 sd_data: [],
//                 sd_html: "",
//                 sd_list: {},
//                 sd_object: {},
//                 service_id: {}
//             };

//         var $this = this;
//         Object.defineProperty($this, 'config', {
//             "__proto__": null, // no inherited properties
//             "value": defaults, // not enumerable
//             "writable": true,
//             "configurable": true,
//         });
//         // $this.config = defaults;
//         console.log();

//         var methods = {

//             init: function() {
//                 // console.log('init:'+this);
//                 var config = $this.config;
//                 methods.bindEventHandlers(config);
//             },

//             getIdsByPrefixes: function() {
//                 var prefix,
//                     prefixes = this.config.prefixes;
//                 for (prefix in prefixes) {
//                     if (prefixes.hasOwnProperty(prefix)) {
//                         // console.log(prefix);

//                     }
//                 }
//                 this.config.select_id = this.config.select_id_prefix + this.config.delivery_id;
//                 return;
//             },


//             /**
//              * Навешивает обработчики на элементы
//              * @return
//              */
//             bindEventHandlers: function(config) {
//                 this.config = config;
//                 console.log(this);
//                 // console.log(Iml);

//                 // elemIds = this.getIdsByPrefixes();
//                 // document.getElementById()

//                 // $('#showMap_' + delivery_id).unbind('click').on('click', function(e) {
//                 //     e.preventDefault();
//                 //     $(this).addClass('passive');
//                 //     $(Iml.config.loading).appendTo('body').clone().appendTo('.modal-body');
//                 //     Iml.ajaxRequest('loadMap', [], modalShow);
//                 // });

//                 // $(Iml.config.select_id).unbind('change').on('change', function() {
//                 //     $(Iml.config.loading).appendTo('.modal-body');
//                 //     mapInit($(this).children(':selected').val());
//                 // });

//                 // $(Iml.config.list_id + ' .selectSd').unbind('click').on('click', function(e) {
//                 //     toggleActive(e, $(this).attr('data-code'));
//                 // });

//                 // $('#mapModal').unbind('hide.bs.modal').on('hide.bs.modal', function(e) {
//                 //     $this = $(this);
//                 //     $this.find('.modal-body').empty();
//                 // });

//                 // $('button.submitModal').unbind('click').on('click', function(e) {
//                 //     sdSave();
//                 //     return false;
//                 // });
//             },




//             // /**
//             //  * Показывает модальное окно с картой
//             //  *
//             //  * @param  (object) data    Ответ сервера
//             //  * @return
//             //  */
//             // modalShow: function(data) {
//             //     $('body > .loading').remove();
//             //     $('#mapModal').modal('show');
//             //     $('.modal-body').append($(data.data));
//             //     regionsLoad();
//             //     mapInit();
//             //     //$(data.html).find('.mapContainer').appendTo('.imlContainer_{$delivery.id}');
//             //     //console.log($mapContainer);
//             // },

//             // /**
//             //  * Подгружает карту из API Ya.Maps
//             //  * @param  (string) region_id_to Код региона куда делать доставку
//             //  * @return
//             //  */
//             // mapInit: function(region_id_to) {
//             //     var params = {
//             //         region_id: region_id_to || Iml.config.region_id_to
//             //     };

//             //     $('#map_' + Iml.config.delivery_id).empty();
//             //     Iml.ajaxRequest('getSdByRegion', params, Iml.sdByRegionLoad);
//             // },

//             // /**
//             //  * Грузит список доступных регионов в select
//             //  * @return
//             //  */
//             // regionsLoad: function() {
//             //     ajaxRequest('getSdRegions', [], function(data) {
//             //         //console.log(data);
//             //         var $selectRegionList = $(Iml.config.select_id);
//             //         $.each(data.data, function(id, val) {
//             //             $selectRegionList.append('<option value="' + id + '">' + val + '</option>');
//             //         });
//             //         $selectRegionList.val(Iml.config.region_id_to);
//             //     });
//             // },

//             // /**
//             //  * Получает пункты самовывоза в одном регионе. Создает HTML карты
//             //  *
//             //  * @param  object data response
//             //  * @return
//             //  */
//             // sdByRegionLoad: function(data) {
//             //     data = data.data;
//             //     // console.log();
//             //     var config = Iml.config,
//             //         r_c = 0;
//             //     // if (Iml.config.request_code > 0 && Iml.config.region_id_to) {
//             //     // 	r_c = getIndexSd(Iml.config.request_code, data);
//             //     // 	console.log(r_c);
//             //     // }
//             //     // if (Iml.config.sd_object === undefined || Iml.config.region_id_to != data[0].RegionCode) {
//             //     // }
//             //     // else {
//             //     //     var r_c = getIndexSd(Iml.config.sd_object.request_code, data);
//             //     // }
//             //     Iml.config.sd_data = [];

//             //     //console.log(data);
//             //     $(config.list_id).empty();

//             //     myMap = new ymaps.Map('map_' + config.delivery_id, {
//             //         center: [Number(data[r_c].Latitude), Number(data[r_c].Longitude)],
//             //         zoom: 10
//             //     });
//             //     var objectManager = new ymaps.ObjectManager({
//             //         // Чтобы метки начали кластеризоваться, выставляем опцию.
//             //         clusterize: true,
//             //         // ObjectManager принимает те же опции, что и кластеризатор.
//             //         gridSize: 32
//             //     });
//             //     Iml.config.region_id_to = data[r_c].RegionCode;
//             //     Iml.config.request_code = data[r_c].RequestCode;
//             //     Iml.config.sd_object = data[r_c];
//             //     Iml.config.sd_list = {
//             //         "type": "FeatureCollection",
//             //         "features": []
//             //     };

//             //     $.each(data, function(i, el) {
//             //         Iml.config.sd_data[el.RequestCode] = el;
//             //         var fitting = el.FittingRoom ? 'есть' : 'нет';
//             //         Iml.config.sd_list.features.push({
//             //             "type": "Feature",
//             //             "id": el.RequestCode,
//             //             "geometry": {
//             //                 type: "Point",
//             //                 coordinates: [el.Latitude, el.Longitude]
//             //             },
//             //             "properties": {
//             //                 balloonContent: '<span style="font-weight: bold">' + el.Name + '</span>' + '<br />' +
//             //                     el.Address + '<br />' +
//             //                     'Телефон: ' + el.Phone + '<br />' +
//             //                     'Оплата картой: ' + el.PaymentCard + '<br />' +
//             //                     'Примерочная: ' + fitting + '<br />' +
//             //                     'Время работы: ' + el.WorkMode +
//             //                     '<br /><button class="balloonSelectSd" onclick="toggleActive(event, ' + el.RequestCode +
//             //                     ');" id="bal_sd_' + config.delivery_id + '_' + el.RequestCode + '">Выбрать</button>',
//             //                 clusterCaption: 'Пункты самовывоза',
//             //                 hintContent: 'Пункт самовывоза' + (el.Name) ? el.Name : '№' + el.RequestCode
//             //             }
//             //         });

//             //         // создание списка пунктов самовывоза рядом с картой
//             //         var active = (i == r_c) ? ' active' : '',
//             //             li_html = '<li class="selectSd' + active + '" data-code="' + el.RequestCode + '" id="sd_' + config.delivery_id + '_' + el.RequestCode + '"><span style="font-weight: bold">' + el.Name + '</span>' + '<br />' +
//             //             el.Address + '<br />' +
//             //             'Телефон: ' + el.Phone + '<br />' +
//             //             'Оплата картой: ' + el.PaymentCard + '<br />' +
//             //             'Примерочная: ' + fitting + '<br />' +
//             //             'Время работы: ' + el.WorkMode + '<br /></li>';
//             //         $(config.list_id).append(li_html);
//             //     });

//             //     objectManager.objects.options.set('preset', 'islands#greenDotIcon');
//             //     objectManager.clusters.options.set('preset', 'islands#greenClusterIcons');
//             //     myMap.geoObjects.add(objectManager);
//             //     objectManager.add(Iml.config.sd_list);

//             //     $('.modal-body > .loading').remove();
//             //     Iml.pricesLoad();
//             //     Iml.init();
//             // },

//             // /**
//             //  * Загружает цены на выбранные в способе доставки услуги
//             //  *
//             //  * @return {[type]} [description]
//             //  */
//             // pricesLoad: function() {
//             //     var params = {
//             //         service_id: Iml.config.service_id,
//             //         region_id_from: Iml.config.region_id_from,
//             //         region_id_to: Iml.config.region_id_to,
//             //         request_code: Iml.config.request_code
//             //     };
//             //     $(Iml.config.loading).appendTo('.modal-footer');
//             //     ajaxRequest('getDeliveryCostAjax', params, pricesAfterLoad);
//             // },

//             // /**
//             //  * Callback после обновления цен
//             //  * @param  ([type]) data [*description*]
//             //  */
//             // pricesAfterLoad: function(data) {
//             //     Iml.config.delivery_cost_json = data.data;
//             //     Iml.config.sd_html = $('.selectSd.active').html();
//             //     $('.modal-footer .loading').fadeOut('fast', function() {
//             //         $.each(data.data, function(i, v) {
//             //             var $priceBlock = $('.priceText[data-code="' + i + '"]', $('#delivery_' + Iml.config.delivery_id));
//             //             $priceBlock.removeClass('text-info text-warning text-danger');
//             //             if (v.Price) {
//             //                 $priceBlock.addClass('text-info').html(v.Price + ' ' + Iml.config.currency.stitle.replace('р.', '<i class="fa fa-rub"></i>'));
//             //             } else if (v.Code && v.Mess) {
//             //                 $priceBlock.addClass('text-warning').attr({
//             //                     dataErrorCode: v.Code,
//             //                     dataErrorMess: v.Mess
//             //                 }).text('Цена отсутствует.');
//             //             } else {
//             //                 $priceBlock.addClass('text-danger').text('Неизвестная ошибка!');
//             //             }
//             //         });
//             //         $(this).remove();
//             //     });
//             // },

//             // /**
//             //  * Сохраняет выбранные данные в экстре заказа
//             //  * Закрывает карту
//             //  */
//             // sdSave: function() {
//             //     var params = {
//             //         service_id: Iml.config.service_id,
//             //         region_id_from: Iml.config.region_id_from,
//             //         region_id_to: Iml.config.region_id_to,
//             //         request_code: Iml.config.request_code,
//             //         sd_data: Iml.config.sd_object,
//             //         sd_html: Iml.config.sd_html
//             //     };
//             //     ajaxRequest('updateExtraData', params, function(data) {
//             //         if (data.success) {
//             //             for (var prop in Iml.config.delivery_cost_json) {
//             //                 if (Iml.config.delivery_cost_json.hasOwnProperty(prop)) {
//             //                     var cost_obj = Iml.config.delivery_cost_json[prop];
//             //                 }
//             //             }
//             //             $('input[name="delivery"][value="' + Iml.config.delivery_id + '"]').trigger('click');
//             //             $('.deliveryItem').removeClass('hidden');
//             //             $('.addressOfDelivery').empty().append('<span class="text-capitalize lead">' + Iml.config.region_id_to + '</span><br>' + Iml.config.sd_html);
//             //             $('.priceOfDelivery').add('#scost_' + Iml.config.delivery_id + ' .text-success')
//             //                 .empty().append(cost_obj.Price + ' ' + Iml.config.currency.stitle);
//             //             var $aod = $('.addressOfDelivery'),
//             //                 $inf_blk = $aod.parents('.infoBlock'),
//             //                 ib_heigth = $inf_blk.height(),
//             //                 aod_heigth = $aod.height();

//             //             $inf_blk.height(ib_heigth + aod_heigth);
//             //             $inf_blk.siblings().height(ib_heigth + aod_heigth);
//             //             $('#mapModal').modal('hide');
//             //             // Iml.config.sd_selected = truefalse,
//             //         }
//             //     });
//             // },
//             // mapMove: function(la, lo, code) {
//             //     myMap.panTo([la, lo], {
//             //         duration: 600,
//             //         timingFunction: 'ease-out',
//             //         callback: function() {
//             //             myMap.setZoom(10);
//             //         }
//             //     });
//             // },

//             // /**
//             //  * Запрос на стандартный AJAX контроллер (userAct)
//             //  *
//             //  * @param  (string)   action   Имя вызываемого публичного метода
//             //  * @param  (array|object)   params   Параметры, передающиеся методу
//             //  * @param  (function) callback
//             //  * @return
//             //  */
//             // ajaxRequest: function(action, params, callback) {
//             //     var config = this.options;
//             //     $.ajax({
//             //         url: config.url,
//             //         type: 'POST',
//             //         dataType: 'json',
//             //         data: {
//             //             module: 'Imldelivery',
//             //             typeObj: 0, //Доставка
//             //             typeId: config.delivery_id,
//             //             'class': 'Iml',
//             //             userAct: action,
//             //             params: params
//             //         },
//             //         success: callback
//             //     });
//             // },


//             // toggleActive: function(e, code) {
//             //     e.preventDefault();
//             //     $this = $('#sd_' + Iml.config.delivery_id + '_' + code);
//             //     $this.addClass('active').siblings('.selectSd').removeClass('active');

//             //     Iml.config.request_code = code;
//             //     Iml.config.sd_object = Iml.config.sd_data[code];

//             //     mapMove(Iml.config.sd_object.Latitude, Iml.config.sd_object.Longitude);
//             //     Iml.pricesLoad();
//             // },

//         };





//         var data_config = $('[id^="showMap_"]').data('config');
//         this.config = extend(defaults, data_config);
//         // console.log(this.config);
//         if (arguments[0] && typeof arguments[0] === "object") {
//             // console.log(arguments[0]);
//             this.config = extend(data_config, arguments[0]);
//         }


//         for (method in methods) {
//             if (methods.hasOwnProperty(method) && methods[method]) {
//                 methods[method].apply($this, Array.prototype.slice.call(arguments, 1));
//                 // console.log(methods[method].apply($this, Array.prototype.slice.call(arguments, 1)));
//             } else if (typeof method === 'object' || !method) {
//                 return methods.init.apply($this, arguments || []);
//             }
//         }
//         // Iml = $this;
//         // console.log(Iml);

//     };

// }());

// /*

// <div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="mapModalLabel">
//     <div class="modal-dialog modal-lg" role="document">
//         <div class="modal-content">
//             <div class="modal-header">
//                 <button type="button" class="close" data-dismiss="modal" aria-label="Отменить"><span aria-hidden="true">&times;</span></button>
//                 <h3 class="modal-title text-center" id="mapModalLabel">Выбор пункта самовывоза</h3>
//             </div>
//             <div class="modal-body">

//             </div>
//             <div class="modal-footer">
//                 {foreach json_decode($service_ids) as $code => $name}
//                     <div class="priceItem">
//                         <div class="name">{$name}</div>
//                         <div class="priceText" data-code="{$code}"></div>
//                     </div>
//                 {/foreach}
//                 <div class="buttons">
//                     <button type="submit" class="btn btn-default" data-dismiss="modal">Отменить</button>
//                     <button type="submit" class="btn btn-primary submitModal">Выбрать</button>
//                 </div>
//             </div>
//         </div>
//     </div>
// </div>


// */


// /*  // Public Methods

//     Iml.prototype.close = function() {
//         var _ = this;
//         this.modal.className = this.modal.className.replace(" scotch-open", "");
//         this.overlay.className = this.overlay.className.replace(" scotch-open",
//             "");
//         this.modal.addEventListener(this.transitionEnd, function() {
//             _.modal.parentNode.removeChild(_.modal);
//         });
//         this.overlay.addEventListener(this.transitionEnd, function() {
//             if (_.overlay.parentNode) _.overlay.parentNode.removeChild(_.overlay);
//         });
//     };

//     Iml.prototype.open = function() {
//         buildOut.call(this);
//         bindEventHandlers.call(this);
//         window.getComputedStyle(this.modal).height;
//         this.modal.className = this.modal.className +
//             (this.modal.offsetHeight > window.innerHeight ?
//                 " scotch-open scotch-anchored" : " scotch-open");
//         this.overlay.className = this.overlay.className + " scotch-open";
//     };
// */


















// // jQuery(document).ready(bindEventHandlers);

// // /**
// //  * Iml коллекция
// //  * @type (Object)
// //  */
// // Iml = {
// //     config: defaultData,
// //     modalShow: modalShow,
// //     mapInit: mapInit,
// //     regionsLoad: regionsLoad,
// //     sdByRegionLoad: sdByRegionLoad,
// //     sdSave: sdSave,
// //     pricesLoad: pricesLoad,
// //     pricesAfterLoad: pricesAfterLoad,
// //     mapMove: mapMove,
// //     ajaxRequest: ajaxRequest,
// //     bindEventHandlers: bindEventHandlers
// // };





// // function getIndexSd(code, data) {
// //     for (var i = data.length - 1; i >= 0; i--) {
// //         if (data[i].request_code == code) return i;
// //     }
// // }




























// // Private Methods

// function buildOut() {

//     var content, contentHolder, docFrag;

//     /*
//      * If content is an HTML string, append the HTML string.
//      * If content is a domNode, append its content.
//      */

//     if (typeof this.options.content === "string") {
//         content = this.options.content;
//     } else {
//         content = this.options.content.innerHTML;
//     }



//     // If closeButton option is true, add a close button
//     if (this.options.closeButton === true) {
//         this.closeButton = document.createElement("button");
//         this.closeButton.className = "scotch-close close-button";
//         this.closeButton.innerHTML = "×";
//         this.modal.appendChild(this.closeButton);
//     }

//     // If overlay is true, add one
//     if (this.options.overlay === true) {
//         this.overlay = document.createElement("div");
//         this.overlay.className = "scotch-overlay " + this.options.className;
//         docFrag.appendChild(this.overlay);
//     }

//     // Create content area and append to modal
//     contentHolder = document.createElement("div");
//     contentHolder.className = "scotch-content";
//     contentHolder.innerHTML = content;
//     this.modal.appendChild(contentHolder);

//     // Append modal to DocumentFragment
//     docFrag.appendChild(this.modal);

//     // Append DocumentFragment to body
//     document.body.appendChild(docFrag);

// }


// function bindEventHandlers() {

//     if (this.closeButton) {
//         this.closeButton.addEventListener('click', this.close.bind(this));
//     }

//     if (this.overlay) {
//         this.overlay.addEventListener('click', this.close.bind(this));
//     }

// }

// function transitionSelect() {
//     var el = document.createElement("div");
//     if (el.style.WebkitTransition) return "webkitTransitionEnd";
//     if (el.style.OTransition) return "oTransitionEnd";
//     return 'transitionend';
// }

