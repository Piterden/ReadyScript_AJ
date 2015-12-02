{addjs file="http://api-maps.yandex.ru/2.1/?lang=ru_RU" basepath="root"}
{addjs file="{$mod_js}iml_widget.js" basepath="root"}

<div class="imlContainer_{$delivery.id}" data-ajax-action="{$router->getUrl('imldelivery-front-ajaxctrl')}" data-iml-info='{ "deliveryId":"{$delivery.id}", "serviceId":"{$service_id}",  "selectId":"#selectRegionCombo_{$delivery.id}", "listId":"#sdlist_{$delivery.id}", "regionIdFrom":"{$region_id_from}", "deliveryCost":"{$delivery_cost}" }'>
    <select id="selectRegionCombo_{$delivery.id}"></select>
    <div class="mapWrapper">
        <div id="map_{$delivery.id}" style="height:600px; width:600px; float:left;"><ymaps class="ymaps-2-1-31-map ymaps-2-1-31-i-ua_js_yes ymaps-2-1-31-map-ru" style="width: 600px; height: 600px;"></div>
        <div style="overflow: auto; height:600px; width:250px; padding:0; font-size:12px;">
            <ul id="sdlist_{$delivery.id}" style="padding-left:2px;"></ul>
        </div>
    </div>
</div>
