<?php
$my_plugin = plugin_dir_url('')._plugin_name;
?>

<div class="container mt-5 mb-5">
    <div class="row mt-5">
        <div class="col-12 col-sm-8 col-md-6 col-lg-4 offset-sm-2 offset-md-3 offset-lg-4">
            <div id="login_errors" style="display:none" class="row mt-3">
                <div class="col-sm-12">
                    <div class="alert alert-danger">
                    <ul id="login_errors_message"></ul>
                    </div>
                </div>
            </div>
            <form id="legal101_admin_login_form" class="login-box" method="post">
                <div class="text-center">
                    <img src="" />
                    <h1>Admin Account</h1>
                    <div class="mt-4">Not an Admin?</div>
                    <div><a href="<?php echo site_url('/')._pages_login ?>" class="text-black">Click Here</a></div>
                </div>
                <div class="input-group mb-4 mt-4">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-envelope"></i></span>
                    <input type="email" autocomplete="off" name="email_address" class="form-control" placeholder="Alamat Email" aria-label="Alamat Email" aria-describedby="basic-addon1" required>
                </div>
                <div class="input-group mb-4">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-lg btn-danger btn-round">MASUK</button>
                </div>
                <div class="text-center mt-4">
                    <a href="#" class="text-muted">Contact us if you forgot your password</a>
                </div>
            </form>
        </div>
    </div>
</div>