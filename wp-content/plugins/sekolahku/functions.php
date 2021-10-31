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

global $wpdb;

//get table name
$plugin_name = 'sekolahku';
$table_kelas = $wpdb->prefix . $plugin_name . "_kelas"; 
$table_mata_pelajaran = $wpdb->prefix . $plugin_name . "_matapelajaran"; 
$table_bab = $wpdb->prefix . $plugin_name . "_bab"; 
$table_mata_pelajaran_kelas = $wpdb->prefix . $plugin_name . "_matapelajaran_kelas"; 
$table_pengguna = $wpdb->prefix . $plugin_name . "_pengguna"; 
$table_pengguna_kelas = $wpdb->prefix . $plugin_name . "_pengguna_kelas"; 

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
?>