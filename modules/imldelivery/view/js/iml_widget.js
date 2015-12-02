function initIML_Map(region) {
    $('#map').empty();
    var myMap;
    var url = $('#selectRegionCombo').attr('data-ajax-action');

    $.ajax({
        type: 'POST',
        url: url,
        data: { 
            action: 'getSdByRegion',
            params: {region: region}
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
                        '<br /><button onclick="setSd(' + arrObj[i1].RequestCode + ',\'' + region + '\');return false;" >Выбрать</button>'
                }, {
                    preset: 'islands#violetStretchyIcon'
                });

                // добавление объекта на карту
                myMap.geoObjects.add(myPlacemark);

                // создание списка пунктов самовывоза рядом с картой
                var li_html = '<li onclick="setSd(' + arrObj[i1].RequestCode + ',\'' + region + '\');"><span style="font-weight: bold">' + arrObj[i1].Name + '</span>' + '<br />' +
                            arrObj[i1].Address + '<br />' +
                            'Оплата картой: ' + paymentCardStr + '<br />' +
                            'Время работы: ' + arrObj[i1].WorkMode + '<br />' +
                    '   ---   </li>';
                $('#sdlist').append(li_html);

                i1++;
            });

            var params = getDefaultParams(region);
            addExtraLine(params);
        }
    });
    
};

function getDefaultParams (code,region) {
    var $dataElement = $('#selectRegionCombo');
    var params = {
        service_id:     $dataElement.attr('data-service-id'),
        region_id_from: $dataElement.attr('data-region-from'),
        region_to:      region,
        request_code:   '1'
    };
    return params;
}

ymaps.ready().done(function () {
    ajaxRequest('getSdRegions', [], function (data) {
        $.each(data.getSdRegions, function(index, val) {
            $('#selectRegionCombo').append('<option value="'+index+'">'+val+'</option>');
        });
    })
    initIML_Map('САНКТ-ПЕТЕРБУРГ');
});

function setSd (code,region) {
    var params = getDefaultParams(region);
    addExtraLine(params);
}

function addExtraLine (params) {
    ajaxRequest('addExtraLine', params, updatePrice);
}

function updatePrice () {
    var $dataElement = $('#selectRegionCombo');
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
    var $dataElement = $('#selectRegionCombo');
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