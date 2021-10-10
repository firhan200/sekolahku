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
	$table_mata_pelajaran_kelas = $wpdb->prefix . $plugin_name . "_matapelajaran_kelas"; 

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
		kelas_id mediumint(9) NOT NULL,
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
}

/* create administrator menu */

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

/* Main Page */

?>