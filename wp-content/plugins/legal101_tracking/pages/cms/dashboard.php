<link href="<?php echo plugin_dir_url('/')._plugin_name ?>/assets/plugins/bootstrap-5.1.3/css/bootstrap.min.css" rel="stylesheet"></link>


<style>
    body{
        background:#f0f0f1;
    }
    .d-label{
        font-size:13pt;
    }
    .d-val{
        font-size:28pt;
        margin-top:-10px;
    }
</style>

<?php
global $wpdb;

$total_perusahaan = $wpdb->get_var('SELECT COUNT(*) FROM '._tbl_users);
$total_perizinan = $wpdb->get_var('SELECT COUNT(*) FROM '._tbl_perizinan);
$total_hki = $wpdb->get_var('SELECT COUNT(*) FROM '._tbl_hki);
$total_faktur = $wpdb->get_var('SELECT COUNT(*) FROM '._tbl_faktur);
$total_ppn = $wpdb->get_var('SELECT COUNT(*) FROM '._tbl_ppn);
$total_spt = $wpdb->get_var('SELECT COUNT(*) FROM '._tbl_spt_tahunan);

?>

<div class="wrap">
    <h1 class="wp-heading-inline">
        Selamat Datang di Legal101 Tracking System
    </h1>

    <hr class="wp-header-end">

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="box text-end">
                    <p class="d-label">Total Perusahaan</p>
                    <div class="h5 d-val">
                        <?php echo $total_perusahaan; ?>
                    </div>
                    <a href="<?php echo admin_url('/admin.php?page=users'); ?>">Lihat Semua ></a>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="box text-end">
                    <p class="d-label">Total Perizinan</p>
                    <div class="h5 d-val">
                        <?php echo $total_perizinan; ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="box text-end">
                    <p class="d-label">Total HKI</p>
                    <div class="h5 d-val">
                        <?php echo $total_hki; ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="box text-end">
                    <p class="d-label">Total Pajak</p>
                    Faktur: <span class="d-val"><?php echo $total_faktur ?></span>&nbsp;&nbsp;
                    PPN: <span class="d-val"><?php echo $total_ppn ?></span>&nbsp;&nbsp;
                    SPT Tahun: <span class="d-val"><?php echo $total_spt ?></span>
                </div>
            </div>
        </div>
    </div>
</div>