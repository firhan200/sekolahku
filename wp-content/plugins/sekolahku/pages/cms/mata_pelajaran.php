<?php
$modul_name = 'mata_pelajaran';
$add_url = admin_url('/admin.php?page=tambah_'.$modul_name);
$edit_url = admin_url('/admin.php?page=ubah_'.$modul_name);
?>

<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo get_admin_page_title(); ?>
    </h1>

    <a href="<?php echo $add_url; ?>" class="page-title-action">Tambah Baru</a>

    <hr class="wp-header-end">
</div>