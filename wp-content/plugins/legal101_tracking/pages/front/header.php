<?php
$my_plugin = plugin_dir_url('').'/'._plugin_name;

$link = get_site_url().'/';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Legal101 Tracking System</title>

    <link href="<?php echo $my_plugin; ?>/assets/plugins/bootstrap-5.1.3/css/bootstrap.min.css" rel="stylesheet"></link>
    <link href="<?php echo $my_plugin; ?>/assets/plugins/fontawesome-free-5.15.4-web/css/all.css" rel="stylesheet"></link>
    <link href="<?php echo $my_plugin; ?>/assets/css/fe-style.css" rel="stylesheet"></link>

    <?php 
    wp_head(); 
    ?>
</head>
<body data-plugin-name="<?php echo _plugin_name ?>">
    <input type="hidden" id="host_url" value="<?php echo get_site_url(); ?>"/>    
