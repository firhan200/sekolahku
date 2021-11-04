<?php
global $wpdb;
$my_plugin = plugin_dir_url('').'sekolahku';

$menu_history = true;
$current_time = $wpdb->get_var("SELECT NOW()");

if ( ! session_id() ) {
    session_start();
}
?>

<div class="container mb-5">
    <div class="row mb-2">
        <div class="col-12">
            <a href="<?php echo get_site_url().'/sekolahku-dashboard'; ?>" class="link-dark ms-2"><h4><i class="fa fa-arrow-left"></i></h4></a>
        </div>
    </div>
    <h4 class="fw-light mb-4">
        Riwayat Ujian
    </h4>
</div>