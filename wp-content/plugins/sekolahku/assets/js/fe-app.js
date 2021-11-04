$(document).ready(function(){
    var hostUrl = $("#host_url").val();

    $(".logout_btn").click(function(){
        var confirm = window.confirm("Apakah anda yakin ingin keluar?");
        if(confirm){
            $.ajax({
                url: hostUrl + '/wp-admin/admin-ajax.php?action=logout_user&noheader=true',
                type: 'POST',
                contentType: "application/json; charset=utf-8",
                data: {},
                success: function(data){
                    window.location.href = hostUrl + "/sekolahku-masuk";
                }
            });
        }
    })

    $("#sekolahku_login_form").submit(function(e){
        e.preventDefault();
        
        var formDataArr = $(this).serializeArray();
        var formData = formDataArr.reduce((obj, item) => Object.assign(obj, { [item.name]: item.value }), {});

        var email_address = $(this).find("input[name='email_address']");
        var password = $(this).find("input[name='password']");
        var submit_btn = $(this).find("button[type='submit']");

        $.ajax({
            url: hostUrl + '/wp-admin/admin-ajax.php?action=login_user&noheader=true',
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
            url: hostUrl + '/wp-admin/admin-ajax.php?action=register_user&noheader=true',
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

    setInterval(function(){
        $(".to_ago").each(function(){
            //get raw
            var from = $(this).data('from');
            var to = $(this).data('to');
    
            //parse to moment obj
            var fromMoment = moment(from, 'YYYY-MM-DD hh:mm:ss');
            var toMoment = moment(to, 'YYYY-MM-DD hh:mm:ss');
    
            //get ago
            $(this).html(toMoment.locale('id').fromNow());
        });
    }, 1000);

    function padDigits(number, digits) {
        return Array(Math.max(digits - String(number).length + 1, 0)).join(0) + number;
    }

    if($(".quiz_timer_state").length){
        //create function timer countdown from start date and end date
        var startDate = $(".quiz_timer_state").data('start-date');
        var endDate = $(".quiz_timer_state").data('end-date');
        
        var startDateTime = new Date(startDate);

        setInterval(function(){
            renderTimer(startDateTime, endDate, $(".quiz_timer_state"));

            startDateTime = new Date(startDateTime.getTime() + 1000);
        }, 1000);
        
        function renderTimer(startDate, endDate, $elem){
            // Get today's date and time
            var now = startDate;
                
            // Find the distance between now and the count down date
            var distance = new Date(endDate) - now;
                
            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
            // Output the result in an element with id="demo"
            var label = "";
            if(days > 0){
                label = days + " hari ";
            }
            if(hours > 0){
                label += '<span class="digits">'+padDigits(hours, 2) + "</span>:";
            }
            
            label += '<span class="digits">'+padDigits(minutes, 2) + "</span>:";

            label += '<span class="digits">'+padDigits(seconds, 2) + "</span>";

            $elem.html(label);
                
            // If the count down is over, write some text 
            if (distance < 0) {
                if($("#on_quiz").length){
                    save_ujian(true);
                }
            }
        }
    }

    function save_ujian(is_finish = false){
        var paket_id = $("input[name='paket_id']").val();
        var ujian_pengguna_id = $("input[name='ujian_pengguna_id']").val();
        var answers = [];
        var answersElement = $(".answer");
        for(var i =0; i < answersElement.length; i++){
            if(answersElement[i].checked){
                var soalId = $(answersElement[i]).data('soal-id');
                answers.push({
                    'soal_id' : soalId,
                    'soal_pilihan_id' : answersElement[i].value
                });
            }
        }

        $.ajax({
            url: hostUrl + '/wp-admin/admin-ajax.php?action=submit_quiz&noheader=true',
            type: 'POST',
            data:JSON.stringify({
                'is_finish' : is_finish,
                'paket_id' : paket_id,
                'ujian_pengguna_id' : ujian_pengguna_id,
                'answers' : answers
            }),
            beforeSend: function(){
                if(is_finish){
                    $("#quiz_container").hide();
                    $("#loading_container").show();
                }
            },
            success: function(res){
                if(res.is_success){
                    if(is_finish){
                        window.location.reload();
                    }
                }else{
                    alert(res.errors);
                }
            }
        });
    }

    if($("#on_quiz").length){
        setInterval(function(){
            save_ujian();
        }, 5000);
    }

    $("#quiz_form").submit(function(e){
        e.preventDefault();

        var confirm = window.confirm("Apakah anda yakin akan menyelesaikan ujian?");
        if(confirm){
            save_ujian(true);
        }
    })

    /* Quiz History */
    var quizHistoryPage = 1;

    //get first data
    if($("#quiz_list_container").length){
        getQuiz(quizHistoryPage);
    }

    $("#load_more_btn").click(function(){
        quizHistoryPage++;
        getQuiz(quizHistoryPage);
    })

    function getQuiz(page){
        $.ajax({
            url: hostUrl + '/wp-admin/admin-ajax.php?action=history&noheader=true',
            type: 'POST',
            data:JSON.stringify({
                'page' : page,
            }),
            beforeSend: function(){
                $("#loading").show();
                $("#load_more_btn").hide();
            },
            success: function(res){
                $("#load_more_btn").show();
                $("#loading").hide();

                if(res.is_success){
                    if(res.data.length < 1){
                        $("#load_more_btn").hide();
                    }

                    for(var i = 0; i < res.data.length; i++){
                        var quiz = res.data[i];

                        $("#quiz_list_container").append(generateQuizTemplate(quiz));
                    }
                }else{
                    alert(res.errors);
                }
            }
        });
    }

    function generateQuizTemplate(data){
        var pembahasanUrl = hostUrl + "/sekolahku-quiz?id="+data.id;

        var html = '';

        html += '<div class="box mb-4">';
        html += '<div class="row">';

        html += '<div class="col-12 col-md-6">';
        html += '<div class="fs-6">';
        html += data.paket_name;
        html += '</div>';

        html += '<div class="t-sm">';
        html += data.matapelajaran_name;
        html += ' - '+data.kelas_name;
        html += '</div>';

        html += '<div class="t-sm">';
        html += 'Selesai Pengerjaan: <span class="to_ago" data-from="'+moment.now()+'" data-to="'+data.ujian_end_date+'">-</span>';
        html += '</div>';
        html += '</div>';

        html += '<div class="col-12 col-md-6 text-end">';
        html += '<div>Nilai : <b>'+data.score+'</b></div>';
        html += '<div class="mt-2"><a href="'+pembahasanUrl+'" class="btn btn-default btn-sm">Lihat Pembahasan&nbsp;<i class="fa fa-chevron-right"></i></a></div>';
        html += '</div>';

        html += '</div>';
        html += '</div>';

        return html;
    }
    /* Quiz History */
})