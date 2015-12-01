{addjs file="http://api-maps.yandex.ru/2.1/?lang=ru_RU" basepath="root"}
{addjs file="{$mod_js}iml_widget.js" basepath="root"}

<div class="infoContainer{$delivery.id}">
    <div class="costWrapper">
        <div class="cost">{$delivery_cost|@print_r}</div>
    </div>
    <select id="selectRegionCombo" data-ajax-action="{$router->getUrl('imldelivery-front-ajaxctrl')}" onchange="return initIML_Map(this.value);"></select>
    <div class="mapWrapper">
        <div id="map" style="height:600px; width:600px; float:left;"><ymaps class="ymaps-2-1-31-map ymaps-2-1-31-i-ua_js_yes ymaps-2-1-31-map-ru" style="width: 600px; height: 600px;"></div>
        <div style="overflow: auto; height:600px; width:250px; padding:0; font-size:12px;">
            <ul id="sdlist" style="padding-left:2px;"></ul>
        </div>
    </div>
</div>
