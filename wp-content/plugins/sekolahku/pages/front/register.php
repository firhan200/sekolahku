<?php
$login_link = get_site_url().'/sekolahku-masuk';
?>

<div class="container mt-4 ms-2">
    <div class="row">
        <div class="col-12">
            <a href="<?php echo $login_link; ?>" class="link-dark"><h4><i class="fa fa-arrow-left"></i></h4></a>
        </div>
    </div>
</div>
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-sm-12 text-center">
            <h1 class="display-6">Ayo Mulai Bergabung!</h1>
            <div class="text-muted">Buat akun dan belajar daring segera!</div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12 col-sm-8 col-md-6 col-lg-4 offset-sm-2 offset-md-3 offset-lg-4">
            <form>
                <div class="input-group mb-4">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>
                    <input type="text" class="form-control" placeholder="Name" aria-label="Name" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-4">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-envelope"></i></span>
                    <input type="email" class="form-control" placeholder="Alamat Email" aria-label="Alamat Email" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-4">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span>
                    <input type="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-4">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span>
                    <input type="password" class="form-control" placeholder="Ulangi Password" aria-label="Password" aria-describedby="basic-addon1">
                </div>
                <div class="mb-4">
                    <h6>Jenis Kelamin</h6>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2" checked>
                        <label class="form-check-label" for="inlineRadio2">Laki-Laki</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
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