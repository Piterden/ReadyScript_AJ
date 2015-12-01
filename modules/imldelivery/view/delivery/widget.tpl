{addjs file="http://api-maps.yandex.ru/2.1/?lang=ru_RU" basepath="root"}
{addjs file="{$mod_js}iml_widget.js" basepath="root"}

<div class="infoContainer{$delivery.id}">
    <select id="selectRegionCombo" data-ajax-action="{$router->getUrl('imldelivery-front-ajaxctrl')}" onchange="return initIML_Map(this.value);" data-service-id="{$service_id}"></select>
    <div class="mapWrapper">
        <div id="map" style="height:600px; width:600px; float:left;"><ymaps class="ymaps-2-1-31-map ymaps-2-1-31-i-ua_js_yes ymaps-2-1-31-map-ru" style="width: 600px; height: 600px;"></div>
        <div style="overflow: auto; height:600px; width:250px; padding:0; font-size:12px;">
            <ul id="sdlist" style="padding-left:2px;"></ul>
        </div>
    </div>
</div>
