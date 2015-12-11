$(function() {
    $('select[name="addr_country_id"]').change(function() {
        var regions = $('select[name="addr_region_id"]').attr('disabled','disabled');
        
        $.getJSON($(this).data('regionUrl'), {
            parent: $(this).val()
        }, 
        function(response) {
            if (response.list.length>0) {
                regions.html('');
                for(i=0; i< response.list.length; i++) {
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

    $('#sd_region').on('change', function() {
        var name = $(this).children(':selected').text();
        $('#sd_region_name').val(name);
    });

    $('input[name="use_addr"]').click(function() {
        if (this.value == '0') {
            $('.newAddress').removeClass('hide');
            $('.sdAddress').addClass('hide');
        } else if (this.value == '-1') {
            $('.sdAddress').removeClass('hide');
            $('.newAddress').addClass('hide');
        } else {
            $('.newAddress').addClass('hide');
            $('.sdAddress').addClass('hide');
        }
    });
    
    $('.userType input').click(function() {
        $(this).closest('.checkoutBox').removeClass('person company user').addClass( $(this).val() );
        $('#doAuth').attr('disabled', $(this).val()!='user');
    });

    $('input[name="reg_autologin"]').change(function() {
        $('#manual-login').toggle(!this.checked);
    });

    $('.toggleView').on('click', function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        if (id == 'hasAccount') {
            $('[name="user_type"]').removeAttr('checked').filter('#type-account').click();
            $('#doAuth').attr('disabled', false);
        } else if (id == 'contactData') {
            $('[name="user_type"]').removeAttr('checked').filter('#type-user').click();
            $('#doAuth').attr('disabled', true);
        }
        $(this).closest('.row').addClass('hide');
        $('.'+id).removeClass('hide');
    });      
    
    /**
    * Отработка удаления адреса доставки на странице оформления заказа
    */
    $(".lastAddress .deleteAddress").on('click', function(){
        var parent = $(this).closest('.tableRow');
        parent.css('opacity', '0.5');
        $.get($(this).attr('href') ? $(this).attr('href') : $(this).data('href'), function( response ) {
            parent.css('opacity', '1');
            if (response.success){
               parent.remove(); 
               $(".lastAddress input[name='use_addr']:eq(0)").click();
            }
        }, "json");
        return false;
    });     
    
});   