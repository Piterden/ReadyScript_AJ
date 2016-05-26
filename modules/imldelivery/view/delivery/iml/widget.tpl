{addjs file='http://api-maps.yandex.ru/2.1/?lang=ru_RU' basepath='root'}
{addjs file="$mod_js\\iml.js" basepath='root'}
{if $order->getExtraKeyPair('request_code') && $order->getExtraKeyPair('region_id_to')}
    {assign var="sd_is_selected" value=1}
{/if}

<div class='imlContainer_{$delivery.id}'>
    <button id='showMap_{$delivery.id}' class='btn btn-primary show-map spacing'>Выбрать пункт самовывоза</button>
</div>
<input id="region_id_to" type="hidden" name="extra[region_id_to]" value="{$region_id_to}">
<input id="request_code" type="hidden" name="extra[request_code]" value="{$request_code}">
<input id="sd_is_selected" type="hidden" value="{$sd_is_selected|default:'0'}">

<script type='text/javascript'>

jQuery(document).ready(function($) {ldelim}
    $('#showMap_' + '{$delivery.id}').Iml({ldelim}
        url: '{$router->getUrl("shop-front-checkout", ["Act" => "userAct"])}',
        delivery_id: '{$delivery.id}',

        cls: {ldelim}
            overlay: 'loading'
        {rdelim},

        request: {ldelim}
            RegionFrom: '{$region_id_from}',
            RegionTo: '{$region_id_to}',
            RequestCode: '{$request_code}'
        {rdelim},

        /**
         * JSON Data
         */
        currency: JSON.parse('{$currency}'),
        delivery_cost_json: JSON.parse('{$delivery_cost_json}'),
        service_id: JSON.parse('{$service_ids}')
    {rdelim});
{rdelim});
</script>
