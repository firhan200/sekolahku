<?php
/*
Plugin Name: Sekolahku
Plugin URI: 
Description: Adakan Ujian & Pembelajaran Digital Lewat Sekolahku
Author: Firhan
Version: 1.0
Author URI: https://firhan200.github.io/
*/

if(!defined('ABSPATH')){
	echo "Please do not access this file :)";
	exit;
}

include_once( __DIR__ . '/functions.php' );

//define
define('PILIHAN_GANDA', 1);
define('PILIHAN_GANDA_KOMPLEKS', 2);

define('PILIHAN_GANDA_LABEL', 'Pilihan Ganda');
define('PILIHAN_GANDA_KOMPLEKS_LABEL', 'Pilihan Ganda Kompleks');

define('SESSION_ID', 'sekolahku_user_id');
define('SESSION_NAME', 'sekolahku_user_name');
define('SESSION_KELAS_IDS', 'sekolahku_kelas_ids');

define('UJIAN_SEDANG_BERLANGSUNG', 'Sedang Berlangsung');
define('UJIAN_BELUM_DIMULAI', 'Belum Dimulai');
define('UJIAN_SUDAH_BERAKHIR', 'Sudah Berakhir');

define('QUIZ_ONGOING', 0);
define('QUIZ_FINISHED', 1);

$sekolahku_pages = array(
	'login' => 'sekolahku-masuk',
	'register' => 'sekolahku-daftar',
	'dashboard' => 'sekolahku-dashboard',
	'setting' => 'sekolahku-pengaturan',
	'quiz' => 'sekolahku-quiz',
	'history' => 'sekolahku-riwayat',
);

/* 
* Front-End Page Handler
*/
function is_authorize(){
	global $sekolahku_pages;

	if ( ! session_id() ) {
        session_start();
    }

	//get site url
	$host_url = get_site_url();

	if(!isset($_SESSION[SESSION_ID])){
		echo '<script type="text/javascript">window.location.href = "'.$host_url.'/'.$sekolahku_pages['login'].'"</script>';
		die();
	}
}

function is_guest(){
	global $sekolahku_pages;

	if ( ! session_id() ) {
        session_start();
    }

	//get site url
	$host_url = get_site_url();

	if(isset($_SESSION[SESSION_ID])){
		echo '<script type="text/javascript">window.location.href = "'.$host_url.'/'.$sekolahku_pages['dashboard'].'";</script>';
		die();
	}
}

add_action('template_redirect','front_page_redirect_handler');
function front_page_redirect_handler(){
	global $post;
	global $sekolahku_pages;

	$is_found = false;

	$pages_dir = array(
		'login' => __DIR__ . '/pages/front/login.php',
		'register' => __DIR__ . '/pages/front/register.php',
		'dashboard' => __DIR__ . '/pages/front/dashboard.php',
		'setting' => __DIR__ . '/pages/front/setting.php',
		'quiz' => __DIR__ . '/pages/front/quiz.php',
		'history' => __DIR__ . '/pages/front/history.php',
	);

	if (is_page()) {
		if($post->post_name == $sekolahku_pages['login']){
			is_guest();

			$is_found = true;
		
			//render page
			$page =  $pages_dir['login'];
		}
		else if($post->post_name == $sekolahku_pages['register']){
			is_guest();

			$is_found = true;
		
			//render page
			$page =  $pages_dir['register'];
		}
		else if($post->post_name == $sekolahku_pages['dashboard']){
			is_authorize();

			$is_found = true;
		
			//render page
			$page =  $pages_dir['dashboard'];
		}
		else if($post->post_name == $sekolahku_pages['setting']){
			is_authorize();

			$is_found = true;
		
			//render page
			$page =  $pages_dir['setting'];
		}
		else if($post->post_name == $sekolahku_pages['quiz']){
			is_authorize();

			$is_found = true;
		
			//render page
			$page =  $pages_dir['quiz'];
		}
		else if($post->post_name == $sekolahku_pages['history']){
			is_authorize();

			$is_found = true;
		
			//render page
			$page =  $pages_dir['history'];
		}
	}

	if ($is_found) {
		include_once(__DIR__ . '/pages/front/header.php');
		include_once($page);
		include_once(__DIR__ . '/pages/front/footer.php');

		exit;
	}
}
/* 
* Front-End Page Handler
*/

