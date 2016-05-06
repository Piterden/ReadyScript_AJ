/**
* Возващает true и выводит диалог с текстом ошибки, 
* если возвращаемый JSON извещает о произошедшей ошибки. Иначе - false
*/
function noWriteRights(response)
{
    if (response.status != 'ok') {
        alert(response.error);
        return true;
    }    
    return false;
}


//Устанавливаем высоту рабочей области
var resizeClientHeight = function() {
    var screenClientHeight = $(window).height() - $('#header').height();
    if ($('#content-layout').height() < screenClientHeight) {
        $('#content-layout').css('min-height', screenClientHeight+'px');
        
        var columnHeight = screenClientHeight - $('#clienthead').height();
        $('#content-layout .fullheight').css('min-height', columnHeight+'px');
    }
};

$(window).load(function() {
    $(window).resize(resizeClientHeight);    
    $.contentReady(function() {
        resizeClientHeight();        
        if ($('#content-layout .fullheight').height() < $('#content-layout').height()) {
            $('#content-layout .fullheight').css('min-height', $('#content-layout').height()+'px');
        }        
    });
});

$.contentReady(function() {
    
   

    
    $('.c-head .help', this).off('.help').on('click.help', function() {
        $('.c-help').slideToggle('fast');
        $(this).toggleClass('act');
    });
    
    $('.columns .left-column', this).columnResizer();
    $('input[data-deftext]', this).deftext();    
    
});