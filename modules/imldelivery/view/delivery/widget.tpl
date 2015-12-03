{addjs file="http://api-maps.yandex.ru/2.1/?lang=ru_RU" basepath="root"}
{addjs file="{$mod_js}iml_widget.js" basepath="root"}
{assign var=ajaxUrl value=$router->getUrl("imldelivery-front-ajaxctrl")}

<div class="imlContainer_{$delivery.id}" data-iml-info='{
    "url"           :"{$ajaxUrl}",
    "deliveryId"    :"{$delivery.id}", 
    "serviceId"     :{$service_ids},
    "selectId"      :"#selectRegionCombo_{$delivery.id}", 
    "listId"        :"#sdlist_{$delivery.id}", 
    "regionIdFrom"  :"{$region_id_from}", 
    "regionIdTo"    :"{$region_id_to}", 
    "deliveryCost"  :{$delivery_cost} 
}'>
    <select id="selectRegionCombo_{$delivery.id}"></select>
    <div class="mapWrapper">
        <div id="map_{$delivery.id}" style="height:600px; width:600px; float:left;"><ymaps class="ymaps-2-1-31-map ymaps-2-1-31-i-ua_js_yes ymaps-2-1-31-map-ru" style="width: 600px; height: 600px;"></div>
        <div style="overflow: auto; height:600px; width:250px; padding:0; font-size:12px;">
            <ul id="sdlist_{$delivery.id}" style="padding-left:2px;"></ul>
        </div>
    </div>
</div>