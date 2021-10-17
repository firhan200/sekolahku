$(document).ready(function(){
    var hostUrl = $("#host_url").val();

    $("#sekolahku_login_form").submit(function(e){
        e.preventDefault();
        
        var formDataArr = $(this).serializeArray();
        var formData = formDataArr.reduce((obj, item) => Object.assign(obj, { [item.name]: item.value }), {});

        var email_address = $(this).find("input[name='email_address']");
        var password = $(this).find("input[name='password']");
        var submit_btn = $(this).find("button[type='submit']");

        $.ajax({
            url:'/sekolahku/wp-admin/admin-ajax.php?action=login_user&noheader=true',
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
                    window.location.href = hostUrl + "/sekolahku-dashboard";
                }else{
                    $("#login_errors").slideDown();
                    for(var i = 0; i < res.errors.length; i++){
                        var errMessage = res.errors[i];
                        $("#login_errors_message").append('<li>'+errMessage+'</li>');
                    }
                }
            }
        })
    });

    $("#sekolahku_register_form").submit(function(e){
        e.preventDefault();
        
        var formDataArr = $(this).serializeArray();
        var formData = formDataArr.reduce((obj, item) => Object.assign(obj, { [item.name]: item.value }), {});

        var nameInput = $(this).find("input[name='name']");
        var email_address = $(this).find("input[name='email_address']");
        var password = $(this).find("input[name='password']");
        var repeat_password = $(this).find("input[name='repeat_password']");
        var submit_btn = $(this).find("button[type='submit']");

        $.ajax({
            url:'/sekolahku/wp-admin/admin-ajax.php?action=register_user&noheader=true',
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            data:JSON.stringify(formData),
            beforeSend: function(){
                $("#register_errors").hide();
                $("#register_errors_message").html('');

                nameInput.attr('disabled', true);
                email_address.attr('disabled', true);
                password.attr('disabled', true);
                repeat_password.attr('disabled', true);
                submit_btn.attr('disabled', true);
            },
            success:function(res){
                nameInput.attr('disabled', false);
                email_address.attr('disabled', false);
                password.attr('disabled', false);
                repeat_password.attr('disabled', false);
                submit_btn.attr('disabled', false);

                if(res.is_success){
                    window.location.href = hostUrl + "/sekolahku-dashboard";
                }else{
                    $("#register_errors").slideDown();
                    for(var i = 0; i < res.errors.length; i++){
                        var errMessage = res.errors[i];
                        $("#register_errors_message").append('<li>'+errMessage+'</li>');
                    }
                }
            }
        })
    });

})