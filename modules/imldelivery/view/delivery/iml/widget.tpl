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
var defaultData = {
        "deliveryId"        :"{$delivery.id}", 
        "serviceId"         :{$service_ids},
        "selectId"          :"#selectRegionCombo_{$delivery.id}", 
        "listId"            :"#sdlist_{$delivery.id}", 
        "regionIdFrom"      :"{$region_id_from}", 
        "regionIdTo"        :"{$region_id_to}", 
        "deliveryCostJson"  :{$delivery_cost_json},
        "loading"           :"<div class='loading'></div>"
    },
    IML = {
        imlData                 :   defaultData,
        initIML_Map             :   initIML_Map,
        getSdByRegionRender   :   getSdByRegionRender,
        setSd                   :   setSd,
        addExtraLine            :   addExtraLine,
        updatePrice             :   updatePrice,
        afterUpdatePrice        :   afterUpdatePrice,
        ajaxRequest             :   ajaxRequest,
        bindHandlers            :   bindHandlers,
        showMapModal            :   showMapModal,
        loadRegions             :   loadRegions,
        extractData             :   extractData
    };

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

function initIML_Map (regionIdTo) {
    
    IML.imlData.regionIdTo = regionIdTo || IML.imlData.regionIdFrom;
    var imlData = IML.imlData;

    $('#map_'+imlData.deliveryId).empty();
    var myMap;

    //console.log(imlData);
    var params = {
        region : imlData.regionIdTo
    }
    IML.ajaxRequest('getSdByRegion', params, IML.getSdByRegionRender);
};

function loadRegions () {
    ajaxRequest('getSdRegions', [], function (data) {
        $selectRegionList = $(IML.imlData.selectId);
        $.each(extractData(data), function(id, val) {
            $selectRegionList.append('<option value="'+id+'">'+val+'</option>');
        });
        $selectRegionList.val(IML.imlData.regionIdTo || IML.imlData.regionIdFrom);
    })
}

function extractData (response) {
    var $response = $('<html />').html(response.html);
    var dataObject = JSON.parse($response.find('#delivery_'+IML.imlData.deliveryId+' .additionalInfo').html());
    return dataObject.response;
}

function getSdByRegionRender (data) {
    var data = extractData(data);
    var imlData = IML.imlData;
    $(imlData.listId).empty();

    myMap = new ymaps.Map('map_'+imlData.deliveryId, {
        center: [Number(data[0].Latitude), Number(data[0].Longitude)],
        zoom: 10
    });
    IML.imlData.currentSd = data[0].RequestCode;

    $.each(data, function (i,el) {
        // создание объекта для карты
        var myPlacemark = new ymaps.Placemark([Number(el.Latitude), Number(el.Longitude)], {
            iconContent: 'IML',
            balloonContent: '<span style="font-weight: bold">' + el.Name + '</span>' + '<br />' +
                el.Address + '<br />' +
                'Оплата картой: ' + el.PaymentCard + '<br />' +
                'Время работы: ' + el.WorkMode +
                '<br /><button class="balloonSelectSd" data-code="'+el.RequestCode+'" id="bal_sd_'+imlData.deliveryId+'_'+el.RequestCode+'">Выбрать</button>'
        }, {
            preset: 'islands#violetStretchyIcon'
        });

        // добавление объекта на карту
        myMap.geoObjects.add(myPlacemark);
        //console.log(el);
        // создание списка пунктов самовывоза рядом с картой
        var li_html = '<li class="selectSd" data-code="'+el.RequestCode+'" id="sd_'+imlData.deliveryId+'_'+el.RequestCode+'"><span style="font-weight: bold">' + el.Name + '</span>' + '<br />' +
                    el.Address + '<br />' +
                    'Оплата картой: ' + el.PaymentCard + '<br />' +
                    'Время работы: ' + el.WorkMode + '<br /></li>';
        $(imlData.listId).append(li_html);
    });

    $('.loading').remove();
    IML.bindHandlers();

    //addExtraLine(imlData);
}

function setSd (imlData) {
    var params = getDefaultParams(region);
    addExtraLine(params);
}

function addExtraLine (imlData) {
    var params = {
        region_id_from  : imlData.regionIdFrom,
        region_id_to    : imlData.regionIdTo,
        service_id      : imlData.serviceId,
        request_code    : imlData.current_sd
    };
    ajaxRequest('addExtraLine', params, updatePrice);
}

function updatePrice (data) {
    ajaxRequest('getDeliveryCostAjax', [], afterUpdatePrice);
}

function afterUpdatePrice (data) {
    console.log(imlDataArray);
    
    //var $priceBlock = $('.price', '#delivery_'+deliveryId);
    //if (Number.isInteger(data.getDeliveryCostAjax)) {
    //    $priceBlock.empty().html('<span class="help"></span>'+data.getDeliveryCostAjax);
    //} else {
    //    var error = data.getDeliveryCostAjax;
    //    $.each(error, function(index, val) {
    //        $priceBlock.empty().html('<strong>'+val.Code+'</strong><span class="text-error">'+val.Mess+'</span>');
    //    });
    //}
}

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

function bindHandlers () {
    var deliveryId = IML.imlData.deliveryId;
    
    $('#showMap_'+deliveryId).unbind('click').on('click', function(e) {
        e.preventDefault();
        $(this).addClass('passive');
        $(IML.imlData.loading).appendTo('body').clone().appendTo('.modal-body');
        IML.ajaxRequest('loadMap', [], showMapModal);
    });
    
    $('#selectRegionCombo_'+deliveryId).unbind('change').on('change', function() {
        var regionIdTo = $(this).children(':selected').val();
        $(IML.imlData.loading).appendTo('.modal-body');
        initIML_Map(regionIdTo);
        //console.log($(this).children(':selected'));
    });

    $('#sdlist_{$delivery.id} .selectSd').unbind('click').on('click', function() {
        var $this = $(this);
        $this.addClass('active').siblings('.selectSd').removeClass('active');
        IML.imlData.currentSd = $this.attr('data-code');
    });

}
</script>