<div class="formbox" >
    <form method="POST" action="{urlmake}" enctype="multipart/form-data" class="crud-form">
        <input type="submit" value="" style="display:none">
        <div class="notabs">
            <table class="otable">
                <tr>
                    <td class="otitle" style="border-bottom: 1px solid #eee;">Пользователь</td>
                    <td style="border-bottom: 1px solid #eee;">
                        {include file="form/order/user_select_or_reg.tpl"}
                    </td>
                </tr>
                <tr>
                    <td class="otitle">Адреса</td>
                    <td>
                        <select name="use_addr" id="change_addr" data-url="{adminUrl do=getUserAddresses}">
                            <option value="0">Новый адрес для заказа</option>        
                        </select>
                    </td>
                </tr>
                <tr class="new_addr">
                    <td class="otitle">Страна</td>
                    <td>
                        <select name="address[country_id]" data-url="{adminUrl do=getCountryRegions}">
                            <option>- не выбрано - </option>
                            {html_options options=$country_list selected=$address.country_id}
                        </select>
                    </td>
                </tr>
                
                <tr class="new_addr">
                    <td class="otitle">Область</td>
                    <td>
                        <span style="display:none" id="region-select">
                            <select name="address[region_id]">
                            </select>
                        </span>
                        
                        <span id="region-input">
                            <input type="text"  name="address[region]">
                        </span>
                    </td>
                </tr>                    
                <tr class="new_addr">
                    <td class="otitle">Город</td>
                    <td><input type="text" value="{$address.city}" name="address[city]"></td>
                </tr>                    
                <tr class="new_addr">
                    <td class="otitle">Индекс</td>
                    <td><input size="10" maxlength="20" value="{$address.zipcode}" name="address[zipcode]"></td>
                </tr>                    
                <tr class="new_addr">
                    <td class="otitle">Адрес</td>
                    <td><input size="44" maxlength="255" value="{$address.address}" name="address[address]"></td>
                </tr>
                <tr>
                    <td class="otitle">{$elem.__delivery->getTitle()}</td>
                    <td>{include file=$elem.__delivery->getRenderTemplate() field=$elem.__delivery}</td>
                </tr>
                <tr>
                    <td class="otitle">{$elem.__payment->getTitle()}</td>
                    <td>{include file=$elem.__payment->getRenderTemplate() field=$elem.__payment}</td>
                </tr>
            </table>
        </div>
    </form>
</div>

<script>
$(function() {

    // На смену пользователя
    $('body').on('change', 'input[name="user_id"]', function(){
        // Загружаем список адресов
        var user_id = $(this).val();
        var addreses = $('select[name="use_addr"]');

        $.ajaxQuery({
            url: addreses.data('url'),
            data: {
                user_id: user_id
            },
            success: function(response) {
                addreses.html('');
                // Если есть адреса
                if (response.list.length>0) {
                    for(var i in response.list) {
                        var item = $('<option value="'+response.list[i].key+'">'+response.list[i].value+'</option>');
                        addreses.append(item);
                    }
                }                
                addreses.append('<option value="0">Новый адрес для заказа</option>');
                addreses.change();
            }
        });
    
    });

    // На удаление пользователя
    $('body').on('remove-user', 'input', function(){
        var addreses = $('select[name="use_addr"]');
        addreses.html('<option value="0">Новый адрес для заказа</option>');
        addreses.change();
    });

    
    // На смену адреса
    $('select[name="use_addr"]').change(function(){
        if($(this).val() == 0){
            $('.new_addr').show();
        }
        else{
            $('.new_addr').hide();
        }
    });
    

    // На смену страны
    $('select[name="address[country_id]"]').change(function() {
        // Загружаем список регионов
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
