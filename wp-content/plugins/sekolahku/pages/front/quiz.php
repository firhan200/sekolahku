<?php
global $wpdb;
$my_plugin = plugin_dir_url('').'sekolahku';

$quiz_id = $_GET['id'];
$is_valid = false;
$menu_quiz = true;
$hide_menu = true;
$is_resume = false;
$is_finish = false;
$current_time = $wpdb->get_var("SELECT NOW()");

if ( ! session_id() ) {
    session_start();
}


//check if quiz id valid
if($quiz_id != null){
    $quiz = $wpdb->get_row('SELECT u.*, p.id as paket_id ,p.name AS paket_name, k.name AS kelas_name, m.name AS matapelajaran_name FROM '.$wpdb->prefix.'sekolahku_ujian AS u LEFT JOIN '.$wpdb->prefix.'sekolahku_paket AS p ON u.paket_id=p.id LEFT JOIN '.$wpdb->prefix.'sekolahku_kelas AS k ON u.kelas_id=k.id LEFT JOIN '.$wpdb->prefix.'sekolahku_matapelajaran AS m ON p.matapelajaran_id=m.id WHERE u.id = '.$quiz_id);
    if($quiz != null){
        $is_valid = true;
    }
}

if($is_valid){
    //check ujian pengguna
    $ujian_pengguna_data = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'sekolahku_ujian_pengguna AS up WHERE ujian_id='.$quiz->id.' AND pengguna_id='.$_SESSION[SESSION_ID]);
    if($ujian_pengguna_data == null){
        //insert new
        $is_resume = false;
        $is_finish = false;
    }else{
        if($ujian_pengguna_data->status == QUIZ_ONGOING){
            //resume
            $is_resume = true;
        }
        else if($ujian_pengguna_data->status == QUIZ_FINISHED){
            //resume
            $is_finish = true;
        }
    }

    //not resume and not finish yet
    if(!$is_resume && !$is_finish){
        //create new
        $ujian_pengguna_data = array(
            'ujian_id' => $quiz->id,
            'pengguna_id' => $_SESSION[SESSION_ID],
            'status' => QUIZ_ONGOING,
            'start_date' => $current_time
        );

        //insert into wp_sekolahku_ujian_pengguna
        $wpdb->insert($wpdb->prefix.'sekolahku_ujian_pengguna', $ujian_pengguna_data);

        //get last insert id
        $ujian_pengguna_data['id'] = $wpdb->insert_id;

        $ujian_pengguna_data = (object) $ujian_pengguna_data;
    }
}

$end_on = date("Y-m-d H:i:s", strtotime($ujian_pengguna_data->start_date) + $quiz->duration_seconds);

//check if timeout
if($is_valid && !$is_finish){
    if($current_time > $end_on){
        //timeout, finish quiz now
        $ujian_pengguna_data->status = QUIZ_FINISHED;
        
        //update wp_sekolahku_ujian_pengguna
        $wpdb->update($wpdb->prefix.'sekolahku_ujian_pengguna', array('status' => QUIZ_FINISHED), array('id' => $ujian_pengguna_data->id));
    }
}

//get questions and answer
if($is_valid && !$is_finish){
    //get questions
    $questions = $wpdb->get_results('SELECT s.* FROM '.$wpdb->prefix.'sekolahku_paket_soal AS ps LEFT JOIN '.$wpdb->prefix.'sekolahku_soal AS s ON ps.soal_id=s.id WHERE ps.paket_id='.$quiz->paket_id);

    $question_ids = "";
    foreach($questions as $question){
        $question_ids .= $question->id.",";
    }

    if($question_ids != ""){
        //remove last comma
        $question_ids = substr($question_ids, 0, -1);
    }

    //get answers
    $answers = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'sekolahku_soal_pilihan WHERE soal_id IN ('.$question_ids.')');

    /* get old answer */
    $old_answers = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'sekolahku_ujian_pengguna_jawaban WHERE ujian_pengguna_id='.$ujian_pengguna_data->id);
}
?>

