$(function() {
    $('select[name="addr_country_id"]').change(function() {
        var regions = $('select[name="addr_region_id"]').attr('disabled','disabled');
        
        $.getJSON($(this).data('regionUrl'), {
            parent: $(this).val()
        }, 
        function(response) {
            if (response.list.length>0) {
                regions.html('');
                for(var i=0; i< response.list.length; i++) {
                    var item = $('<option value="'+response.list[i].key+'">'+response.list[i].value+'</option>');
                    regions.append(item);
                }
                regions.removeAttr('disabled');
                $('#region-input').val('').hide();
                $('#region-select').show();
            } else {
                $('#region-input').show();
                $('#region-select').hide();
            }
            
            
        });
    });
    
    if ($('#address-list').length>0 && $('input[name="use_addr"]:checked').val()!='0') {
        $('.new-address').hide();
    }

    $('input[name="use_addr"]').click(function() {
        if (this.value == '0') $('.new-address').show();
            else $('.new-address').hide();
    });
    
    $('#changeuser').click(function() {
        $('#order-form').append('<input type="hidden" name="logout" value="1">');
        $('#order-form').submit();
        return false;
    });
    
    $('#order_login').click(function() {
        $('#order-form').append('<input type="hidden" name="ologin" value="1">');
        $('#order-form').submit();
        return false;
    }); 

    $('input[name="reg_autologin"]').change(function() {
        $('#manual-login').toggle(!this.checked);
    });       
    
    
    /**
    * Отработка удаления адреса доставки на странице оформления заказа
    */
    $(".existsAddress .deleteAddress").on('click', function(){
        var parent = $(this).closest('tr');
        parent.css('opacity', '0.5');
        $.get($(this).attr('href') ? $(this).attr('href') : $(this).data('href'), function( response ) {
            parent.css('opacity', '1');
            if (response.success){
               parent.remove(); 
               $("#address-list input[name='use_addr']:eq(0)").click();
            }
        }, "json");
        return false;
    });

});   