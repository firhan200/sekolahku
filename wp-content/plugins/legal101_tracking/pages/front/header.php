<?php
$my_plugin = plugin_dir_url('').'/'._plugin_name;

$link = get_site_url().'/';

if ( ! session_id() ) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Legal101 Tracking System</title>

    <?php 
    wp_head(); 

    $bodyStyle = '';
    if(!isset($_SESSION[SESSION_ID])){
        $bodyStyle = "background:url('".$my_plugin."/assets/img/bg.jpg') rgba(0, 0, 0, 0.6);background-size:cover;background-blend-mode: multiply;";
    }
    ?>

    <link href="<?php echo $my_plugin; ?>/assets/plugins/bootstrap-5.1.3/css/bootstrap.min.css" rel="stylesheet"></link>
    <link href="<?php echo $my_plugin; ?>/assets/plugins/fontawesome-free-5.15.4-web/css/all.css" rel="stylesheet"></link>
    <link href="<?php echo $my_plugin; ?>/assets/css/fe-style.css" rel="stylesheet"></link>
    <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300&display=swap" rel="stylesheet">
</head>
<body data-plugin-name="<?php echo _plugin_name ?>" style="<?php echo $bodyStyle ?>">
    <input type="hidden" id="host_url" value="<?php echo get_site_url(); ?>"/>    

    <?php
    //check if login
    if(!isset($_SESSION[SESSION_ID])){
        get_header();
        echo '<div style="height:100px !important"></div>';
    }
    ?>
