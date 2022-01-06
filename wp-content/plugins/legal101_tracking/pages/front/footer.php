<?php
$my_plugin = plugin_dir_url('').'/'._plugin_name;

?>

    <?php
    //check if login
    if(isset($_SESSION[SESSION_ID]) && !isset($hide_menu)){
    ?>
        <nav class="legal101-navbar navbar navbar-expand-lg navbar-dark fixed-top">
            <div class="container">
                <?php
                $custom_logo_id = get_theme_mod( 'custom_logo' );
                $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
                
                if ( has_custom_logo() ) {
                    echo '<a class="navbar-brand" href="'.site_url('/').'"><img height="40" src="' . esc_url( $logo[0] ) . '" alt="' . get_bloginfo( 'name' ) . '"></a>';
                } else {
                    echo '<h1>' . get_bloginfo('name') . '</h1>';
                }
                ?>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="legal101-left-nav navbar-nav m-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link <?php echo isset($menu_dashboard) ? 'active' : '' ?>" aria-current="page" href="<?php echo $link._pages_home ?>">Profil</a>
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
                    <ul class="legal101-right-nav navbar-nav ml-auto mb-2 mb-lg-0">
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

        <div class="container-fluid legal101-footer">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-3">
                        <?php
                        $custom_logo_id = get_theme_mod( 'custom_logo' );
                        $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
                        
                        if ( has_custom_logo() ) {
                            echo '<a class="navbar-brand" href="'.site_url('/').'"><img height="50" src="' . esc_url( $logo[0] ) . '" alt="' . get_bloginfo( 'name' ) . '"></a>';
                        } else {
                            echo '<h1>' . get_bloginfo('name') . '</h1>';
                        }
                        ?>
                        <br/>
                        <br/>
                        <?php if ( is_active_sidebar( 'company-information-footer' ) ) { ?>
                            <ul id="sidebar">
                                <?php dynamic_sidebar('company-information-footer'); ?>
                            </ul>
                        <?php } ?>
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <div class="legal101-footer-title">
                            Social Media
                        </div>
                        <?php
                            wp_nav_menu(
                                array(
                                    'menu' => 'social_media_menu',
                                    'container' => '',
                                    'theme_location' => 'social_media_menu',
                                    'items_wrap' => '<ul class="legal101-footer-link">%3$s</ul>',
                                )
                            );
                        ?>
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <div class="legal101-footer-title">
                            Company
                        </div>
                        <?php
                            wp_nav_menu(
                                array(
                                    'menu' => 'company_menu',
                                    'container' => '',
                                    'theme_location' => 'company_menu',
                                    'items_wrap' => '<ul class="legal101-footer-link">%3$s</ul>',
                                )
                            );
                        ?>
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <div class="legal101-footer-title">
                            Services
                        </div>
                        <?php
                            wp_nav_menu(
                                array(
                                    'menu' => 'services_menu',
                                    'container' => '',
                                    'theme_location' => 'services_menu',
                                    'items_wrap' => '<ul class="legal101-footer-link">%3$s</ul>',
                                )
                            );
                        ?>
                    </div>
                </div>
            </div>
        </div>
    

    

    <script type="text/javascript" src="<?php echo $my_plugin ?>/assets/js/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="<?php echo $my_plugin ?>/assets/plugins/bootstrap-5.1.3/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo $my_plugin ?>/assets/js/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo $my_plugin ?>/assets/js/fe-app.js"></script>
    </body>
</html>