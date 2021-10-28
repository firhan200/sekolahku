<?php
$my_plugin = plugin_dir_url('').'sekolahku';

$link = get_site_url();

?>
    <?php
    if ( ! session_id() ) {
        session_start();
    }

    //check if login
    if(isset($_SESSION[SESSION_ID])){
        ?>
        <!-- logged in footer -->
        <div class="container-fluid menu_container">
            <div class="container">
                <div class="row">
                    <div class="col-3 text-center">
                        <a href="<?php echo $link.'/sekolahku-dashboard' ?>" class="menu <?php echo isset($menu_dashboard) ? 'active' : '' ?>">
                            <i class="fa fa-home"></i>
                        </a>
                    </div>
                    <div class="col-3 text-center">
                        <a href="#" class="menu <?php echo isset($menu_quiz) ? 'active' : '' ?>">
                            <i class="fa fa-pen"></i>
                        </a>
                    </div>
                    <div class="col-3 text-center">
                        <a href="#" class="menu">
                            <i class="fa fa-book"></i>
                        </a>
                    </div>
                    <div class="col-3 text-center">
                        <a href="<?php echo $link.'/sekolahku-pengaturan' ?>" class="menu <?php echo isset($menu_setting) ? 'active' : '' ?>">
                            <i class="fa fa-cog"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- logged in footer -->
        <?php
    }
    ?>

    <script type="text/javascript" src="<?php echo $my_plugin ?>/assets/js/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="<?php echo $my_plugin ?>/assets/js/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo $my_plugin ?>/assets/js/fe-app.js"></script>
    </body>
</html>