{addjs file="http://api-maps.yandex.ru/2.1/?lang=ru_RU" basepath="root"}
{*addjs file="http://css-spinners.com/css/spinner/hexdots.css" basepath="root"*}

<div class="imlContainer_{$delivery.id}">
    <button id="showMap_{$delivery.id}" class="btn btn-primary btn-md">Показать карту</button>
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
        "delivery_id"        :"{$delivery.id}", 
        "service_id"         :{$service_ids},
        "select_id"          :"#selectRegionCombo_{$delivery.id}", 
        "list_id"            :"#sdlist_{$delivery.id}", 
        "region_id_from"      :"{$region_id_from}", 
        "region_id_to"        :"{$region_id_to}", 
        "delivery_cost_json"  :{$delivery_cost_json},
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
        extractData : extractData,
        getSdByRegionRender : getSdByRegionRender,
        saveDataToOrder : saveDataToOrder,
        priceUpdateRequest : priceUpdateRequest,
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
    $map = $(data.html).find('.mapContainer');
    $('.modal-body').append($map);
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
    
    IML.imlData.region_id_to = region_id_to || IML.imlData.region_id_from;
    var imlData = IML.imlData;

    $('#map_'+imlData.delivery_id).empty();
    var myMap;

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
        $selectRegionList = $(IML.imlData.select_id);
        $.each(extractData(data), function(id, val) {
            $selectRegionList.append('<option value="'+id+'">'+val+'</option>');
        });
        $selectRegionList.val(IML.imlData.region_id_to || IML.imlData.region_id_from);
    })
}

/**
 * Вынимает сожержимое контейнера .additionalInfo
 * @param  {*object*} response
 * @return {*object*} Ответ без оберток, данные получать через Smarty
 */
function extractData (response) {
    var $response = $('<html />').html(response.html);
    var dataObject = JSON.parse($response.find('#delivery_'+IML.imlData.delivery_id+' .additionalInfo').html());
    return dataObject.response;
}

/**
 * Получает пункты самовывоза в одном регионе
 * Создает HTML карты
 * @param  {*object*} data response
 * @return
 */
function getSdByRegionRender (data) {
    var data = extractData(data);
    var imlData = IML.imlData;
    $(imlData.list_id).empty();

    myMap = new ymaps.Map('map_'+imlData.delivery_id, {
        center: [Number(data[0].Latitude), Number(data[0].Longitude)],
        zoom: 10
    });
    IML.imlData.request_code = data[0].RequestCode;

    $.each(data, function (i,el) {
        // создание объекта для карты
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
        // создание списка пунктов самовывоза рядом с картой
        var li_html = '<li class="selectSd" data-code="'+el.RequestCode+'" id="sd_'+imlData.delivery_id+'_'+el.RequestCode+'"><span style="font-weight: bold">' + el.Name + '</span>' + '<br />' +
                    el.Address + '<br />' +
                    'Оплата картой: ' + el.PaymentCard + '<br />' +
                    'Время работы: ' + el.WorkMode + '<br /></li>';
        $(imlData.list_id).append(li_html);
    });

    $('.loading').remove();
    IML.bindHandlers();
}

function saveDataToOrder () {
    ajaxRequest('addExtraLine', IML.imlData);
}

function priceUpdateRequest () {
    saveDataToOrder();
    ajaxRequest('getDeliveryCostAjax', [], priceUpdateRender);
}

function priceUpdateRender (data) {
    console.log(extractData(data));
    
    //var $priceBlock = $('.price', '#delivery_'+delivery_id);
    //if (Number.isInteger(data.getDeliveryCostAjax)) {
    //    $priceBlock.empty().html('<span class="help"></span>'+data.getDeliveryCostAjax);
    //} else {
    //    var error = data.getDeliveryCostAjax;
    //    $.each(error, function(index, val) {
    //        $priceBlock.empty().html('<strong>'+val.Code+'</strong><span class="text-error">'+val.Mess+'</span>');
    //    });
    //}
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
        type: 'POST',
        dataType: 'json',
        data: {
            action: action,
            params: params
        }
    })
    .done(callback)
    .fail(function() {
    })
    .always(function() {
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
    
    $('#selectRegionCombo_'+delivery_id).unbind('change').on('change', function() {
        var region_id_to = $(this).children(':selected').val();
        $(IML.imlData.loading).appendTo('.modal-body');
        initIML_Map(region_id_to);
        //console.log($(this).children(':selected'));
    });

    $('#sdlist_{$delivery.id} .selectSd').unbind('click').on('click', function() {
        var $this = $(this);
        $this.addClass('active').siblings('.selectSd').removeClass('active');
        IML.imlData.request_code = $this.attr('data-code');
        priceUpdateRequest();
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