/**
 * Activate the plugin.
 */
function pluginprefix_activate() { 
    global $wpdb;

    $plugin_name = 'sekolahku';

	//get table name
	$table_kelas = $wpdb->prefix . $plugin_name . "_kelas"; 
	$table_mata_pelajaran = $wpdb->prefix . $plugin_name . "_matapelajaran"; 
	$table_bab = $wpdb->prefix . $plugin_name . "_bab"; 
	$table_mata_pelajaran_kelas = $wpdb->prefix . $plugin_name . "_matapelajaran_kelas"; 
	$table_pengguna = $wpdb->prefix . $plugin_name . "_pengguna"; 
	$table_pengguna_kelas = $wpdb->prefix . $plugin_name . "_pengguna_kelas"; 
	
	//table soal soal
	$table_soal = $wpdb->prefix . $plugin_name . "_soal"; 
	$table_soal_pilihan = $wpdb->prefix . $plugin_name . "_soal_pilihan"; 
	$table_paket = $wpdb->prefix . $plugin_name . "_paket"; 
	$table_paket_soal = $wpdb->prefix . $plugin_name . "_paket_soal";
	
	//table ujian
	$table_ujian = $wpdb->prefix . $plugin_name . "_ujian"; 
	$table_ujian_pengguna = $wpdb->prefix . $plugin_name . "_ujian_pengguna"; 
	$table_ujian_pengguna_jawaban = $wpdb->prefix . $plugin_name . "_ujian_pengguna_jawaban"; 

	//check if table exists
	if($wpdb->get_var("SHOW TABLES LIKE '$table_kelas'") != $table_kelas) {
		//table not in database. Create new table
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_kelas (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name varchar(255) NOT NULL,
		is_active smallint(1) DEFAULT 1 NOT NULL,
		created_on timestamp DEFAULT current_timestamp,
		updated_on timestamp DEFAULT current_timestamp ON UPDATE current_timestamp,
		PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

    //check if table exists
	if($wpdb->get_var("SHOW TABLES LIKE '$table_mata_pelajaran'") != $table_mata_pelajaran) {
		//table not in database. Create new table
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_mata_pelajaran (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name varchar(255) NOT NULL,
		is_active smallint(1) DEFAULT 1 NOT NULL,
		created_on timestamp DEFAULT current_timestamp,
		updated_on timestamp DEFAULT current_timestamp ON UPDATE current_timestamp,
		PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

    //check if table exists
	if($wpdb->get_var("SHOW TABLES LIKE '$table_mata_pelajaran_kelas'") != $table_mata_pelajaran_kelas) {
		//table not in database. Create new table
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_mata_pelajaran_kelas (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		kelas_id mediumint(9) NOT NULL,
		matapelajaran_id mediumint(9) NOT NULL,
		created_on timestamp DEFAULT current_timestamp,
		PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	//check if table exists
	if($wpdb->get_var("SHOW TABLES LIKE '$table_bab'") != $table_bab) {
		//table not in database. Create new table
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_bab (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		matapelajaran_id mediumint(9) NOT NULL,
		name varchar(255) NOT NULL,
		description varchar(255) NOT NULL,
		is_active smallint(1) DEFAULT 1 NOT NULL,
		created_on timestamp DEFAULT current_timestamp,
		PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	//check if table exists
	if($wpdb->get_var("SHOW TABLES LIKE '$table_pengguna'") != $table_pengguna) {
		//table not in database. Create new table
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_pengguna (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		full_name varchar(255) NOT NULL,
		email_address varchar(255) NOT NULL,
		password varchar(100) NOT NULL,
		account_type smallint(2) NOT NULL, /* teacher or student */
		gender smallint(2) NOT NULL,
		is_active smallint(1) DEFAULT 1 NOT NULL,
		created_on timestamp DEFAULT current_timestamp,
		updated_on timestamp DEFAULT current_timestamp ON UPDATE current_timestamp,
		PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	//check if table exists
	if($wpdb->get_var("SHOW TABLES LIKE '$table_pengguna_kelas'") != $table_pengguna_kelas) {
		//table not in database. Create new table
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_pengguna_kelas (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		pengguna_id mediumint(9) NOT NULL,
		kelas_id mediumint(9) NOT NULL,
		created_on timestamp DEFAULT current_timestamp,
		PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	//check if table exists
	if($wpdb->get_var("SHOW TABLES LIKE '$table_soal'") != $table_soal) {
		//table not in database. Create new table
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_soal (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		title varchar(150) NOT NULL,
		question TEXT NOT NULL,
		explanation TEXT NULL,
		question_type mediumint(9) NOT NULL,
		is_lock smallint(1) DEFAULT 0 NOT NULL,
		is_active smallint(1) DEFAULT 1 NOT NULL,
		created_on timestamp DEFAULT current_timestamp,
		updated_on timestamp DEFAULT current_timestamp ON UPDATE current_timestamp,
		PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	//check if table exists
	if($wpdb->get_var("SHOW TABLES LIKE '$table_soal_pilihan'") != $table_soal_pilihan) {
		//table not in database. Create new table
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_soal_pilihan (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		soal_id mediumint(9) NOT NULL,
		label TEXT NOT NULL,
		score mediumint(9) NOT NULL,
		is_active smallint(1) DEFAULT 1 NOT NULL,
		created_on timestamp DEFAULT current_timestamp,
		updated_on timestamp DEFAULT current_timestamp ON UPDATE current_timestamp,
		PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	//check if table exists
	if($wpdb->get_var("SHOW TABLES LIKE '$table_paket'") != $table_paket) {
		//table not in database. Create new table
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_paket (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		matapelajaran_id mediumint(9) NOT NULL,
		name varchar(150) NOT NULL,
		description TEXT NULL,
		is_lock smallint(1) DEFAULT 0 NOT NULL,
		is_active smallint(1) DEFAULT 1 NOT NULL,
		created_on timestamp DEFAULT current_timestamp,
		updated_on timestamp DEFAULT current_timestamp ON UPDATE current_timestamp,
		PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	//check if table exists
	if($wpdb->get_var("SHOW TABLES LIKE '$table_paket_soal'") != $table_paket_soal) {
		//table not in database. Create new table
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_paket_soal (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		paket_id mediumint(9) NOT NULL,
		soal_id mediumint(9) NOT NULL,
		is_active smallint(1) DEFAULT 1 NOT NULL,
		created_on timestamp DEFAULT current_timestamp,
		updated_on timestamp DEFAULT current_timestamp ON UPDATE current_timestamp,
		PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	//check if table exists
	if($wpdb->get_var("SHOW TABLES LIKE '$table_ujian'") != $table_ujian) {
		//table not in database. Create new table
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_ujian (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		paket_id mediumint(9) NOT NULL,
		kelas_id mediumint(9) NOT NULL,
		duration_seconds mediumint(9.) NOT NULL,
		start_date datetime NOT NULL,
		end_date datetime NOT NULL,
		randomize_questions smallint(1) DEFAULT 1 NOT NULL,
		created_on timestamp DEFAULT current_timestamp,
		updated_on timestamp DEFAULT current_timestamp ON UPDATE current_timestamp,
		PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	//check if table exists
	if($wpdb->get_var("SHOW TABLES LIKE '$table_ujian_pengguna'") != $table_ujian_pengguna) {
		//table not in database. Create new table
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_ujian_pengguna (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		ujian_id mediumint(9) NOT NULL,
		pengguna_id mediumint(9) NOT NULL,
		score double(5,2) NULL,
		start_date datetime NOT NULL,
		end_date datetime NULL,
		status smallint(1) DEFAULT 0 NOT NULL,
		created_on timestamp DEFAULT current_timestamp,
		updated_on timestamp DEFAULT current_timestamp ON UPDATE current_timestamp,
		PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	//check if table exists
	if($wpdb->get_var("SHOW TABLES LIKE '$table_ujian_pengguna_jawaban'") != $table_ujian_pengguna_jawaban) {
		//table not in database. Create new table
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_ujian_pengguna_jawaban (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		ujian_pengguna_id mediumint(9) NOT NULL,
		soal_id mediumint(9) NOT NULL,
		soal_pilihan_id mediumint(9) NOT NULL,
		created_on timestamp DEFAULT current_timestamp,
		updated_on timestamp DEFAULT current_timestamp ON UPDATE current_timestamp,
		PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	create_pages();

	set_transient( 'activated_plugin_msg', true, 5 );
}

function create_pages(){
    global $wpdb;
		
	$sekolahku_pages = array(
		'login' => 'sekolahku-masuk',
		'register' => 'sekolahku-daftar',
		'dashboard' => 'sekolahku-dashboard',
		'setting' => 'sekolahku-pengaturan',
		'quiz' => 'sekolahku-quiz',
		'history' => 'sekolahku-riwayat',
	);

	/* CREATE FRONT END PAGE POST */
	$login_page = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'posts WHERE post_name="'.$sekolahku_pages['login'].'"');
	if($login_page == null){
		$login_post_id = wp_insert_post(array (
			'post_type' => 'page',
			'post_title' => 'Sekolahku - Masuk',
			'post_name' => '"'.$sekolahku_pages['login'].'"',
			'post_content' => '',
			'post_status' => 'publish',
			'comment_status' => 'closed',   // if you prefer
			'ping_status' => 'closed',      // if you prefer
		));
	}

	$register_page = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'posts WHERE post_name="'.$sekolahku_pages['register'].'"');
	if($register_page == null){
		$register_post_id = wp_insert_post(array (
			'post_type' => 'page',
			'post_title' => 'Sekolahku - Daftar',
			'post_name' => '"'.$sekolahku_pages['register'].'"',
			'post_content' => '',
			'post_status' => 'publish',
			'comment_status' => 'closed',   // if you prefer
			'ping_status' => 'closed',      // if you prefer
		));
	}

	$dashboard_page = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'posts WHERE post_name="'.$sekolahku_pages['dashboard'].'"');
	if($dashboard_page == null){
		$dashboard_post_id = wp_insert_post(array (
			'post_type' => 'page',
			'post_title' => 'Sekolahku - Dashboard',
			'post_name' => '"'.$sekolahku_pages['dashboard'].'"',
			'post_content' => '',
			'post_status' => 'publish',
			'comment_status' => 'closed',   // if you prefer
			'ping_status' => 'closed',      // if you prefer
		));
	}

	$setting_page = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'posts WHERE post_name="'.$sekolahku_pages['setting'].'"');
	if($setting_page == null){
		$setting_post_id = wp_insert_post(array (
			'post_type' => 'page',
			'post_title' => 'Sekolahku - Pengaturan',
			'post_name' => '"'.$sekolahku_pages['setting'].'"',
			'post_content' => '',
			'post_status' => 'publish',
			'comment_status' => 'closed',   // if you prefer
			'ping_status' => 'closed',      // if you prefer
		));
	}

	$quiz_page = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'posts WHERE post_name="'.$sekolahku_pages['quiz'].'"');
	if($quiz_page == null){
		$quiz_post_id = wp_insert_post(array (
			'post_type' => 'page',
			'post_title' => 'Sekolahku - Ujian',
			'post_name' => '"'.$sekolahku_pages['quiz'].'"',
			'post_content' => '',
			'post_status' => 'publish',
			'comment_status' => 'closed',   // if you prefer
			'ping_status' => 'closed',      // if you prefer
		));
	}

	$history_page = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'posts WHERE post_name="'.$sekolahku_pages['history'].'"');
	if($history_page == null){
		$history_post_id = wp_insert_post(array (
			'post_type' => 'page',
			'post_title' => 'Sekolahku - Riwayat',
			'post_name' => '"'.$sekolahku_pages['history'].'"',
			'post_content' => '',
			'post_status' => 'publish',
			'comment_status' => 'closed',   // if you prefer
			'ping_status' => 'closed',      // if you prefer
		));
	}
	/* CREATE FRONT END PAGE POST */
}

register_activation_hook( __FILE__, 'pluginprefix_activate' );

add_action( 'admin_notices', 'sekolahku_admin_notice' );

function sekolahku_admin_notice(){
	global $wpdb;
	global $sekolahku_pages;

	/* Check transient, if available display notice */
    if( get_transient( 'activated_plugin_msg' ) ){
		//show notice here
        /* Delete transient, only display this notice once. */
        delete_transient( 'activated_plugin_msg' );
    }
}
/**
 * Activate the plugin.
 */

/* create administrator menu */

//call action
add_action("admin_menu", 'add_plugin_cms_menu');

//init function
function add_plugin_cms_menu(){
    add_menu_page("Sekolahku", "Sekolahku", "manage_options", "sekolahku", "sekolahku_page", "dashicons-welcome-learn-more", 6);

    /* Kelas */
    add_submenu_page("sekolahku", "Kelas", "Kelas", "manage_options", "kelas", "kelas_page");

    /* Mata Pelajaran */
    add_submenu_page("sekolahku", "Mata Pelajaran", "Mata Pelajaran", "manage_options", "matapelajaran", "mata_pelajaran_page");

	/* Bab */
    add_submenu_page(null, "Bab", "Bab", "manage_options", "bab", "bab_page");

	/* Pengguna */
    add_submenu_page("sekolahku", "Guru & Siswa", "Guru & Siswa", "manage_options", "pengguna", "pengguna_page");

	/* Soal */
    add_submenu_page("sekolahku", "Soal", "Soal", "manage_options", "soal", "soal_page");

	/* Paket */
    add_submenu_page("sekolahku", "Paket", "Paket", "manage_options", "paket", "paket_page");

	/* Paket Soal */
    add_submenu_page(null, "Paket Soal", "Paket Soal", "manage_options", "paket_soal", "paket_soal_page");

	/* Ujian */
    add_submenu_page("sekolahku", "Ujian", "Ujian", "manage_options", "ujian", "ujian_page");
}

/* create administrator menu */

/* Add Assets */

/* Append Style */
add_action("admin_enqueue_scripts", 'addStyle');

/* Append Scripts */
add_action("admin_enqueue_scripts", 'addScripts');

/* add Style */
function addStyle(){
	wp_enqueue_style("sekolahku-style-select2", plugins_url("/assets/css/select2.min.css", __FILE__));
	wp_enqueue_style("sekolahku-style-daterangepicker", plugins_url("/assets/css/daterangepicker.css", __FILE__));
	wp_enqueue_style("sekolahku-style", plugins_url("/assets/css/style.css", __FILE__));
}

/* add Scripts */
function addScripts(){
	wp_enqueue_script("sekolahku-script-jquery", plugins_url("/assets/js/jquery-3.6.0.min.js", __FILE__), array("jquery"));
	wp_enqueue_script("sekolahku-script-moment", plugins_url("/assets/js/moment.min.js", __FILE__), array("jquery"));
	wp_enqueue_script("sekolahku-script-select2", plugins_url("/assets/js/select2.min.js", __FILE__), array("jquery"));
	wp_enqueue_script("sekolahku-script-daterangepicker", plugins_url("/assets/js/daterangepicker.min.js", __FILE__));
	wp_enqueue_script("sekolahku-script", plugins_url("/assets/js/app.js", __FILE__), array("jquery"));
}

/* Add Assets */


/* Main Page */

function sekolahku_page(){
    include_once( __DIR__ . '/pages/cms/dashboard.php' );
}

function kelas_page(){
    include_once( __DIR__ . '/pages/cms/kelas.php' );
}

function mata_pelajaran_page(){
    include_once( __DIR__ . '/pages/cms/mata_pelajaran.php' );
}

function bab_page(){
    include_once( __DIR__ . '/pages/cms/bab.php' );
}

function pengguna_page(){
    include_once( __DIR__ . '/pages/cms/pengguna.php' );
}

function soal_page(){
    include_once( __DIR__ . '/pages/cms/soal.php' );
}

function paket_page(){
    include_once( __DIR__ . '/pages/cms/paket.php' );
}

function paket_soal_page(){
    include_once( __DIR__ . '/pages/cms/paket_soal.php' );
}

function ujian_page(){
    include_once( __DIR__ . '/pages/cms/ujian.php' );
}

/* Main Page */

?>