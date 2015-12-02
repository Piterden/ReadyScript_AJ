ymaps.ready().done(function () {
    var dataDiv = $('[class^="imlContainer"]');
    //var deliveryId = $('')
    var imlData = dataDiv.attr('data-iml-info');
    imlData = JSON.parse(imlData);
    //console.log(data);
    ajaxRequest('getSdRegions', [], function (data) {
        $.each(data.getSdRegions, function(index, val) {
            $('[id^="selectRegionCombo"]').append('<option value="'+index+'">'+val+'</option>');
        });
    })
    initIML_Map(imlData);
    //getExtraLine('iml_region_to');
});
function initIML_Map(imlData) {
    $('#map').empty();
    var myMap;
    //var url = '';
    var url = $('[class^="imlContainer"]').attr('data-ajax-action');

    $.ajax({
        type: 'POST',
        url: url,
        data: { 
            action: 'getSdByRegion',
            params: {region: imlData.region}
         },
        dataType: "json",
        success: function (data) {
            var i1 = 0;
            var arrObj = [];
            data = data.getSdByRegion;
            $('#sdlist').empty();

            myMap = new ymaps.Map('map', {
                center: [Number(data[0].Latitude), Number(data[0].Longitude)],
                zoom: 10
            });
            //console.log(data);
            $.each(data, function () {
                inner = this;
                arrObj[i1] = inner;

                var paymentCardStr = "Да";
                if (arrObj[i1].PaymentCard == 0) { paymentCardStr = "Нет"; }

                // создание объекта для карты
                var myPlacemark = new ymaps.Placemark([Number(arrObj[i1].Latitude), Number(arrObj[i1].Longitude)], {
                    iconContent: 'IML',
                    balloonContent: '<span style="font-weight: bold">' + arrObj[i1].Name + '</span>' + '<br />' +
                        arrObj[i1].Address + '<br />' +
                        'Оплата картой: ' + paymentCardStr + '<br />' +
                        'Время работы: ' + arrObj[i1].WorkMode +
                        '<br /><button onclick="GetPVZ(' + arrObj[i1].RequestCode + ',\'' + region + '\');return false;" >Выбрать</button>'
                }, {
                    preset: 'islands#violetStretchyIcon'
                });

                // добавление объекта на карту
                myMap.geoObjects.add(myPlacemark);

                // создание списка пунктов самовывоза рядом с картой
                var li_html = '<li onclick="GetPVZ(' + arrObj[i1].RequestCode + ',\'' + region + '\');"><span style="font-weight: bold">' + arrObj[i1].Name + '</span>' + '<br />' +
                            arrObj[i1].Address + '<br />' +
                            'Оплата картой: ' + paymentCardStr + '<br />' +
                            'Время работы: ' + arrObj[i1].WorkMode + '<br />' +
                    '   ---   </li>';
                $('#sdlist').append(li_html);

                i1++;
            });

            var params = getDefaultParams(code,region);
            addExtraLine(params);
        }
    });
    
};

function getDefaultParams (code,region) {
    var dataDiv = $('[class^="imlContainer"]');
    var imlData = dataDiv.attr('data-iml-info');
    imlData = JSON.parse(imlData);var $dataElement = $('[class^="imlContainer"]');
    //console.log($dataElement.data);
    var params = {
        service_id:     imlData.serviceId,
        region_id_from: imlData.regionIdFrom,
        region_to:      region,
        request_code:   code
    };
    return params;
}

function GetPVZ (code,region) {
    var params = getDefaultParams(region);
    addExtraLine(params);
};

function addExtraLine (params) {
    ajaxRequest('addExtraLine', params, updatePrice);
}

function getExtraLine (key) {
    ajaxRequest('getExtraLine', key, getExtraLineCallback);
}

function getExtraLineCallback (data) {
    //if (!data.getExtraLine.iml_region_to.value) {data.getExtraLine.iml_region_to.value = 'МОСКВА'};
    initIML_Map('МОСКВА');
    $('[class^="imlContainer"]').val(data.getExtraLine.iml_region_to.value);
    //console.log(data.getExtraLine.iml_region_to.value);
}

function updatePrice () {
    var $dataElement = $('[class^="imlContainer"]');
    var params = {
        service_id: $dataElement.attr('data-service-id'),
        region_id_from: $dataElement.attr('data-region-from')
    };
    ajaxRequest('getDeliveryCostAjax', params, updatePriceCallback);
}

function updatePriceCallback (data) {
    var deliveryId = $dataElement.parents('li').attr('data-delivery-id');
    var $priceBlock = $('.price', '#delivery_'+deliveryId);
    //console.log(data.getDeliveryCostAjax);
    if (Number.isInteger(data.getDeliveryCostAjax)) {
        $priceBlock.empty().html('<span class="help"></span>'+data.getDeliveryCostAjax);
    } else {
        var error = data.getDeliveryCostAjax;
        $.each(error, function(index, val) {
            $priceBlock.empty().html('<strong>'+val.Code+'</strong><span class="text-error">'+val.Mess+'</span>');
        });
    }
}

function ajaxRequest (action, params, callback) {
    var $dataElement = $('[class^="imlContainer"]');
    var url = $dataElement.attr('data-ajax-action');
    var deliveryId = $dataElement.parents('li').attr('data-delivery-id');
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: {action: action, params: params},
    })
    .done(callback)
    .fail(function() {
    })
    .always(function() {
    });
    
}
