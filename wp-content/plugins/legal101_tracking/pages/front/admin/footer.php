<?php
$my_plugin = plugin_dir_url('').'/'._plugin_name;
?>
    <?php
    if ( ! session_id() ) {
        session_start();
    }

    //check if login
    if(isset($_SESSION[SESSION_ID]) && !isset($hide_menu)){
        ?>
        <!-- logged in footer -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
            <div class="container bg-light">
                <a class="navbar-brand" href="#"><b style="font-weight:700">LEGAL 101</b> <i>Tracking System</i></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav m-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link <?php echo isset($menu_dashboard) ? 'active' : '' ?>" aria-current="page" href="<?php echo $link._pages_home ?>">Client</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo isset($menu_perizinan) ? 'active' : '' ?>" href="<?php echo $link._pages_perizinan ?>">Perijinan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo isset($menu_hki) ? 'active' : '' ?>" href="<?php echo $link._pages_hki ?>">HKI</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo isset($menu_pajak) ? 'active' : '' ?>" href="<?php echo $link._pages_pajak ?>">Pajak</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link btn legal101_logout_btn" href="#">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- logged in footer -->
        <?php
    }
    ?>

    <script type="text/javascript" src="<?php echo $my_plugin ?>/assets/js/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="<?php echo $my_plugin ?>/assets/plugins/bootstrap-5.1.3/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo $my_plugin ?>/assets/js/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo $my_plugin ?>/assets/js/fe-app.js"></script>
    </body>
</html>