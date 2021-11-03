<?php
global $wpdb;
$my_plugin = plugin_dir_url('').'sekolahku';
$quiz_link = get_site_url().'/sekolahku-quiz?id=';

$menu_dashboard = true;
$current_time = $wpdb->get_var("SELECT NOW()");

if ( ! session_id() ) {
    session_start();
}
?>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-12 text-start">
            <div class="display-6">Halo <strong><?php echo $_SESSION[SESSION_NAME]; ?></strong></div>
            <div class="text-muted">Selamat Datang...</div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12 text-start">
            <p>Ujian yang Akan Datang</p>
            <div class="row">
                <?php
                $list_ujian = $wpdb->get_results('SELECT u.*, p.name AS paket_name, k.name AS kelas_name, m.name AS matapelajaran_name, up.status AS ujian_status, up.score AS score, up.end_date AS ujian_end_date FROM '.$wpdb->prefix.'sekolahku_ujian AS u LEFT JOIN '.$wpdb->prefix.'sekolahku_paket AS p ON u.paket_id=p.id LEFT JOIN '.$wpdb->prefix.'sekolahku_kelas AS k ON u.kelas_id=k.id LEFT JOIN '.$wpdb->prefix.'sekolahku_matapelajaran AS m ON p.matapelajaran_id=m.id LEFT JOIN '.$wpdb->prefix.'sekolahku_ujian_pengguna AS up ON up.ujian_id=u.id WHERE u.kelas_id IN ('.$_SESSION[SESSION_KELAS_IDS].') ORDER BY id DESC');
                foreach($list_ujian as $ujian){
                    $status = "";
                    if($ujian->start_date <= $current_time && $ujian->end_date >= $current_time){ 
                        $status = UJIAN_SEDANG_BERLANGSUNG;
                    }else if($current_time < $ujian->start_date){
                        $status = UJIAN_BELUM_DIMULAI;
                    }else if($current_time > $ujian->end_date){
                        $status = UJIAN_SUDAH_BERAKHIR;
                    }  
                    ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="box mb-2">
                            <div class="row">
                                <div class="col-6">
                                    <div class="fs-6">
                                        <b><?php echo $ujian->paket_name; ?></b>
                                    </div>
                                    <div class="t-sm">
                                        <?php echo $ujian->matapelajaran_name.' - '.$ujian->kelas_name; ?>
                                    </div>
                                    <?php if($ujian->ujian_status == QUIZ_ONGOING){ ?>
                                        <?php if($status == UJIAN_BELUM_DIMULAI){ ?>
                                            <div class="fs-6 fw-light t-sm">Mulai <span class="to_ago" data-from="<?php echo $current_time; ?>" data-to="<?php echo $ujian->start_date; ?>">-</span></div>
                                        <?php }else{ ?>
                                            <div class="fs-6 fw-light t-sm">Berakhir <span class="to_ago" data-from="<?php echo $current_time; ?>" data-to="<?php echo $ujian->end_date; ?>">-</span></div>
                                        <?php } ?>
                                    <?php }else{ ?>
                                        <div class="fs-6 fw-light t-sm">Dikerjakan <span class="to_ago" data-from="<?php echo $current_time; ?>" data-to="<?php echo $ujian->ujian_end_date; ?>">-</span></div>
                                    <?php } ?>
                                    <div class="t-xs mt-1">
                                        <i class="fa fa-clock"></i>&nbsp;<?php echo ($ujian->duration_seconds) / 60; ?> Menit
                                    </div>
                                </div>
                                <div class="col-6 text-end">
                                    <?php if($ujian->ujian_status == QUIZ_ONGOING){ ?>
                                    <?php if($status == UJIAN_SEDANG_BERLANGSUNG){ ?>
                                        <a href="<?php echo $quiz_link.$ujian->id; ?>" class="btn btn-default btn-sm"><i class="fa fa-pen"></i>&nbsp;Kerjakan</a>
                                    <?php }else if($status == UJIAN_BELUM_DIMULAI){ ?>
                                        <a href="#" class="btn btn-default btn-sm"><i class="fa fa-clock"></i>&nbsp;Belum Dimulai</a>
                                    <?php }else if($status == UJIAN_SUDAH_BERAKHIR){ ?>
                                        <a href="#" class="btn btn-default btn-sm"><i class="fa fa-check"></i>&nbsp;Selesai</a>
                                    <?php } ?>
                                    <?php }else{ ?>
                                        <?php echo $ujian->score; ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>