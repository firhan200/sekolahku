<?php
if(!defined('ABSPATH')){
	echo "Please do not access this file :)";
	exit;
}

add_action('wp_ajax_nopriv_legal101_login_user', 'legal101_login_user');
add_action('wp_ajax_legal101_login_user', 'legal101_login_user');

add_action('wp_ajax_nopriv_legal101_logout_user', 'legal101_logout_user');
add_action('wp_ajax_legal101_logout_user', 'legal101_logout_user');

global $wpdb;

/*
LOGIN USER 
*/
function legal101_login_user(){
    global $wpdb;

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

        $user = $wpdb->get_row("SELECT * FROM "._tbl_users." WHERE email_address = '$email_address' AND password = '$encrypted_password'");
        if($user == null){
            $response['is_success'] = false;
            $response['errors'][] = 'Pengguna tidak ditemukan.';
        }
    }

    if($response['is_success']){
        //set session
        $_SESSION[SESSION_ID] = $user->id;
    }

    wp_send_json($response);

    exit;
}
/*
LOGIN USER 
*/

/*
LOGOUT USER 
*/
function legal101_logout_user(){
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