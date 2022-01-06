<?php
$my_plugin = plugin_dir_url('')._plugin_name;
?>

<div class="container mt-5 mb-5">
    <div class="row mt-5 align-items-center">
        <div class="col-12 col-sm-12 col-md-6 col-lg-7 col-xl-8">
            <?php if ( is_active_sidebar( 'legal101-login-left-content' ) ) { ?>
                <?php dynamic_sidebar('legal101-login-left-content'); ?>
            <?php } ?>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-5 col-xl-4">
            <div id="login_errors" style="display:none" class="row mt-3">
                <div class="col-sm-12">
                    <div class="alert alert-danger">
                    <ul id="login_errors_message"></ul>
                    </div>
                </div>
            </div>
            <form id="legal101_login_form" class="login-box mb-5" method="post">
                <?php if ( is_active_sidebar( 'legal101-login-icon' ) ) { ?>
                    <?php dynamic_sidebar('legal101-login-icon'); ?>
                <?php } ?>
                <div class="text-center">
                    <h1>Client Account</h1>
                    <div class="mt-4">Not a Client?</div>
                    <div><a href="<?php echo site_url('/')._admin_pages_login ?>" class="text-black">Click Here</a></div>
                </div>
                <div class="input-group mb-4 mt-4">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-envelope"></i></span>
                    <input type="email" autocomplete="off" name="email_address" class="form-control bg-dark bg-opacity-10" placeholder="Alamat Email" aria-label="Alamat Email" aria-describedby="basic-addon1" required>
                </div>
                <div class="input-group mb-4">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span>
                    <input type="password" name="password" class="form-control bg-dark bg-opacity-10" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-lg btn-danger btn-round">Log in</button>
                </div>
                <div class="text-center mt-4">
                    <?php if ( is_active_sidebar( 'legal101-contact-us' ) ) { ?>
                        <?php dynamic_sidebar('legal101-contact-us'); ?>
                    <?php } ?>
                </div>
            </form>
            <?php if ( is_active_sidebar( 'legal101-social-icon' ) ) { ?>
                <?php dynamic_sidebar('legal101-social-icon'); ?>
            <?php } ?>
        </div>
    </div>
</div>