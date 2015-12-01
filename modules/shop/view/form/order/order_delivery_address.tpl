    <tr>
        <td class="caption">Страна:</td>
        <td>
            <select name="address[country_id]" data-url="{adminUrl do=getCountryRegions}">
                {html_options options=$country_list selected=$address.country_id}
            </select>
            <div class="helper">Например: Россия</div>
        </td>
    </tr>
    <tr>
        <td class="caption">Область, Город:</td>
        <td>
            <div class="inline f">
                    <span {if count($region_list) == 0}style="display:none"{/if} id="region-select">
                        <select name="address[region_id]">
                            {foreach from=$region_list item=region}
                            <option value="{$region.id}" {if $region.id==$address.region_id}selected{/if}>{$region.title}</option>
                            {/foreach}
                        </select>
                    </span>
                    
                    <span {if count($region_list) > 0}style="display:none"{/if} id="region-input">
                        <input type="text" value="{$address.region}" name="address[region]">
                    </span>
                    <div class="helper">Область/Край</div>
                </div>
                <div class="inline">
                    <input type="text" value="{$address.city}" name="address[city]">
                    <div class="helper">Город</div>
                </div>
        </td>
    </tr>                    
    <tr>
        <td class="caption">Индекс, Адрес:</td>
        <td>
            <div class="inline f">
                <input size="10" maxlength="20" value="{$address.zipcode}" name="address[zipcode]">
                <div class="helper">Индекс</div>
            </div>
            <div class="inline">
                <input size="44" maxlength="255" value="{$address.address}" name="address[address]">
                <div class="helper">Адрес. Например: ул. Красная, 100, офис 71</div>
            </div>
        </td>
    </tr>
{literal}
<script>
$(function() {
    $('select[name="address[country_id]"]').change(function() {
        var parent = $(this).val();
        var regions = $('select[name="address[region_id]"]').attr('disabled','disabled');
        
        $.ajaxQuery({
            url: $(this).data('url'),
            data: {
                parent: parent
            },
            success: function(response) {
                if (response.list.length>0) {
                    regions.html('');
                    for(i=0; i< response.list.length; i++) {
                        var item = $('<option value="'+response.list[i].key+'">'+response.list[i].value+'</option>');
                        regions.append(item);
                    }
                    regions.removeAttr('disabled');
                    $('#region-input').hide();
                    $('#region-select').show();
                } else {
                    $('#region-input [name="address[region]"]').val('');
                    $('#region-input').show();
                    $('#region-select').hide();
                }                
            }
        });
        
    });    
});
</script>
{/literal}