<?php
if(!defined('ABSPATH')){
	echo "Please do not access this file :)";
	exit;
}

//register ajax
add_action('wp_ajax_nopriv_register_user', 'register_user');
add_action('wp_ajax_register_user', 'register_user');

add_action('wp_ajax_nopriv_login_user', 'login_user');
add_action('wp_ajax_login_user', 'login_user');

add_action('wp_ajax_nopriv_logout_user', 'logout_user');
add_action('wp_ajax_logout_user', 'logout_user');

add_action('wp_ajax_nopriv_submit_quiz', 'submit_quiz');
add_action('wp_ajax_submit_quiz', 'submit_quiz');

add_action('wp_ajax_nopriv_history', 'history');
add_action('wp_ajax_history', 'history');

global $wpdb;

//get table name
$plugin_name = 'sekolahku';
$table_kelas = $wpdb->prefix . $plugin_name . "_kelas"; 
$table_mata_pelajaran = $wpdb->prefix . $plugin_name . "_matapelajaran"; 
$table_bab = $wpdb->prefix . $plugin_name . "_bab"; 
$table_mata_pelajaran_kelas = $wpdb->prefix . $plugin_name . "_matapelajaran_kelas"; 
$table_pengguna = $wpdb->prefix . $plugin_name . "_pengguna"; 
$table_pengguna_kelas = $wpdb->prefix . $plugin_name . "_pengguna_kelas"; 
$table_paket = $wpdb->prefix . $plugin_name . "_paket"; 
$table_paket_soal = $wpdb->prefix . $plugin_name . "_paket_soal"; 
$table_soal = $wpdb->prefix . $plugin_name . "_soal"; 
$table_soal_pilihan = $wpdb->prefix . $plugin_name . "_soal_pilihan"; 
$table_ujian = $wpdb->prefix . $plugin_name . "_ujian"; 
$table_ujian_pengguna = $wpdb->prefix . $plugin_name . "_ujian_pengguna"; 
$table_ujian_pengguna_jawaban = $wpdb->prefix . $plugin_name . "_ujian_pengguna_jawaban"; 

/*
LOGIN USER 
*/
function login_user(){
    global $wpdb;
    global $table_pengguna;
    global $table_pengguna_kelas;

    //to set session
    if ( ! session_id() ) {
        session_start();
    }

    $response = array(
        'is_success' => true,
        'errors' => [],
        'data' => null
    );

    //get data
    $data = file_get_contents("php://input");
    $data = json_decode($data, true);

    if($data != null){
        //get data
        $email_address = $data['email_address'];
        $password = $data['password'];

        //validate
        if($email_address == null && $email_address == ''){
            $response['errors'][] = 'Email tidak boleh kosong';
        }

        if($password == null && $password == ''){
            $response['errors'][] = 'Password tidak boleh kosong';
        }
    }

    if(count($response['errors']) > 0){
        $response['is_success'] = false;
    }else{
        //search from db
        $encrypted_password = sha1($password);

        $user = $wpdb->get_row("SELECT * FROM ".$table_pengguna." WHERE email_address = '$email_address' AND password = '$encrypted_password'");
        if($user == null){
            $response['is_success'] = false;
            $response['errors'][] = 'Pengguna tidak ditemukan.';
        }
    }

    if($response['is_success']){
        //get kelas ids
        $kelas_ids = $wpdb->get_var("SELECT GROUP_CONCAT(kelas_id) FROM ".$table_pengguna_kelas." WHERE pengguna_id = '".$user->id."'");

        //set session
        $_SESSION[SESSION_ID] = $user->id;
        $_SESSION[SESSION_NAME] = $user->full_name;
        $_SESSION[SESSION_KELAS_IDS] = $kelas_ids;
    }

    wp_send_json($response);

    exit;
}
/*
LOGIN USER 
*/

