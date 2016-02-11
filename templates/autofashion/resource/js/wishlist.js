jQuery(document).ready(function($) {
    $('.wishWrap form').on('submit', function() {
        //event.preventDefault();
        var data = $(this).serialize();
        $.ajax({
            url: window.location.href,
            type: 'POST',
            data: data,
        })
        .done(function(data) {
            console.log("success", data);
        })
        .fail(function(data) {
            console.log("error", data);
        });
        return false;
    });
});
