<?php get_header() ?>

<div class="legal101-main-container">
    <?php
    if(have_posts()){
        while(have_posts()){
            if (get_post_type() === 'post') {
                // POST
                //set body image
                ?>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $("body").attr('style', "background:url('<?php echo get_template_directory_uri(); ?>/assets/img/bg.jpg') rgba(0, 0, 0, 0.6);background-size:cover;background-blend-mode: multiply;");
                    })
                </script>

                <div class="container legal101-single-post">
                    <div class="row">
                        <div class="col-12">
                            <div class="legal101-single-post-box">
                <?php
            }
            else if (get_post_type() === 'page') {
                // PAGE
            }
            ?>
            
                            <?php
                            the_post();
                            the_content();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
             if (get_post_type() === 'post') {
                // POST
                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            else if (get_post_type() === 'page') {
                // PAGE
            }
        }
    }
    ?>
</div>

<?php get_footer() ?>