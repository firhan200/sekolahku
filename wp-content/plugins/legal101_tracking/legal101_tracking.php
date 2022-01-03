<?php
/*
Plugin Name: Legal101 Tracking System
Plugin URI: 
Description: Tracking pengesahan legal
Author: Gotago
Version: 1.0
Author URI: https://firhan200.github.io/
*/

if(!defined('ABSPATH')){
	echo "Please do not access this file :)";
	exit;
}

//GLOBALS
global $wpdb;

define('_plugin_name', 'legal101_tracking');
//tables
define('_tbl_users', $wpdb->prefix._plugin_name.'_users');
define('_tbl_perizinan', $wpdb->prefix._plugin_name.'_perizinan');
define('_tbl_hki', $wpdb->prefix._plugin_name.'_hki');
define('_tbl_hki_documents', $wpdb->prefix._plugin_name.'_hki_documents');
define('_tbl_faktur', $wpdb->prefix._plugin_name.'_faktur');
define('_tbl_ppn', $wpdb->prefix._plugin_name.'_ppn');
define('_tbl_spt_tahunan', $wpdb->prefix._plugin_name.'_spt_tahunan');

//pages
define('_pages_login', $wpdb->prefix._plugin_name.'_login');
define('_pages_home', $wpdb->prefix._plugin_name.'_home');

//session
define('SESSION_ID', 'legal101_user_id');

//perizinan
define('PERIZINAN_CANCELLED', -1);
define('PERIZINAN_PENDING', 0);
define('PERIZINAN_ON_PROGRESS', 1);
define('PERIZINAN_DONE', 2);


/* 
* Front-End Page Handler
*/
function is_legal101_authorize(){
	global $sekolahku_pages;

	if ( ! session_id() ) {
        session_start();
    }

	//get site url
	$host_url = get_site_url();

	if(!isset($_SESSION[SESSION_ID])){
		echo '<script type="text/javascript">window.location.href = "'.$host_url.'/'._pages_login.'"</script>';
		die();
	}
}

function is_legal101_guest(){
	global $sekolahku_pages;

	if ( ! session_id() ) {
        session_start();
    }

	//get site url
	$host_url = get_site_url();

	if(isset($_SESSION[SESSION_ID])){
		echo '<script type="text/javascript">window.location.href = "'.$host_url.'/'._pages_home.'";</script>';
		die();
	}
}

