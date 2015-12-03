{addjs file="http://api-maps.yandex.ru/2.1/?lang=ru_RU" basepath="root"}
{*addjs file="{$mod_js}iml_widget.js" basepath="root"*}
{assign var=ajaxUrl value=$router->getUrl("imldelivery-front-ajaxctrl")}

<div class="imlContainer_{$delivery.id}" data-iml-info='{
    "url"               :"{$ajaxUrl}",
    "deliveryId"        :"{$delivery.id}", 
    "serviceId"         :{$service_ids},
    "selectId"          :"#selectRegionCombo_{$delivery.id}", 
    "listId"            :"#sdlist_{$delivery.id}", 
    "regionIdFrom"      :"{$region_id_from}", 
    "regionIdTo"        :"{$region_id_to}", 
    "deliveryCostJson"  :{$delivery_cost_json}
}'>
    <button id="showMap" class="btn btn-default"><a href="#">Показать карту</a></button>
    
</div>