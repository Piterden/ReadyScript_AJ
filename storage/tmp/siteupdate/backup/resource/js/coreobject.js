/* скрипт обеспечивает работу форм редактирования core-object'ов */
$.contentReady(function() 
{
    //Для группового редактирования
    checkOn = function() {
        var tr = $(this).parents('tr:first');
        if (this.checked) tr.addClass('editthis'); else tr.removeClass('editthis');    
    }
    
    $('.multiedit .doedit', this).each(checkOn);
    $('.multiedit .doedit', this).click(checkOn);
    
    //Инициализируем поля "дата-время"
    $('input[datetime]', this).each(function() {
        $(this).datetime();
    });    
    
    //Инициализируем поля "дата-время"
    $('input[date]', this).each(function() {
        $(this).dateselector();
    });    
});