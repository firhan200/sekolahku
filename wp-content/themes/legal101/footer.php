
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
                        OUR PRACTICE
                    </div>
                    <?php
                        wp_nav_menu(
                            array(
                                'menu' => 'our_practice_menu',
                                'container' => '',
                                'theme_location' => 'our_practice_menu',
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
            <div class="row">
                <div class="col-12 text-end">
                    <?php if ( is_active_sidebar( 'legal101-footer-social-media' ) ) { ?>
                        <ul id="sidebar">
                            <?php dynamic_sidebar('legal101-footer-social-media'); ?>
                        </ul>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <?php
    wp_footer();
    ?>

    <div class="float-section">
        <?php if ( is_active_sidebar( 'legal101-floating-section' ) ) { ?>
            <ul id="sidebar">
                <?php dynamic_sidebar('legal101-floating-section'); ?>
            </ul>
        <?php } ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>