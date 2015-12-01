$(function() {
    $('#auth .to-recover').click(function() {
        $('#auth').fadeOut();
        $('#recover').fadeIn();
        $('.form-box').removeClass('has-error success');
        return false;
    });
    
    $('#recover .back-to-auth').click(function() {
        $('#auth').fadeIn();
        $('#recover').fadeOut();
        $('.form-box').removeClass('has-error success');
        return false;
    });        
    
    
    $('#auth, #recover').submit(function() {
        var $form = $(this);            
        var $wrap = $(this).closest('.form-box');
        var success = false;
        $.ajaxQuery({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serializeArray(),
            success: function(response) {
                if (response.success) {
                    $wrap.removeClass('has-error');
                    if (response.successText) {
                        $wrap.addClass('success');
                        $('.success-message', $wrap).text(response.successText);
                    }
                    
                    if ($form.attr('id') == 'auth') {
                        success = true;
                        location.reload();
                    }
                } else {
                    $wrap.removeClass('success').addClass('has-error');
                    $('.error-message', $wrap).text(response.error);
                }
            },
            complete: function() {
                if (success) {
                    loading.show();
                }
            }
        });
        
        return false;
    });
    
});