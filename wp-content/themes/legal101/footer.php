
    <div class="container-fluid legal101-footer">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-3">
                    Blok AR Jl. Ruko Modern Land
                    <br/>
                    Kec Tangerang
                    <br/>
                    +62 812-1234-5678
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

    <?php
    wp_footer();
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>