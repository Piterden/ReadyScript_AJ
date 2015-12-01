function initIML_Map(region) {
    $('#map').empty();
    var myMap;
    var url = $('#selectRegionCombo').attr('data-ajax-action');

    $.ajax({
        type: 'POST',
        url: url,
        data: { 
            action: 'getSdByRegion',
            params: {'region': region}
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
                        '<br /><button onclick="GetPVZ(' + arrObj[i1].RequestCode + ');" >Выбрать</button>'
                }, {
                    preset: 'islands#violetStretchyIcon'
                });

                // добавление объекта на карту
                myMap.geoObjects.add(myPlacemark);

                // создание списка пунктов самовывоза рядом с картой
                var li_html = '<li onclick="GetPVZ(' + arrObj[i1].RequestCode + ');"><span style="font-weight: bold">' + arrObj[i1].Name + '</span>' + '<br />' +
                            arrObj[i1].Address + '<br />' +
                            'Оплата картой: ' + paymentCardStr + '<br />' +
                            'Время работы: ' + arrObj[i1].WorkMode + '<br />' +
                    '   ---   </li>';
                $('#sdlist').append(li_html);

                i1++;
            });
        }
    });

    addExtraLine('region_to', region);
    addExtraLine('request_code', null);
    updatePrice();
};

function GetPVZ(code) {
    addExtraLine('request_code', code);
    updatePrice();
};

ymaps.ready().done(function () {

    var url = $('#selectRegionCombo').attr('data-ajax-action');
    $.ajax({
        url: url,
        data: {action: 'getSdRegions'},
        success: function (data) {
            data = JSON.parse(data);
            $.each(data.getSdRegions, function(index, val) {
                //console.log($('#selectRegionCombo'));
                $('#selectRegionCombo')
                .append('<option value='+index+'>'+val+'</option>')
            });
        }
    });

    initIML_Map('МОСКВА');
});


function addExtraLine (key, value) {
    var url = $('#selectRegionCombo').attr('data-ajax-action');
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: {action: 'addExtraLine', params: {
            key: key,
            value: value
        }},
    })
    .done(function(data) {
        //console.log(data);
        
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
}

function getExtraLine (key) {
    var url = $('#selectRegionCombo').attr('data-ajax-action');
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: {action: 'getExtraLine', params: {
            key: key
        }},
    })
    .done(function(data) {
        //console.log(data);
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
}

function updatePrice () {
    var url = $('#selectRegionCombo').attr('data-ajax-action');
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: {action: 'getDeliveryCostAjax'},
    })
    .done(function(data) {
        console.log(data.getDeliveryCostAjax);
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}