<?php 
if($is_valid){ 
    if($is_finish){
        ?>
        <div class="container mb-5">
            <div class="row mb-2">
                <div class="col-12">
                    <a href="<?php echo get_site_url().'/sekolahku-dashboard'; ?>" class="out_quiz link-dark ms-2"><h4><i class="fa fa-arrow-left"></i></h4></a>
                </div>
            </div>
            <h4 class="fw-light mb-4">
                Ujian Telah Selesai
                <a href="<?php echo get_site_url(); ?>">Lihat Hasil Ujian</a>
            </h4>
        </div>
        <?php
    }else{
?>
<div class="fixed-top quiz_header">
    <div class="container">
        <div class="row">
            <div class="col-2 col-sm-2 col-md-1 mt-2">
                <a href="<?php echo get_site_url().'/sekolahku-dashboard'; ?>" onclick="return confirm('Anda yakin akan keluar?')" class="out_quiz link-dark ms-2"><h4><i class="fa fa-arrow-left"></i></h4></a>
            </div>
            <div class="col-10 col-sm-10 col-md-6 mt-3 text-start">
                <h5 class="fw-light">
                    Ujian <?php echo $quiz->paket_name; ?>
                </h5>
                <div>
                    <?php echo '<span class="badge bg-default">'.$quiz->matapelajaran_name.'</span> - <span class="fw-light">'.$quiz->kelas_name.'</span>'; ?>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-5 text-center text-sm-center text-md-end quiz_timer_container">
                <b>Waktu Tersisa:</b> <span class="quiz_timer mt-2" data-end-date="<?php echo $end_on; ?>" data-start-date="<?php echo $current_time; ?>"></span>
            </div>
        </div>
    </div>
</div>
<div class="container mb-5 quiz_container">
    <form action="#!" id="quiz_form" method="post">
        <input type="hidden" name="ujian_pengguna_id" value="<?php echo $ujian_pengguna_data->id; ?>"/>
        <input type="hidden" name="paket_id" value="<?php echo $quiz->paket_id; ?>"/>

        <div class="row mt-4">
            <?php
            foreach($questions as $index => $question){
                $question_number = $index + 1;
                ?>
                <div class="questions mb-4">
                    <?php echo $question_number.'. '.$question->question; ?>
                    <div class="fw-bold mt-3">Jawaban:</div>
                    <?php
                    if($question->question_type == PILIHAN_GANDA_KOMPLEKS){
                        echo '<div class="text-muted fw-italic"><i class="fa fa-info-circle"></i> Dapat Dipilih Lebih Dari Satu:</div>';
                    }
                    ?>
                    <div class="mb-3"></div>
                    <?php
                    foreach($answers as $answer){
                        if($answer->soal_id == $question->id){
                            //check if selected on old answer
                            $isChecked = false;
                            foreach($old_answers as $old_answer){
                                if($old_answer->soal_pilihan_id == $answer->id && $old_answer->soal_id == $question->id){
                                    $isChecked = true;
                                    break;
                                }
                            }

                            $checkedValue = $isChecked ? 'checked' : '';

                            if($question->question_type == PILIHAN_GANDA){
                                echo '<div class="row">';
                                echo '<div class="col-12 col-sm-12 col-md-10 col-lg-4">';
                                echo '<label class="labl">';
                                echo '<input class="answer" data-soal-id="'.$question->id.'" name="answer['.$question->id.']" type="radio" value="'.$answer->id.'" '.$checkedValue.'/>';
                                echo '<div>'.$answer->label.'</div>';
                                echo '</label>';
                                echo '</div>';
                                echo '</div>';
                            }else if($question->question_type == PILIHAN_GANDA_KOMPLEKS){
                                echo '<div class="row">';
                                echo '<div class="col-12 col-sm-12 col-md-10 col-lg-4">';
                                echo '<label class="labl">';
                                echo '<input class="answer" data-soal-id="'.$question->id.'" name="answer['.$question->id.']" type="checkbox" value="'.$answer->id.'" '.$checkedValue.'/>';
                                echo '<div>'.$answer->label.'</div>';
                                echo '</label>';
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                    }
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="text-center">
            <button class="btn btn-default"><i class="fa fa-check"></i>&nbsp;SELESAI UJIAN</button>
        </div>
    </form>
</div>
<?php }
}else{ ?>
<div class="container mb-5">
    <div class="row mb-2">
        <div class="col-12">
            <a href="#" class="out_quiz link-dark ms-2"><h4><i class="fa fa-arrow-left"></i></h4></a>
        </div>
    </div>
    <h4 class="fw-light mb-4">
        Ujian Tidak Ditemukan
    </h4>
</div>
<?php } ?>