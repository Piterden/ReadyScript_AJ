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

    $('input[name="use_addr"]').click(function() {
        $('.newAddress').toggle( this.value == '0' );
    });
    
    $('.userType input').click(function() {
        $(this).closest('.checkoutForm').removeClass('person company noregister user').addClass( $(this).val() );
        $('#doAuth').attr('disabled', $(this).val()!='user');
    });

    $('input[name="reg_autologin"]').change(function() {
        $('#manual-login').toggle(!this.checked);
    });       

    /**
    * Отработка удаления адреса доставки на странице оформления заказа
    */
    $(".newAccount .address .deleteAddress").on('click', function(){
        var parent = $(this).closest('span');
        parent.css('opacity', '0.5');
        $.get($(this).attr('href') ? $(this).attr('href') : $(this).data('href'), function( response ) {
            parent.css('opacity', '1');
            if (response.success){
               parent.remove(); 
               $(".newAccount .address input[name='use_addr']:eq(0)").click();
            }
        }, "json");
        return false;
    });      
});   