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
}

register_activation_hook( __FILE__, 'pluginprefix_activate' );
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
	wp_enqueue_style("sekolahku-style", plugins_url("/assets/css/style.css", __FILE__));
}

/* add Scripts */
function addScripts(){
	wp_enqueue_script("sekolahku-script-jquery", plugins_url("/assets/js/jquery-3.6.0.min.js", __FILE__), array("jquery"));
	wp_enqueue_script("sekolahku-script-select2", plugins_url("/assets/js/select2.min.js", __FILE__), array("jquery"));
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

/* Main Page */

?>