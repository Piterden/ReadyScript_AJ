/**
 * Плагин, инициализирующий работу механизма выбора комплектации продукта.
 * Позволяет изменять связанные цены на странице при выборе комплектации,
 * а также подменяет ссылку на добавление товара в корзину с учетом комплектации
 */
(function($) {
    $.fn.changeOffer = function(method) {
        var defaults = {
                buyOneClick: '.buyOneClick', //Класс купить в один клик
                unitBlock: '.unitBlock',
                dataAttribute: 'changeCost',
                addToCartButtons: '.addToCart',
                offerProperty: '.offerProperty',
                notAvaliable: 'notAvaliable', // Класс для нет в наличии
                hiddenClass: 'hidden',
                offerParam: 'offer',
                context: '[data-id]', // Родительский элемент, ограничивающий поиск элемента с ценой
                //Параметры для многомерных комплектаций
                multiOffers: false, // Флаг использования мн. комплектаций
                multiOffersInfo: [], // Массив с информацией о комплектациях
                multiOfferName: '[name^="multioffers["]', // Списки многомерных комплектаций
                multiOfferPhotoBlock: '.multiOfferValueBlock', // Блок многомерной комплектации представленный как фото
                multiOfferPhotoWrap: '.multiOfferValues', // Оборачивающий блок с многомерными комплектации представленными как фото
                multiOfferPhotoSel: 'sel', // Класс выбранной многомерных комплектаций в виде фото
                multiOfferDialogPhoto: '.multiComplectations .image img', // Картинка в диалоговом окне многомерных комплектаций
                hiddenOffersName: '[name="hidden_offers"]', // Комплектации с информацией
                theOffer: '[name="offer"]', // Скрытое поле откуда забирается комплектация
                //Паметры для складов
                sticksRow: '.warehouseRow', //Оборачивающий контейнер с рисками значений заполнености склада
                stick: '.stick', //Риски со значениями заполнености склада
                stickFilledClass: 'filled', //Класс заполненой риски
                //Пораметры для фото
                jcarouselBlock: ".gallery", //Блок карусели
                mainPicture: ".mainPicture", //Класс главных фото
                previewPicture: ".gallery li", //Класс превью фото
                pictureDisable: "hidden", //Класс скрытия фото
                onShowOfferPhotos: null
            },
            args = arguments;

        return this.each(function() {
            var $this = $(this),
                data = $this.data('changeOffer');

            var methods = {
                init: function(initoptions) {
                    // console.log('INIT start');
                    if (data) return;
                    data = {};
                    $this.data('changeOffer', data);
                    data.options = $.extend({}, defaults, initoptions);
                    $this.change(changeOffer);

                    var context = $(this).closest(data.options.context);

                    //Многомерные комплектации
                    if ($(data.options.hiddenOffersName, context).length > 0) {
                        data.options.multiOffers = true;

                        //Соберём информацию для работы
                        $(data.options.hiddenOffersName, context).each(function(i) {
                            data.options.multiOffersInfo[i] = {};
                            data.options.multiOffersInfo[i].id = this;
                            data.options.multiOffersInfo[i].info = $(this).data('info');
                            data.options.multiOffersInfo[i].num = $(this).data('num');
                            data.options.multiOffersInfo[i].sticks = $(this).data('sticks');
                        });

                        //Навесим событие
                        context
                            .on('change', data.options.multiOfferName, changeMultiOffer)
                            .on('click', data.options.multiOfferPhotoBlock, changeMultiOfferPhotoBlock);
                    }

                    //Устанавливаем текущую комплектацию
                    var photoEx = new RegExp('#(\\d+)'),
                        res = photoEx.exec(location.hash);
                    res = (res !== null) ? res[1] : 0;

                    $('select[name="offer"]', context).val(res);
                    $('select[name="offer"] [value="' + res + '"]', context).click();
                    $('input[name="offer"][value="' + res + '"]', context).click();

                    //Если используются многомерные комплектации
                    var multioffer = $('input#offer_' + res);
                    if (multioffer.length) {
                        var multioffer_values = multioffer.data('info');
                        if (multioffer_values) {
                            for (var j in multioffer_values) {
                                $('[data-prop-title="' + multioffer_values[j][0] + '"]', context).val(multioffer_values[j][1]).change();
                                //Проверим, а многомерная комплектация эта представлена как фото, тогда выберем правильное фото при переключении
                                chooseRightOfferPhotoSelected($('[data-prop-title="' + multioffer_values[j][0] + '"]', context));
                            }
                        }
                    }
                    //Выбираем правильный offer, в случае, если у нулевой комплектации не прописаны свойства
                    $('[data-prop-title]:first', context).change();
                    // console.log('INIT finish');
                }
            };


            //private
            //Комплектации
            /**
             * Смена комплектации
             *
             */
            var changeOffer = function() {
                    var $selected = $this;

                    if ($this.get(0).tagName.toLowerCase() == 'select') {
                        $selected = $('option:selected', $this);
                    }

                    var list = $selected.data(data.options.dataAttribute);
                    var context = $selected.closest(data.options.context);
                    var offer = $selected.val();

                    //Сменим артикул и цену
                    $.each(list, function(selector, cost) {
                        $(selector, context).text(cost);
                    });

                    //Сменим единицу измерения, если нужно
                    if ((typeof($selected.data('unit')) != 'undefined') && ($selected.data('unit') !== "")) {
                        $(data.options.unitBlock, context).show();
                        $(data.options.unitBlock + " .unit", context).text($selected.data('unit'));
                    } else {
                        $(data.options.unitBlock, context).hide();
                    }

                    $(data.options.offerProperty).addClass(data.options.hiddenClass);
                    $(data.options.offerProperty + '[data-offer="' + offer + '"]').removeClass('hidden');

                    //Добавим параметр комплектации к ссылке купить в 1 клик
                    if ($(data.options.buyOneClick, context).length > 0) {
                        var clickHref = $(data.options.buyOneClick, context).data('href').split('?'); //Получим урл
                        $(data.options.buyOneClick, context).data('href', clickHref[0] + "?offer_id=" + offer);
                    }

                    //Показываем фото комплектаций
                    showOfferPhotos($selected);
                    //Показывает доступность комплектации
                    showAvailability(offer);
                },
                /**
                 * Показывает наличие на складе
                 * @param Array stock_arr - массив с наличием "палочек наличия" для отображения
                 */
                showStockSticks = function(stock_arr, context) {
                    //Сбросим наличие
                    $(data.options.sticksRow + " " + data.options.stick, context).removeClass(data.options.stickFilledClass);
                    //Установим значения рисок заполнености склада
                    $(data.options.sticksRow, context).each(function() {
                        var warehouse_id = $(this).data('warehouseId');
                        var num = stock_arr[warehouse_id]; //Количество рисок для складов
                        for (var j = 0; j < num; j++) {
                            $(data.options.stick + ":eq(" + j + ")", $(this)).addClass(data.options.stickFilledClass);
                        }
                    });
                },
                /**
                 * Показывает наличие товара, приписывает или убирает класс
                 * notAvaliable
                 * Если ret == true, то возвращает кол-во
                 */
                showAvailability = function(offerValue) {
                    var context = $this.closest(data.options.context),
                        num;

                    if (data.options.multiOffers) { //Если используются многомерные комплектации
                        num = data.options.multiOffersInfo[offerValue].num;
                        //Покажем наличие на складах
                        showStockSticks(data.options.multiOffersInfo[offerValue].sticks, context);
                    } else {
                        var offer = $(data.options.theOffer + "[value='" + offerValue + "']", context); //Если радиокнопками
                        if (!offer.length) { //Если выпадающее меню
                            offer = $(data.options.theOffer + " option:selected", context);
                        }
                        num = offer.data('num');
                        //Покажем наличие на складах
                        showStockSticks(offer.data('sticks'), context);
                    }

                    if (typeof(num) != 'undefined') {
                        if (num === 0) { //Если не доступно
                            $(context).addClass(data.options.notAvaliable);
                        } else { //Если  доступно
                            $(context).removeClass(data.options.notAvaliable);
                        }
                    }
                },
                //Многомерные комплектации
                /**
                 * Смена/Выбор многомерной комплектации
                 *
                 */
                changeMultiOffer = function() {
                    var context = $this.closest(data.options.context);

                    var selected = []; //Массив, что выбрано
                    //Соберём информацию, что изменилось
                    $(data.options.multiOfferName, context).each(function(i) {
                        selected[i] = {};
                        selected[i].title = $(this).data('propTitle');
                        selected[i].value = $(this).val();
                    });

                    //Найдём инпут с комплектацией
                    var input_info = data.options.multiOffersInfo,
                        i,
                        offer = false; //Cпрятанная комплектация, которую мы выбрали

                    for (var j = 0; j < input_info.length; j++) {
                        var info = input_info[j].info; //Группа с информацией
                        // console.log("input_info", input_info[j]);
                        var found = 0; //Флаг, что найдены все совпадения
                        for (var m = 0; m < info.length; m++) {
                            for (i = 0; i < selected.length; i++) {
                                if ((selected[i].title == info[m][0]) && (selected[i].value == info[m][1])) {
                                    found++;
                                    // console.log(selected[i].title);
                                    // console.log("selected_match", selected[i]);
                                }
                            }
                            if (found == selected.length) { //Если удалось найди совпадение, то выходим
                                offer = input_info[j].id;
                                // console.log("compl", selected[i]);
                                break;
                            }
                        }
                    }

                    //Отметим выбранную комплектацию
                    var offer_val = 0;
                    if (offer) { // Если комплектация выбранная присутствует
                        offer_val = $(offer).val();
                        $(data.options.theOffer, context).val(offer_val);
                    } else { // Если комплектации такой не нашлось, выберем нулевую компл.
                        $(data.options.theOffer, context).val(offer_val);
                    }

                    //Добавим параметр комплектаций к ссылке купить в 1 клик, если купить в 1 клик присутствует
                    if ($(data.options.buyOneClick, context).length > 0) {
                        var clickHref = $(data.options.buyOneClick, context).data('href').split('?'); //Получим урл
                        //Соберём информацию что выбрано из многомерных
                        var multi_selected = [];
                        $(selected).each(function(i) {
                            multi_selected.push('multioffers[]=' + encodeURIComponent(selected[i].title) + ": " + encodeURIComponent(selected[i].value));
                        });
                        multi_selected = multi_selected.join('&');
                        //Запишем урл
                        $(data.options.buyOneClick, context).data('href', clickHref[0] + "?" + multi_selected);
                    }

                    $(data.options.offerProperty).addClass(data.options.hiddenClass);
                    $(data.options.offerProperty + '[data-offer="' + offer_val + '"]').removeClass('hidden');

                    //Поменяем цену за комплектацию
                    var dataCost = $(offer).data('changeCost');
                    for (i in dataCost) {
                        $(i, context).html(dataCost[i]);
                    }

                    //Сменим единицу измерения, если нужно
                    if ((typeof($(offer).data('unit')) !== 'undefined') && ($(offer).data('unit') !== "")) {
                        $(data.options.unitBlock, context).show();
                        $(data.options.unitBlock + " .unit", context).text($(offer).data('unit'));
                    } else {
                        $(data.options.unitBlock, context).hide();
                    }

                    //Показываем фото комплектаций
                    if ($(data.options.hiddenOffersName + "[value='" + offer_val + "']", context).length > 0) {
                        $selected = $(data.options.hiddenOffersName + "[value='" + offer_val + "']", context);
                        showOfferPhotos($selected);
                    }

                    //Покажем наличие товара после выбора комплектации
                    showAvailability(offer_val);
                    //Проверяем на наличие хар-к с нулевым остатком
                    checkEmptyOffers(selected);
                },

                /**
                 * Вычисляем характеристики, для которых нет в наличии товаров
                 * @param  {obj} 		selected  		Массив выбранных характеристик
                 */
                checkEmptyOffers = function(selected) {
                    // Из массива выбранного делаем объекты данных для каждой хар-ки отдельно
                    selected.forEach(function(el, i) {
                        // Исключаем выбранное значение из возможных
                        var t = selected.filter(function(v, idx) {
                            return idx != i;
                        });
                        disableOptions({ // готовим объект и передаем его метод для изменения DOM
                            name: el.title, // имя хар-ки по которой проходим
                            activeValue: el.value, // выбранное значение хар-ки
                            disabled: [], // сюда отфильтруются только нулевые остатки из всех siblings (ниже)
                            // фильтруем компл, оставим только актуальные для выбранных значений
                            siblings: data.options.multiOffersInfo.filter(function(v) {
                                // здесь ишем чтобы не совпадало с выбранной по которой проходим но совпадало с
                                return v.info.every(function(a) { // выбранными остальными
                                    return (a[0] == el.title && a[1] != el.value) || t.some(function(o) {
                                        return JSON.stringify(o) === JSON.stringify({ // объекты только так сравнивать)))
                                            title: a[0],
                                            value: a[1]
                                        });
                                    });
                                });
                            })
                        });
                    });
                },

                /**
                 * Зачеркиваем характеристики
                 * @param  {obj}	data	Массив данных
                 */
                disableOptions = function(d) {
                    var $listEl = $('.themed[data-prop-title="' + d.name + '"]'),
                        $items,
                        getValue = function(name, info) {
                            return info.filter(function(ar) {
                                return ar[0] == name;
                            })[0][1];
                        };
                    if (!$listEl.length) {
                        $listEl = $('[data-wrapper="' + d.name + '"]');
                    }

                    if ($listEl.hasClass('themed')) { // Для элементов select-or-die (списки)
                        $items = $listEl.prev('.sod_list_wrapper').find('.sod_option:not(.selected)');
                        if (d.siblings.length != $items.length) {
                            setTimeout(function() {
                                disableOptions(d);
                            }, 400);
                            return false;
                        }
                    }
                    if ($listEl.hasClass('multiofferBlock')) { // Для элементов moItem (квадраты)
                        $items = $listEl.children('.moItem:not(.active)');
                    }
                    d.disabled = d.siblings.filter(function(s) {
                        s.title = d.name;
                        s.value = getValue(s.title, s.info);
                        return s.num < 1;
                    });
                    $items.removeClass('empty').each(function() {
                        $(this).attr('title', $(this).data('value'));
                    });
                    d.disabled.forEach(function(val) {
                        $items.each(function() {
                            var _this = $(this);
                            if (_this.text().trim() == val.value) {
                                _this.addClass('empty').attr('title', 'Нет в наличии');
                            }
                        });
                    });
                    return d;
                },

                /**
                 * Смена многомерной комплектации, если она представлена как фото
                 */
                changeMultiOfferPhotoBlock = function() {
                    var context = $(this).closest(data.options.multiOfferPhotoWrap);
                    $(data.options.multiOfferPhotoBlock, context).removeClass(data.options.multiOfferPhotoSel);
                    $(this).addClass(data.options.multiOfferPhotoSel);
                    $('input', context).val($(this).data('value'));
                    $('input', context).change();

                    //Если указано data-image то поменяем фото(для окна с многомерными комплектациями, не для карточки товара)
                    if (typeof($(this).data('isDialog')) != 'undefined') {
                        var bigPhoto = $(this).data('image');
                        //Сохраним основное фото, чтобы можно было переключится на него, если фото для многомерной комплектации не существует
                        var pagePhoto = $(data.options.multiOfferDialogPhoto);
                        if (typeof(pagePhoto.data('mainPhoto')) == 'undefined') {
                            pagePhoto.data('mainPhoto', pagePhoto.attr('src'));
                        }

                        //Если есть заданные фото у фото значения комплектации, то переключимся на него
                        if (typeof(bigPhoto) != 'undefined') {
                            pagePhoto.attr('src', bigPhoto);
                        } else {
                            pagePhoto.attr('src', pagePhoto.data('mainPhoto'));
                        }
                    }

                    return false;
                },
                /**
                 * Проверяет фото ли это в виде многомерной комплектации и выбирает правильное фото, если это так
                 */
                chooseRightOfferPhotoSelected = function(offer) {

                    var photoWrap = offer.closest(data.options.multiOfferPhotoWrap);
                    if (photoWrap.length) {
                        $(data.options.multiOfferPhotoBlock, photoWrap).removeClass(data.options.multiOfferPhotoSel);
                        $(data.options.multiOfferPhotoBlock + "[data-value='" + offer.val() + "']", photoWrap).addClass(data.options.multiOfferPhotoSel);
                    }
                },
                //Фото комплектаций

                /**
                 * Показывает фото выбранной комплектаций
                 *
                 */
                showOfferPhotos = function(offer) {
                    var context = $this.closest(data.options.context);
                    //Получим массив фото из комплектаций
                    var images = offer.data('images');
                    if (!images || !images.length) images = [];

                    $(data.options.mainPicture, context).addClass(data.options.pictureDisable).removeAttr('rel');

                    //Покажем, только те, которые принадлежат комплектации и переключимся на первое фото иначе,
                    //покажем все фото и перключимся на первое фото
                    if (images.length > 0) {
                        //Скроем все превью фото
                        $(data.options.previewPicture, context).addClass(data.options.pictureDisable);

                        //Пройдёмся по главным фото
                        var mainFound = false; //Флаг главного фото
                        $(data.options.mainPicture, context).each(function(i) {
                            var id = $(this).data('id'); //id картинки
                            if (!mainFound && in_array(id, images)) {
                                $(this).removeClass(data.options.pictureDisable).attr('rel', 'bigphotos');
                                mainFound = true; //Найдено первое главное фото
                            } else if (in_array(id, images)) {
                                $(this).attr('rel', 'bigphotos');
                            }
                        });

                        //Пройдёмся по превью фото
                        $(data.options.previewPicture, context).each(function(i) {
                            var id = $(this).data('id'); //id картинки
                            if (in_array(id, images)) {
                                $(this).removeClass(data.options.pictureDisable);
                            }
                        });
                    } else {
                        //Покажем все фото
                        $(data.options.mainPicture + ":eq(0)", context).removeClass(data.options.pictureDisable);
                        $(data.options.mainPicture, context).attr('rel', 'bigphotos');
                        $(data.options.previewPicture, context).removeClass(data.options.pictureDisable);
                    }

                    if (typeof(data.options.onShowOfferPhotos) == 'function')
                        data.options.onShowOfferPhotos.call(this, offer, context, data);

                },
                /**
                 * Проверяет есть ли в массиве нужный элемента
                 *
                 * @param mixed needle      - то что ищем
                 * @param array haystack    - массив в котором нужно искать
                 *
                 * @returns {Boolean}
                 */
                in_array = function(needle, haystack) {
                    var key = '';
                    for (key in haystack) {
                        if (haystack[key] == needle) {
                            return true;
                        }
                    }
                    return false;
                };

            if (methods[method]) {
                methods[method].apply(this, Array.prototype.slice.call(args, 1));
            } else if (typeof method === 'object' || !method) {
                return methods.init.apply(this, args);
            }
        });
    };

})(jQuery);


$(function() {
    $('[name="offer"]').changeOffer({
        jcarouselBlock: ".gallery .wrap", //Блок карусели
        onShowOfferPhotos: function(offer, context, data) //Индивидуально для темы fashion
            {
                if (offer.closest('.multiComplectations').length === 0) { //Если мы находимся в карточке товара
                    //Обновим привязки карусели и показа фото
                    $(data.options.previewPicture, context).removeClass("first");
                    $(data.options.previewPicture + ":visible:eq(0)", context).addClass("first");

                    if (typeof($(data.options.jcarouselBlock, context).data('jcarousel')) != 'undefined') {
                        $(data.options.jcarouselBlock, context).jcarousel('reload', {
                            items: "li:visible"
                        });

                        //Пролистнём к первому пункту карусели
                        $(data.options.jcarouselBlock, context).jcarousel('scroll', 0);
                    }

                    $.colorbox.remove();
                    $('.product .main a.item[rel="bigphotos"]').colorbox({
                        rel: 'bigphotos',
                        className: 'titleMargin',
                        opacity: 0.2
                    });
                }
            }

    });
});
