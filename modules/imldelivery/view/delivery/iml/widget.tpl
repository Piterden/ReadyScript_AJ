{addjs file="http://api-maps.yandex.ru/2.1/?lang=ru_RU" basepath="root"}

<div class="imlContainer_{$delivery.id}">
    <button id="showMap_{$delivery.id}" class="btn btn-primary show-map spacing">Выбрать пункт самовывоза</button>
</div>
<!-- Modal -->
<div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="mapModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Отменить"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title text-center" id="mapModalLabel">Выбор пункта самовывоза</h3>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                {foreach json_decode($service_ids) as $code => $name}
                    <div class="priceItem">
                        <div class="name">{$name}</div>
                        <div class="priceText" data-code="{$code}"></div>
                    </div>
                {/foreach}
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-primary submitModal">Сохранить</button>
            </div>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(bindHandlers);

/**
 * Начальные значения. Подгружаются через Smarty.
 * @type {*Object*}
 */
var defaultData = {
        "url"               :"{$router->getUrl('shop-front-checkout', ['Act' => 'userAct'])}",
        "delivery_id"        :"{$delivery.id}", 
        "delivery_cost_json"  :{$delivery_cost_json},
        "service_id"         :{$service_ids},
        "region_id_from"      :"{$region_id_from}", 
        "region_id_to"        :"{$region_id_to}", 
        "currency"           :"{$currency}", 
        "select_id"          :"#selectRegionCombo_{$delivery.id}", 
        "list_id"            :"#sdlist_{$delivery.id}", 
        "loading"           :"<div class='loading'></div>"
    },
    /**
     * IML коллекция
     * @type {*Object*}
     */
    IML = {
        imlData : defaultData,
        showMapModal : showMapModal,
        initIML_Map : initIML_Map,
        loadRegions : loadRegions,
        getSdByRegionRender : getSdByRegionRender,
        saveDataToOrder : saveDataToOrder,
        priceUpdateRender : priceUpdateRender,
        ajaxRequest : ajaxRequest,
        bindHandlers : bindHandlers
    };

/**
 * Показывает модальное окно с картой
 * @param  {*object*} data    Ответ сервера
 * @return
 */
function showMapModal (data) {
    $('body > .loading').remove();
    $('#mapModal').modal('show');
    $('.modal-body').append($(data.data));
    loadRegions();
    initIML_Map();
    //$(data.html).find('.mapContainer').appendTo('.imlContainer_{$delivery.id}');
    //console.log($mapContainer);
}

/**
 * Подгружает карту из API Ya.Maps
 * @param  {*string*} region_id_to Код региона куда делать доставку
 * @return
 */
function initIML_Map (region_id_to) {
    
    IML.imlData.region_id_to = region_id_to ? region_id_to : IML.imlData.region_id_to;        
    var imlData = IML.imlData;

    $('#map_'+imlData.delivery_id).empty();

    //console.log(imlData);
    var params = {
        region : imlData.region_id_to
    }
    IML.ajaxRequest('getSdByRegion', params, IML.getSdByRegionRender);
};

/**
 * Грузит список доступных регионов в select
 * @return
 */
function loadRegions () {
    ajaxRequest('getSdRegions', [], function (data) {
        //console.log(data);
        var $selectRegionList = $(IML.imlData.select_id);
        $.each(data.data, function(id, val) {
            $selectRegionList.append('<option value="'+id+'">'+val+'</option>');
        });
        $selectRegionList.val(IML.imlData.region_id_to || IML.imlData.region_id_from);
    })
}

/**
 * Получает пункты самовывоза в одном регионе
 * Создает HTML карты
 * @param  {*object*} data response
 * @return
 */
