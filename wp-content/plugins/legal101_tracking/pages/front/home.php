<?php
global $wpdb;
$menu_dashboard = true;

//get profile
$profil = $wpdb->get_row("SELECT * FROM "._tbl_users." WHERE id = '".$_SESSION[SESSION_ID]."'");
?>

<div class="container main-container">
    <div class="row">
        <div class="col-12">
            <h2 class="text-center">Profil</h2>
            <div class="row mt-5">
                <div class="col-md-6">
                    <div class="row info-detail">
                        <div class="col-md-6 col-12">
                            <label>Nama Perusahaan</label>
                        </div>
                        <div class="col-md-6 col-12">
                            <?php echo $profil->company_name; ?>
                        </div>
                    </div>
                    <div class="row info-detail">
                        <div class="col-md-6 col-12">
                            <label>Alamat Perusahaan</label>
                        </div>
                        <div class="col-md-6 col-12">
                            <?php echo $profil->company_address; ?>
                        </div>
                    </div>
                    <div class="row info-detail">
                        <div class="col-md-6 col-12">
                            <label>Fax</label>
                        </div>
                        <div class="col-md-6 col-12">
                            <?php echo $profil->fax; ?>
                        </div>
                    </div>
                    <div class="row info-detail">
                        <div class="col-md-6 col-12">
                            <label>No. NPWP Perusahaan</label>
                        </div>
                        <div class="col-md-6 col-12">
                            <?php echo $profil->company_npwp; ?>
                        </div>
                    </div>
                    <div class="row info-detail">
                        <div class="col-md-6 col-12">
                            <label>NIB</label>
                        </div>
                        <div class="col-md-6 col-12">
                            <?php echo $profil->nib; ?>
                        </div>
                    </div>
                    <div class="row info-detail">
                        <div class="col-md-6 col-12">
                            <label>Website</label>
                        </div>
                        <div class="col-md-6 col-12">
                            <?php echo $profil->website; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row info-detail">
                        <div class="col-md-6 col-12">
                            <label>Nama PIC</label>
                        </div>
                        <div class="col-md-6 col-12">
                            <?php echo $profil->pic_name; ?>
                        </div>
                    </div>
                    <div class="row info-detail">
                        <div class="col-md-6 col-12">
                            <label>Phone</label>
                        </div>
                        <div class="col-md-6 col-12">
                            <?php echo $profil->phone; ?>
                        </div>
                    </div>
                    <div class="row info-detail">
                        <div class="col-md-6 col-12">
                            <label>Email</label>
                        </div>
                        <div class="col-md-6 col-12">
                            <?php echo $profil->email_address; ?>
                        </div>
                    </div>
                    <div class="row info-detail">
                        <div class="col-md-6 col-12">
                            <label>Direktur Utama</label>
                        </div>
                        <div class="col-md-6 col-12">
                            <?php echo $profil->main_director; ?>
                        </div>
                    </div>
                    <div class="row info-detail">
                        <div class="col-md-6 col-12">
                            <label>No Identitas</label>
                        </div>
                        <div class="col-md-6 col-12">
                            <?php echo $profil->identity_number; ?>
                        </div>
                    </div>
                    <div class="row info-detail">
                        <div class="col-md-6 col-12">
                            <label>NPWP</label>
                        </div>
                        <div class="col-md-6 col-12">
                            <?php echo $profil->npwp; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>