add_action('template_redirect','legal101_front_page_redirect_handler');
function legal101_front_page_redirect_handler(){
	global $post;

	$is_found = false;

	$pages_dir = array(
		_pages_login => __DIR__ . '/pages/front/login.php',
		_pages_home => __DIR__ . '/pages/front/home.php',
	);

	if (is_page()) {
		if($post->post_name == _pages_login){
			is_legal101_guest();

			$is_found = true;
		
			//render page
			$page =  $pages_dir[_pages_login];
		}
		else if($post->post_name == _pages_home){
			is_legal101_authorize();

			$is_found = true;
		
			//render page
			$page =  $pages_dir[_pages_home];
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
function legal101_activated() { 
    global $wpdb;

    //get table name
	$tbl_users = _tbl_users; 
	$tbl_perizinan = _tbl_perizinan; 
	$tbl_hki = _tbl_hki; 
	$tbl_hki_documents = _tbl_hki_documents; 
	$tbl_faktur = _tbl_faktur; 
	$tbl_ppn = _tbl_ppn; 
	$tbl_spt_tahunan = _tbl_spt_tahunan; 

    //check if table exists
	if($wpdb->get_var("SHOW TABLES LIKE '$tbl_users'") != $tbl_users) {
        //table not in database. Create new table
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $tbl_users (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		company_name varchar(255) NOT NULL,
		email_address varchar(255) NOT NULL,
		password varchar(100) NOT NULL,
		pic_name varchar(255) NULL,
		company_address varchar(255) NULL,
		phone varchar(150) NULL,
		fax varchar(150) NULL,
		company_npwp varchar(150) NULL,
		main_director varchar(150) NULL,
		nib varchar(150) NULL,
		identity_number varchar(150) NULL,
		website varchar(150) NULL,
		npwp varchar(150) NULL,
		is_active smallint(1) DEFAULT 1 NOT NULL,
		created_on timestamp DEFAULT current_timestamp,
		updated_on timestamp DEFAULT current_timestamp ON UPDATE current_timestamp,
		PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
    }

	//check if table exists
	if($wpdb->get_var("SHOW TABLES LIKE '$tbl_perizinan'") != $tbl_perizinan) {
        //table not in database. Create new table
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $tbl_perizinan (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id mediumint(9) NOT NULL,
		description varchar(255) NOT NULL,
		progress_message varchar(255) NOT NULL,
		target_date date NULL,
		status smallint(1) DEFAULT 1 NOT NULL,
		created_on timestamp DEFAULT current_timestamp,
		updated_on timestamp DEFAULT current_timestamp ON UPDATE current_timestamp,
		PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
    }

	//check if table exists
	if($wpdb->get_var("SHOW TABLES LIKE '$tbl_hki'") != $tbl_hki) {
        //table not in database. Create new table
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $tbl_hki (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id mediumint(9) NOT NULL,
		pemohon varchar(255) NULL,
		pekerjaan varchar(255) NULL,
		no_agenda varchar(255) NULL,
		class varchar(255) NULL,
		tanggal_penerimaan date NULL,
		status VARCHAR(255) NULL,
		deadline date NULL,
		created_on timestamp DEFAULT current_timestamp,
		updated_on timestamp DEFAULT current_timestamp ON UPDATE current_timestamp,
		PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
    }

	//check if table exists
	if($wpdb->get_var("SHOW TABLES LIKE '$tbl_hki_documents'") != $tbl_hki_documents) {
        //table not in database. Create new table
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $tbl_hki_documents (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		hki_id mediumint(9) NOT NULL,
		attachment_id mediumint(9) NOT NULL,
		created_on timestamp DEFAULT current_timestamp,
		updated_on timestamp DEFAULT current_timestamp ON UPDATE current_timestamp,
		PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
    }

	//check if table exists
	if($wpdb->get_var("SHOW TABLES LIKE '$tbl_faktur'") != $tbl_faktur) {
        //table not in database. Create new table
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $tbl_faktur (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id mediumint(9) NOT NULL,
		tanggal_faktur date NULL,
		nomor_faktur varchar(255) NULL,
		created_on timestamp DEFAULT current_timestamp,
		updated_on timestamp DEFAULT current_timestamp ON UPDATE current_timestamp,
		PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
    }

	//check if table exists
	if($wpdb->get_var("SHOW TABLES LIKE '$tbl_ppn'") != $tbl_ppn) {
        //table not in database. Create new table
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $tbl_ppn (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id mediumint(9) NOT NULL,
		bulan_pajak int NOT NULL,
		tahun_pajak varchar(4) NOT NULL,
		status smallint(1) DEFAULT 0 NOT NULL,
		attachment_id mediumint(9) NULL,
		created_on timestamp DEFAULT current_timestamp,
		updated_on timestamp DEFAULT current_timestamp ON UPDATE current_timestamp,
		PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
    }

	//check if table exists
	if($wpdb->get_var("SHOW TABLES LIKE '$tbl_spt_tahunan'") != $tbl_spt_tahunan) {
        //table not in database. Create new table
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $tbl_spt_tahunan (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id mediumint(9) NOT NULL,
		tahun varchar(4) NOT NULL,
		status smallint(1) DEFAULT 0 NOT NULL,
		attachment_id mediumint(9) NULL,
		created_on timestamp DEFAULT current_timestamp,
		updated_on timestamp DEFAULT current_timestamp ON UPDATE current_timestamp,
		PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
    }
}

register_activation_hook( __FILE__, 'legal101_activated' );
/**
 * Activate the plugin.
 */

/* Add Assets */

/* Append Style */
add_action("admin_enqueue_scripts", 'legal101_addStyle');

/* Append Scripts */
add_action("admin_enqueue_scripts", 'legal101_addScripts');

/* add Style */
function legal101_addStyle(){
	wp_enqueue_style("sekolahku-style-daterangepicker", plugins_url("/assets/css/daterangepicker.css", __FILE__));
	wp_enqueue_style("sekolahku-style", plugins_url("/assets/css/style.css", __FILE__));
}

/* add Scripts */
function legal101_addScripts(){
	wp_enqueue_script("sekolahku-script-jquery", plugins_url("/assets/js/jquery-3.6.0.min.js", __FILE__), array("jquery"));
	wp_enqueue_script("sekolahku-script-moment", plugins_url("/assets/js/moment.min.js", __FILE__), array("jquery"));
	wp_enqueue_script("sekolahku-script-daterangepicker", plugins_url("/assets/js/daterangepicker.min.js", __FILE__));
	wp_enqueue_script("sekolahku-script", plugins_url("/assets/js/app.js", __FILE__), array("jquery"));
}

/* Add Assets */


/**
 * create administrator menu 
 */

//call action
add_action("admin_menu", 'add_legal101_plugin_cms_menu');

//init function
function add_legal101_plugin_cms_menu(){
    add_menu_page("Legal 101", "Legal 101", "manage_options", "legal101", "legal101u_page", "dashicons-welcome-learn-more", 6);

    /* Users */
    add_submenu_page("legal101", "Users", "Users", "manage_options", "users", "users_page");

	/* Perizinan */
    add_submenu_page(null, "Perizinan", "Perizinan", "manage_options", "perizinan", "perizinan_page");

	/* HKI */
    add_submenu_page(null, "HKI", "HKI", "manage_options", "hki", "hki_page");

	/* HKI Dokumen */
    add_submenu_page(null, "HKI Dokumen", "HKI Dokumen", "manage_options", "hki_documents", "hki_documents_page");

	/* Faktur */
    add_submenu_page(null, "Faktur", "Faktur", "manage_options", "faktur", "faktur_page");

	/* PPN */
    add_submenu_page(null, "PPN", "PPN", "manage_options", "ppn", "ppn_page");

	/* SPT */
    add_submenu_page(null, "SPT Tahunan", "SPT Tahunan", "manage_options", "spt", "spt_page");
}

/**
 * create administrator menu 
 */


function users_page(){
    include_once( __DIR__ . '/pages/cms/users.php' );
}

function perizinan_page(){
    include_once( __DIR__ . '/pages/cms/perizinan.php' );
}

function hki_page(){
    include_once( __DIR__ . '/pages/cms/hki.php' );
}

function hki_documents_page(){
    include_once( __DIR__ . '/pages/cms/hki_documents.php' );
}

function faktur_page(){
    include_once( __DIR__ . '/pages/cms/faktur.php' );
}

function ppn_page(){
    include_once( __DIR__ . '/pages/cms/ppn.php' );
}

function spt_page(){
    include_once( __DIR__ . '/pages/cms/spt.php' );
}