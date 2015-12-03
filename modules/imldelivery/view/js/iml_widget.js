var imlDataArray = [];
ymaps.ready().done(function() {
    //console.log(this);
    var $dataDiv = $('[class^="imlContainer"]');
    ajaxRequest('getSdRegions', [], function (data) {
        $.each(data.getSdRegions, function(index, val) {
            $('[id^="selectRegionCombo"]').append('<option value="'+index+'">'+val+'</option>');
        });
    })
    $dataDiv.each(function(i) {
        var imlData = JSON.parse($(this).attr('data-iml-info'));
        imlDataArray[i] = imlData;
        initIML_Map(imlData);
    });
});
function initIML_Map(imlData) {
    $('#map_'+imlData.deliveryId).empty();
    var myMap;
    if (imlData.regionIdTo == '') {
        imlData.regionIdTo = imlData.regionIdFrom;
    };
    //console.log(imlData);

    $.ajax({
        type: 'POST',
        url: imlData.url,
        data: { 
            action: 'getSdByRegion',
            params: {region: imlData.regionIdTo}
         },
        dataType: "json",
        success: function (data) {
            //console.log(data);
            data = data.getSdByRegion;
            $(imlData.listId).empty();

            myMap = new ymaps.Map('map_'+imlData.deliveryId, {
                center: [Number(data[0].Latitude), Number(data[0].Longitude)],
                zoom: 10
            });
            imlData.current_sd = data[0].RequestCode;

            $.each(data, function (i,el) {
                // создание объекта для карты
                var myPlacemark = new ymaps.Placemark([Number(el.Latitude), Number(el.Longitude)], {
                    iconContent: 'IML',
                    balloonContent: '<span style="font-weight: bold">' + el.Name + '</span>' + '<br />' +
                        el.Address + '<br />' +
                        'Оплата картой: ' + el.PaymentCard + '<br />' +
                        'Время работы: ' + el.WorkMode +
                        '<br /><button onclick="setSd(' + el.RequestCode + ',\'' + imlData + '\');return false;" >Выбрать</button>'
                }, {
                    preset: 'islands#violetStretchyIcon'
                });

                // добавление объекта на карту
                myMap.geoObjects.add(myPlacemark);

                // создание списка пунктов самовывоза рядом с картой
                var li_html = '<li onclick="setSd(' + el.RequestCode + ',\'' + imlData + '\');"><span style="font-weight: bold">' + el.Name + '</span>' + '<br />' +
                            el.Address + '<br />' +
                            'Оплата картой: ' + el.PaymentCard + '<br />' +
                            'Время работы: ' + el.WorkMode + '<br />' +
                    '   ---   </li>';
                $(imlData.listId).append(li_html);

            });

            addExtraLine(imlData);
        }
    });
    
};

function setSd (code,region) {
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
    var imlData = JSON.parse($('[class^="imlContainer"]').attr('data-iml-info'));
    var url = imlData.url;
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

function bindHandlers () {
    
}