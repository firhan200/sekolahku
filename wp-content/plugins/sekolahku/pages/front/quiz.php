<?php
global $wpdb;
$my_plugin = plugin_dir_url('').'sekolahku';

$quiz_id = $_GET['id'];
$is_valid = false;
$menu_quiz = true;
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
}
?>

<?php if($is_valid){ ?>
<div class="container mb-5">
    <div class="row mb-2">
        <div class="col-12">
            <a href="#" class="out_quiz link-dark ms-2"><h4><i class="fa fa-arrow-left"></i></h4></a>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-6">
            <h4 class="fw-light">
                Ujian <?php echo $quiz->paket_name; ?>
            </h4>
            <div>
                <?php echo '<span class="badge bg-default">'.$quiz->matapelajaran_name.'</span> - <span class="fw-light">'.$quiz->kelas_name.'</span>'; ?>
            </div>
        </div>
        <div class="col-12 col-sm-6 text-end">
            23:59:59
        </div>
    </div>
    <div class="row mt-4">
        <?php
        foreach($questions as $index => $question){
            $question_number = $index + 1;
            ?>
            <div class="questions">
                <?php echo $question_number.'. '.$question->question; ?>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<?php }else{ ?>
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