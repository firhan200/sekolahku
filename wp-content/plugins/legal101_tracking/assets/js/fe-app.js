$(document).ready(function(){
    var hostUrl = $("#host_url").val();
    var pluginName = $("body").data('plugin-name');

    $("#filter_faktur").change(function(){
        $("#form_filter_faktur").submit();
    })

    $(".legal101_logout_btn").click(function(){
        var confirm = window.confirm("Apakah anda yakin ingin keluar?");
        if(confirm){
            $.ajax({
                url: hostUrl + '/wp-admin/admin-ajax.php?action=legal101_logout_user&noheader=true',
                type: 'POST',
                contentType: "application/json; charset=utf-8",
                data: {},
                success: function(data){
                    window.location.href = hostUrl + "/"+pluginName+"_login";
                }
            });
        }
    })

    $("#legal101_login_form").submit(function(e){
        e.preventDefault();
        
        var formDataArr = $(this).serializeArray();
        var formData = formDataArr.reduce((obj, item) => Object.assign(obj, { [item.name]: item.value }), {});

        var email_address = $(this).find("input[name='email_address']");
        var password = $(this).find("input[name='password']");
        var submit_btn = $(this).find("button[type='submit']");

        $.ajax({
            url: hostUrl + '/wp-admin/admin-ajax.php?action=legal101_login_user&noheader=true',
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            data:JSON.stringify(formData),
            beforeSend: function(){
                $("#login_errors").hide();
                $("#login_errors_message").html('');

                email_address.attr('disabled', true);
                password.attr('disabled', true);
                submit_btn.attr('disabled', true);
            },
            success:function(res){
                email_address.attr('disabled', false);
                password.attr('disabled', false);
                submit_btn.attr('disabled', false);

                if(res.is_success){
                    window.location.href = hostUrl + "/"+pluginName+"_home";
                }else{
                    $("#login_errors").slideDown();
                    for(var i = 0; i < res.errors.length; i++){
                        var errMessage = res.errors[i];
                        $("#login_errors_message").append('<li>'+errMessage+'</li>');
                    }
                }
            },
            error: function(err){
                email_address.attr('disabled', false);
                password.attr('disabled', false);
                submit_btn.attr('disabled', false);
                var message = err.statusText+": "+err.responseText;
                alert(message);
            }
        })
    });

    $("#legal101_admin_login_form").submit(function(e){
        e.preventDefault();
        
        var formDataArr = $(this).serializeArray();
        var formData = formDataArr.reduce((obj, item) => Object.assign(obj, { [item.name]: item.value }), {});

        var email_address = $(this).find("input[name='email_address']");
        var password = $(this).find("input[name='password']");
        var submit_btn = $(this).find("button[type='submit']");

        $.ajax({
            url: hostUrl + '/wp-admin/admin-ajax.php?action=legal101_login_admin&noheader=true',
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            data:JSON.stringify(formData),
            beforeSend: function(){
                $("#login_errors").hide();
                $("#login_errors_message").html('');

                email_address.attr('disabled', true);
                password.attr('disabled', true);
                submit_btn.attr('disabled', true);
            },
            success:function(res){
                email_address.attr('disabled', false);
                password.attr('disabled', false);
                submit_btn.attr('disabled', false);

                if(res.is_success){
                    window.location.href = hostUrl + "/admin_"+pluginName+"_home";
                }else{
                    $("#login_errors").slideDown();
                    for(var i = 0; i < res.errors.length; i++){
                        var errMessage = res.errors[i];
                        $("#login_errors_message").append('<li>'+errMessage+'</li>');
                    }
                }
            },
            error: function(err){
                email_address.attr('disabled', false);
                password.attr('disabled', false);
                submit_btn.attr('disabled', false);
                var message = err.statusText+": "+err.responseText;
                alert(message);
            }
        })
    });

    $(".legal101_admin_logout_btn").click(function(){
        var confirm = window.confirm("Apakah anda yakin ingin keluar?");
        if(confirm){
            $.ajax({
                url: hostUrl + '/wp-admin/admin-ajax.php?action=legal101_logout_admin&noheader=true',
                type: 'POST',
                contentType: "application/json; charset=utf-8",
                data: {},
                success: function(data){
                    window.location.href = hostUrl + "/admin_"+pluginName+"_login";
                }
            });
        }
    })

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
})