/*
REGISTER USER 
*/
function register_user(){
    global $wpdb;
    global $table_pengguna;

    $response = array(
        'is_success' => true,
        'errors' => [],
        'data' => null
    );

    //get data
    $data = file_get_contents("php://input");
    $data = json_decode($data, true);

    if($data != null){
        //get data
        $name = $data['name'];
        $email_address = $data['email_address'];
        $password = $data['password'];
        $repeat_password = $data['repeat_password'];
        $gender = $data['gender'];
        $account_type = 1; //siswa
        $is_active = 1;

        //validate
        if($name == null && $name == ''){
            $response['errors'][] = 'Name tidak boleh kosong';
        }

        if($email_address == null && $email_address == ''){
            $response['errors'][] = 'Email tidak boleh kosong';
        }

        //check if email already taken
        $existUser = $wpdb->get_row("SELECT * FROM ".$table_pengguna." WHERE email_address = '$email_address'");
        if($existUser != null){
            $response['errors'][] = '<b>Email</b> Telah Terpakai.';
        }

        if($password == null && $password == ''){
            $response['errors'][] = 'Password tidak boleh kosong';
        }else{
            $password = trim($password);
            if(strlen($password) < 8){
                $response['errors'][] = 'Password minimal 8 karakter';
            }else{
                if($password != $repeat_password){
                    $response['errors'][] = 'Ulangi Password tidak sama dengan Password';
                }
            }
        }

        if($gender == null){
            $response['errors'][] = 'Jenis Kelamin tidak boleh kosong';
        }
    }else{
        $response['errors'][] = 'Tidak ada masukan data';
    }

    if(count($response['errors']) > 0){
        $response['is_success'] = false;
    }else{
        //register user
        //insert into wpdb database
        $encrypted_password = sha1($password);
        $wpdb->insert(
            $table_pengguna,
            array(
                'full_name' => $name,
                'email_address' => $email_address,
                'password' => $encrypted_password,
                'account_type' => $account_type,
                'gender' => $gender,
                'is_active' => $is_active
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%d',
                '%d',
                '%d',
            )
        );

        $pengguna_id = $wpdb->insert_id;

        if($pengguna_id == null || $pengguna_id == 0){
            $response['is_success'] = false;
            $response['errors'][] = "Gagal memasukan data. ".$table_pengguna;
        }
    }

    wp_send_json($response);

    exit;
}
/*
REGISTER USER 
*/

/*
LOGOUT USER 
*/
function logout_user(){
    global $wpdb;
    global $table_pengguna;

    //to set session
    if ( ! session_id() ) {
        session_start();
    }

    $response = array(
        'is_success' => true,
        'errors' => [],
        'data' => null
    );

    //destroy session
    session_destroy();

    wp_send_json($response);

    exit;
}
/*
LOGOUT USER 
*/

/* 
Submit Quiz 
*/
function submit_quiz(){
    //to set session
    if ( ! session_id() ) {
        session_start();
    }

    $response = array(
        'is_success' => true,
        'errors' => [],
        'data' => null
    );

    //get data
    $data = file_get_contents("php://input");
    $data = json_decode($data, true);

    if($data != null){
        //save to db
        save_quiz_answer($data);

        if($data['is_finish']){
            //calculate score
            $score = calculate_score($data);
        }
    }

    wp_send_json($response);

    exit;
}

