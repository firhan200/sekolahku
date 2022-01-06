$(document).ready(function(){
    //$('.multiple_select2').select2();

    $(".btn-password-show").click(function(){
        var input = $(this).parents('.wp-pwd').find('input');
        var text = $(this).parents('.wp-pwd').find('.text');
        var icon = $(this).parents('.wp-pwd').find('.dashicons');
        var isVisible = input.attr('type') == 'text';
        var newType = '';
        var newText = '';
        var newIcon = '';

        icon.removeClass();

        //check type
        if(isVisible){
            newType = 'password';
            newText = 'Tampilkan';

            newIcon = 'dashicons dashicons-visibility';
        }else{
            newType = 'text';
            newText = 'Sembunyikan';

            newIcon = 'dashicons dashicons-hidden';
        }

        //change type
        input.attr('type', newType);
        text.text(newText);
        icon.addClass(newIcon);
    });

    /* UJIAN */
    $('.custom_daterange').daterangepicker({
        timePicker: true,
        timePicker24Hour: true,
        locale: {
            format: 'DD/MM/YYYY, HH:mm'
        }
    });

    $('.custom_date').daterangepicker({
        singleDatePicker: true,
        locale: {
            format: 'YYYY-MM-DD'
        }
    });
    /* UJIAN */
});