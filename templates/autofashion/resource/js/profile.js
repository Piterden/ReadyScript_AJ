$(function() {

    $('#changePass').change(function() {
        $('.changePass').toggleClass('hidden', !this.checked);
    });

    $('.profile .userType input').click(function() {
        $('#fieldsBlock').toggleClass('thiscompany', $(this).val() == 1);
    });


});
