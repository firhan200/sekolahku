<?php
$my_plugin = plugin_dir_url('').'sekolahku';
$register_link = get_site_url().'/sekolahku-daftar';
?>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-6 col-sm-4 col-md-3 mx-auto d-block">
            <img src="<?php echo $my_plugin; ?>/assets/img/login.png" class="img-fluid"/>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-center">
            <h1 class="display-6">Selamat Datang</h1>
            <div class="text-muted">Masuk ke dalam akun anda dengan kombinasi alamat <strong>Email</strong> dan <strong>Password</strong></div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12 col-sm-8 col-md-6 col-lg-4 offset-sm-2 offset-md-3 offset-lg-4">
            <div id="login_errors" style="display:none" class="row mt-3">
                <div class="col-sm-12">
                    <div class="alert alert-danger">
                    <ul id="login_errors_message"></ul>
                    </div>
                </div>
            </div>
            <form id="sekolahku_login_form" method="post">
                <div class="input-group mb-4">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-envelope"></i></span>
                    <input type="email" name="email_address" class="form-control" placeholder="Alamat Email" aria-label="Alamat Email" aria-describedby="basic-addon1" required>
                </div>
                <div class="input-group mb-4">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-lg btn-default btn-round">MASUK</button>
                </div>
            </form>
        </div>
    </div>
</div>