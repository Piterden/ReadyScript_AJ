$.contentReady(function() {
    $('.rs-table > tbody > tr:not(.no-over)').hover(function() {
        $(this).addClass('over');
    }, function() {
        $(this).removeClass('over');
        $('.over', this).removeClass('over');
    });
    
    $('.cell-image[data-preview-url]').each(function() {
        $(this).hover(function() {
            var previewUrl = $(this).data('previewUrl');
            if (previewUrl != '') {
                $('#imagePreviewWin').remove();
                var win = $('<div id="imagePreviewWin" />')
                    .append('<i />')
                    .append($('<img />').attr('src', previewUrl ))
                    .css({
                        top: $(this).offset().top,
                        left: $(this).offset().left + $(this).width() + 20
                    }).appendTo('body');
            }
        }, function() {
            $('#imagePreviewWin').remove();
        });
    });
});