function calculate_score($data = null){
    global $wpdb;
    global $table_paket;
    global $table_paket_soal;
    global $table_soal;
    global $table_soal_pilihan;
    global $table_ujian_pengguna;
    global $table_ujian_pengguna_jawaban;

    $response = array(
        'is_success' => true,
        'errors' => [],
        'data' => null
    );

    if($data != null){
        //get vars
        $ujian_pengguna_id = $data['ujian_pengguna_id'];
        $answers_raw = $data['answers'];
        $paket_id = $data['paket_id'];

        $soal_query = $wpdb->get_results('SELECT s.* FROM '.$table_paket.' AS p LEFT JOIN '.$table_paket_soal.' AS ps ON ps.paket_id=p.id LEFT JOIN '.$table_soal.' AS s ON ps.soal_id=s.id WHERE p.id='.$paket_id);

        //init soal ids
        $soal_ids = '';
        $list_soal = array();

        //loop
        foreach($soal_query as $soal){
            $soal_ids .= $soal->id.',';

            $list_soal[] = $soal;
        }

        //remove comma
        $soal_ids = rtrim($soal_ids, ',');

        //get soal pilihan
        $soal_pilihan_query = array();
        if($soal_ids != ''){
            //get soal
            $soal_pilihan_query = $wpdb->get_results('SELECT * FROM '.$table_soal_pilihan.' WHERE soal_id IN ('.$soal_ids.')');

            foreach($list_soal as $index => $soal){
                foreach($soal_pilihan_query as $soal_pilihan){
                    if($soal->id == $soal_pilihan->soal_id){
                        $list_soal[$index]->pilihan[] = $soal_pilihan;
                    }
                }
            }
        }

        //check jawaban
        $answers_arr = array();
        $total_correct_answer = 0;
        $total_questions = count($list_soal);

        //loop answers
        foreach($answers_raw as $answer){
            //check if soal id already exist
            if(array_key_exists($answer['soal_id'], $answers_arr)){
                $answers_arr[$answer['soal_id']][] = $answer['soal_pilihan_id'];
            }else{
                $answers_arr[$answer['soal_id']] = array($answer['soal_pilihan_id']);
            }
        }

        //loop all questions
        foreach($list_soal as $soal){
            //loop answers
            foreach($answers_arr as $soal_id => $answer){
                //check if answer on question
                if($soal_id == $soal->id){
                    if($soal->question_type == PILIHAN_GANDA){
                        $is_correct = false; //set default
                    }else if($soal->question_type == PILIHAN_GANDA_KOMPLEKS){
                        $is_correct = true; //set default
                    }

                    //loop question options
                    foreach($soal->pilihan as $soal_pilihan){
                        foreach($answer as $answer_id){
                            if($soal_pilihan->id == $answer_id){
                                 //check type
                                if($soal->question_type == PILIHAN_GANDA){
                                    if($soal_pilihan->id == $answer_id){
                                        $is_correct = $soal_pilihan->score > 0 ? true : false;
                                        break;
                                    }
                                }else if($soal->question_type == PILIHAN_GANDA_KOMPLEKS){
                                    if($soal_pilihan->id == $answer_id){
                                        $is_correct = $soal_pilihan->score < 1 ? false : true;
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    $total_correct_answer = ($is_correct) ? $total_correct_answer + 1 : $total_correct_answer;
                }
            }
        }

        //update ujian pengguna db
        $wpdb->update(
            $table_ujian_pengguna,
            array(
                'score' => (($total_correct_answer / $total_questions) * 100),
                'end_date' => $wpdb->get_var("SELECT NOW()"),
                'total_question' => $total_questions,
                'total_correct_question' => $total_correct_answer,
                'status' => QUIZ_FINISHED
            ),
            array(
                'id' => $ujian_pengguna_id
            )
        );
    }
}

function find_key_value($array, $key, $val)
{
    foreach ($array as $item)
    {
        if (is_array($item) && find_key_value($item, $key, $val)) return true;

        if (isset($item[$key]) && $item[$key] == $val) return true;
    }

    return false;
}

function save_quiz_answer($data = null){
    global $wpdb;
    global $table_ujian_pengguna;
    global $table_ujian_pengguna_jawaban;

    if($data != null){
        //get vars
        $ujian_pengguna_id = $data['ujian_pengguna_id'];
        $answers = $data['answers'];

        //clear ujian pengguna jawaban
        $wpdb->delete(
            $table_ujian_pengguna_jawaban,
            array(
                'ujian_pengguna_id' => $data['ujian_pengguna_id']
            ),
            array(
                '%d'
            )
        );

        //re-insert ujian pengguna jawaban
        foreach($answers as $answer){
            $wpdb->insert(
                $table_ujian_pengguna_jawaban,
                array(
                    'ujian_pengguna_id' => $ujian_pengguna_id,
                    'soal_id' => $answer['soal_id'],
                    'soal_pilihan_id' => $answer['soal_pilihan_id'],
                    'label' => $answer['label']
                ),
                array(
                    '%d',
                    '%d',
                    '%d',
                    '%s'
                )
            );
        }
    }
}
/* 
Submit Quiz 
*/

/*
History Quiz
*/
function history(){
    global $wpdb;
    global $table_ujian;
    global $table_paket;
    global $table_kelas;
    global $table_mata_pelajaran;
    global $table_ujian_pengguna;
    
    //to set session
    if ( ! session_id() ) {
        session_start();
    }

    //init return response
    $response = array(
        'is_success' => true,
        'errors' => [],
        'page' => 1,
        'data' => null
    );

    //get data
    $data = file_get_contents("php://input");
    $data = json_decode($data, true);

    //check data
    if($data != null){
        //get vars
        $page = $data['page'];
        $per_page = 10;

        //parse to res
        $response['page'] = $page;

        //init query
        $query = 'SELECT u.*, p.name AS paket_name, k.name AS kelas_name, m.name AS matapelajaran_name, up.status AS ujian_status, up.score AS score, up.end_date AS ujian_end_date, up.start_date AS ujian_start_date FROM '.$table_ujian.' AS u LEFT JOIN '.$table_paket.' AS p ON u.paket_id=p.id LEFT JOIN '.$table_kelas.' AS k ON u.kelas_id=k.id LEFT JOIN '.$table_mata_pelajaran.' AS m ON p.matapelajaran_id=m.id LEFT JOIN '.$table_ujian_pengguna.' AS up ON up.ujian_id=u.id WHERE u.kelas_id IN (28) AND up.status='.QUIZ_FINISHED.' AND up.pengguna_id='.$_SESSION[SESSION_ID];

        //ordering
        $query .= ' ORDER BY u.id DESC';

        //pagination
        $query .= ' LIMIT '.(($page - 1) * $per_page).', '.$per_page;

        //get results
        $list_ujian = $wpdb->get_results($query);

        //check results
        $response['data'] = $list_ujian;
    }


    wp_send_json($response);

    exit;
}
/*
History Quiz
*/
?>