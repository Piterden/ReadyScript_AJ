{addjs file='http://api-maps.yandex.ru/2.1/?lang=ru_RU' basepath='root'}
{addjs file="$mod_js\\iml.js" basepath='root'}

<div class='imlContainer_{$delivery.id}'>
    <button id='showMap_{$delivery.id}' class='btn btn-primary show-map spacing'>Выбрать пункт самовывоза</button>
</div>

<script type='text/javascript'>

jQuery(document).ready(function($) {ldelim}
    $('#showMap' + '{$delivery.id}').Iml({ldelim}
        url: '{$router->getUrl('shop-front-checkout', ['Act' => 'userAct'])}',
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
        currency: $.parseJSON({$currency}),
        delivery_cost_json: $.parseJSON({$delivery_cost_json}),
        service_id: $.parseJSON({$service_ids}),

        // id: {ldelim}

        // {rdelim},
        // style: {ldelim}

        // {rdelim}
        // templates: {ldelim}

        // {rdelim}

    {rdelim});
{rdelim});
</script>
