<?php
$my_plugin = plugin_dir_url('').'sekolahku';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sekolahku</title>

    <link href="<?php echo $my_plugin; ?>/assets/plugins/bootstrap-5.1.3/css/bootstrap.min.css" rel="stylesheet"></link>
    <link href="<?php echo $my_plugin; ?>/assets/plugins/fontawesome-free-5.15.4-web/css/all.css" rel="stylesheet"></link>
    <link href="<?php echo $my_plugin; ?>/assets/css/fe-style.css" rel="stylesheet"></link>
</head>
<body>
    <input type="hidden" id="host_url" value="<?php echo get_site_url(); ?>"/>    

    <?php
    if ( ! session_id() ) {
        session_start();
    }

    //check if login
    if(isset($_SESSION[SESSION_ID])){
        ?>
        <!-- logged in header -->

        <!-- logged in header -->
        <?php
    }
    ?>
