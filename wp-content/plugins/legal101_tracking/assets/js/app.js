$(document).ready(function(){
    $('.multiple_select2').select2();

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

    var option_index = 0;
    var pilihan_ganda = $("#PILIHAN_GANDA").val();
    var pilihan_ganda_kompleks = $("#PILIHAN_GANDA_KOMPLEKS").val();

    $("#add_answer_option").click(function(){
        var optionContainer = $("#answer_options");
        
        //check type
        var question_type = $('#question_type').val();
        if(question_type == pilihan_ganda){
            optionContainer.append(generateOptionTemplate(option_index));
        }else if(question_type == pilihan_ganda_kompleks){
            optionContainer.append(generateOptionTemplateKompleks(option_index));
        }


        option_index++;
    });

    $(document).on('click', '.btn_remove_option', function(){
        var confirm = window.confirm('Apakah anda yakin ingin menghapus pilihan jawaban ini?');
        if(confirm){
            $(this).parents('.question_option').remove();
        }
    });

    $("#question_type").change(function(){
        var question_type = $(this).val();

        $('#answer_options').html('');
    });

    function generateOptionTemplate(index){
        var optionTemplate = '<div class="form-group question_option">'+
                                '<input type="radio" name="answer_options_score" value="'+index+'" class="form-control">'+
                                '<input type="text" name="answer_options['+index+']" class="regular-text" placeholder="Jawaban">'+
                                '&nbsp;&nbsp;<a href="#!" class="btn btn-danger btn_remove_option">Hapus</a>'+
                            '</div>';
        return optionTemplate;
    }

    function generateOptionTemplateKompleks(index){
        var optionTemplate = '<div class="form-group question_option">'+
                                '<input type="checkbox" name="answer_options_score['+index+']" class="form-control">'+
                                '<input type="text" name="answer_options['+index+']" class="regular-text" placeholder="Jawaban">'+
                                '&nbsp;&nbsp;<a href="#!" class="btn btn-danger btn_remove_option">Hapus</a>'+
                            '</div>';
        return optionTemplate;
    }

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

    if($(".duration").length){
        $(".duration").each(function(){
            var from = moment($(this).data('from'));
            var to = moment($(this).data('to'));

            var seconds = to.diff(from, 'seconds');

            if(seconds < 60){
                $(this).text(to.diff(from, 'seconds')+" detik");
            }else if(seconds >= 60 && seconds < 3600){
                var leftTime = seconds % 60;
                $(this).text(to.diff(from, 'minutes')+" menit" + (leftTime > 0 ? " "+leftTime+" detik" : ""));
            }else{
                var leftTime = seconds % 3600;
                $(this).text(to.diff(from, 'hours')+" jam" + (leftTime > 0 ? " "+leftTime+" detik" : ""));
            }
        })
    }
});