<?php
$login_link = get_site_url().'/sekolahku-masuk';

$name = '';
$email_address = '';
$password = '';
$repeat_password = '';
$gender = 1;

//check if submit
if($_POST['submit']){
    
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <a href="<?php echo $login_link; ?>" class="link-dark ms-2"><h4><i class="fa fa-arrow-left"></i></h4></a>
        </div>
    </div>
</div>
<div class="container mt-5 mb-2">
    <div class="row mb-3">
        <div class="col-sm-12 text-center">
            <h1 class="display-6">Ayo Mulai Bergabung!</h1>
            <div class="text-muted">Buat akun dan belajar daring segera!</div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12 col-sm-8 col-md-6 col-lg-4 offset-sm-2 offset-md-3 offset-lg-4">
            <div id="register_errors" style="display:none" class="row mt-3">
                <div class="col-sm-12">
                    <div class="alert alert-danger">
                    <ul id="register_errors_message"></ul>
                    </div>
                </div>
            </div>
            <form id="sekolahku_register_form" method="post">
                <input type="hidden" name="submit" value="true" />
                <div class="input-group mb-4">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>
                    <input type="text" name="name" value="<?php echo $name ?>" class="form-control" placeholder="Name" aria-label="Name" aria-describedby="basic-addon1" required>
                </div>
                <div class="input-group mb-4">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-envelope"></i></span>
                    <input type="email" name="email_address" value="<?php echo $email_address ?>" class="form-control" placeholder="Alamat Email" aria-label="Alamat Email" aria-describedby="basic-addon1" required>
                </div>
                <div class="input-group mb-4">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" required>
                </div>
                <div class="input-group mb-4">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span>
                    <input type="password" name="repeat_password" class="form-control" placeholder="Ulangi Password" aria-label="Password" aria-describedby="basic-addon1" required>
                </div>
                <div class="mb-4">
                    <h6>Jenis Kelamin</h6>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="1" <?php echo $gender == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label" for="inlineRadio2">Laki-Laki</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="2" <?php echo $gender == 2 ? 'checked' : '' ?>>
                        <label class="form-check-label" for="inlineRadio1">Perempuan</label>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-lg btn-default btn-round">DAFTAR</button>
                </div>
            </form>

            <div class="mt-4 text-center">
                <h6>Sudah punya akun? <a href="<?php echo $login_link; ?>" class="link-default">Masuk</a></h6>
            </div>
        </div>
    </div>
</div>