$(function() {
    
    /**
    * Показывает/скрывает форму авторизации/восстановления пароля
    */
    function recover(e)
    {
        $('.auth .message-box div').fadeOut();
        
        if ($('form.login:visible').length) {
            $('.login .message-box').hide();
            $('form.login').fadeOut(function() {
                $('form.saveform').fadeIn();
                $(e.target).html('Назад к авторизации');
            });
        } else {
            $('.login .message-box div').hide();
            $('form.saveform').fadeOut(function() {
                $('form.login').fadeIn();
                $(e.target).html('Забыли пароль?');
            })
        }
    }    
    
    //Инициализируем форму авторизации
    $('form.login').bind('submit', {authurl: '/auth/?ajax=1'}, function(e) 
     {   
         var form = this;
        $('.login .message-box').hide();          
         $.post(e.data.authurl, $(this).serialize(), function(response) {
             if (response.status == 'ok') {
                 location.reload();
             } else {
                 $('.login .message-box').html(response.result).fadeIn();
             }
         }, 'json');
         return false;
     });
     
     //Инициализируем форму восстановления пароля
     $('form.saveform').bind('submit', {recoverurl: '/ajax.php?mod=musers_authblock&ado=recover'}, function(e) 
     {
         $.post(e.data.recoverurl, $(this).serialize(), function(response) {
             var status = (response.status == 'ok') ? 'success' : 'fail';
             var antiStatus = (status == 'fail') ? 'success' : 'fail';
            
            $('.auth .message-box .'+antiStatus).fadeOut(function() {
                $('.auth .message-box .'+status).html(response.result).fadeIn();
            });             
         }, 'json');
         return false;         
     });
     
     //Инициализируем кнопку "зарегистрироваться"
     $('.register-user').click(function() {
         return openDialog('/ajax.php?mod=MUsers_Register&ajaxAct=dialogWrap', null, null, function() {
             $('#regform').activeTabs({
                 onTabChange: function() {
                     $.colorbox.resize();
                 }
             });
         });
     });
     
     //Инициализируем кнопку "восстановить пароль"
     $('.auth .recover').click(recover);
     
     //Инициализируем подсветку блока авторизации
     $('.goto-auth').click(function() {
         var n = 13;
         var color;
         var pointer = setInterval(function() {
             color = (n % 2 == 0) ? '' : '#e86664';
             $('.login .wi').css('border-color', color);
             if (!n) clearInterval(pointer); else n--;
         }, 100);
     });

});