function getSdByRegionRender (data) {
    data = data.data;
    var imlData = IML.imlData;
    console.log(imlData);
    //var myMap;
    $(imlData.list_id).empty();

    myMap = new ymaps.Map('map_'+imlData.delivery_id, {
        center: [Number(data[0].Latitude), Number(data[0].Longitude)],
        zoom: 10
    });
    IML.imlData.request_code = data[0].RequestCode;
    IML.imlData.sd_object = data[0];
    IML.imlData.sd_list = {};

    $.each(data, function (i,el) {
        // создание объекта для карты
        IML.imlData.sd_list[el.RequestCode] = el;
        var myPlacemark = new ymaps.Placemark([Number(el.Latitude), Number(el.Longitude)], {
            iconContent: 'IML',
            balloonContent: '<span style="font-weight: bold">' + el.Name + '</span>' + '<br />' +
                el.Address + '<br />' +
                'Оплата картой: ' + el.PaymentCard + '<br />' +
                'Время работы: ' + el.WorkMode +
                '<br /><button class="balloonSelectSd" data-code="'+el.RequestCode+'" id="bal_sd_'+imlData.delivery_id+'_'+el.RequestCode+'">Выбрать</button>'
        }, {
            preset: 'islands#violetStretchyIcon'
        });

        // добавление объекта на карту
        myMap.geoObjects.add(myPlacemark);
        //console.log(el);
        var active = (i==0) ? ' active' : '';
        // создание списка пунктов самовывоза рядом с картой
        var li_html = '<li class="selectSd'+active+'" data-code="'+el.RequestCode+'" id="sd_'+imlData.delivery_id+'_'+el.RequestCode+'"><span style="font-weight: bold">' + el.Name + '</span>' + '<br />' +
                    el.Address + '<br />' +
                    'Оплата картой: ' + el.PaymentCard + '<br />' +
                    'Время работы: ' + el.WorkMode + '<br /></li>';
        $(imlData.list_id).append(li_html);
    });

    $('.loading').remove();
    saveDataToOrder()
    IML.bindHandlers();
}

function saveDataToOrder () {
    var params = {
        service_id      : IML.imlData.service_id,
        region_id_from  : IML.imlData.region_id_from,
        region_id_to    : IML.imlData.region_id_to,
        request_code    : IML.imlData.request_code
    }
    ajaxRequest('updateExtraData', params, function(data) {
        if (data.success) {
            ajaxRequest('getDeliveryCostAjax', [], priceUpdateRender);
        };
    });
}

function priceUpdateRender (data) {
    $.each(data.data, function(i,v) {
        var $priceBlock = $('.priceText[data-code="'+i+'"]', $('#delivery_'+IML.imlData.delivery_id));
        console.log(i,v);
        if (v.Price) {
            $priceBlock.text(v.Price);
        } else if (v.Code && v.Mess) {
            $priceBlock.attr({
                dataErrorCode: v.Code,
                dataErrorMess: v.Mess
            }).text('Прайс лист отсутствует');
        } else {
            $priceBlock.text('Неизвестная ошибка!');
        }
    });
}

/**
 * Стандартный контроллер AJAX
 * @param  {*string*}   action   Имя вызываемого PHP метода. Класс - \Imldelivery\Model\DeliveryType->action()
 * @param  {*array*|*object*}   params   Параметры, передающиеся методу
 * @param  {*Function*} callback
 * @return 
 */
function ajaxRequest (action, params, callback) {
    var imlData = IML.imlData;
    $.ajax({
        url  : imlData.url,
        type : 'POST',    
        dataType : 'json',    
        data : {
            module  : 'Imldelivery',
            typeObj : 0, //Доставка
            typeId  : imlData.delivery_id,
            'class' : 'Iml',
            userAct : action,
            params  : params
        },
        success : callback
    });
}

/**
 * Навешивает обработчики на элементы
 * @return
 */
function bindHandlers () {
    var delivery_id = IML.imlData.delivery_id;
    
    $('#showMap_'+delivery_id).unbind('click').on('click', function(e) {
        e.preventDefault();
        $(this).addClass('passive');
        $(IML.imlData.loading).appendTo('body').clone().appendTo('.modal-body');
        IML.ajaxRequest('loadMap', [], showMapModal);
    });
    
    $(IML.imlData.select_id).unbind('change').on('change', function() {
        var region_id_to = $(this).children(':selected').val();
        $(IML.imlData.loading).appendTo('.modal-body');
        initIML_Map(region_id_to);
    });

    $(IML.imlData.list_id+' .selectSd').unbind('click').on('click', function() {
        var $this = $(this),
            code = $this.attr('data-code');
        $this.addClass('active').siblings('.selectSd').removeClass('active');
        IML.imlData.request_code = code;
        IML.imlData.sd_object = IML.imlData.sd_list[code];
        console.log(IML.imlData);
        saveDataToOrder();
    });

    $('#mapModal').unbind('hide.bs.modal').on('hide.bs.modal', function(e) {
        $this = $(this);
        $this.find('.modal-body').empty();
    });

    $('button .submitModal').unbind('click').on('click', function(e) {
        saveDataToOrm();
    });
}
</script>