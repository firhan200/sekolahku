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
                $ujian_ids = "";

                $list_ujian = $wpdb->get_results('SELECT u.*, p.name AS paket_name, k.name AS kelas_name, m.name AS matapelajaran_name FROM '.$wpdb->prefix.'sekolahku_ujian AS u LEFT JOIN '.$wpdb->prefix.'sekolahku_paket AS p ON u.paket_id=p.id LEFT JOIN '.$wpdb->prefix.'sekolahku_kelas AS k ON u.kelas_id=k.id LEFT JOIN '.$wpdb->prefix.'sekolahku_matapelajaran AS m ON p.matapelajaran_id=m.id WHERE u.kelas_id IN ('.$_SESSION[SESSION_KELAS_IDS].') ORDER BY u.id DESC LIMIT 0,4');

                //get list ujian ids
                foreach($list_ujian as $ujian) {
                    $ujian_ids .= $ujian->id.',';
                }
                if($ujian_ids != "") {
                    $ujian_ids = substr($ujian_ids, 0, -1);
                }

                $list_ujian_pengguna = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'sekolahku_ujian_pengguna AS up WHERE up.pengguna_id='.$_SESSION[SESSION_ID].' AND up.ujian_id IN ('.$ujian_ids.')');

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
                                </div>
                                <div class="col-6 text-end">
                                    <?php
                                    $is_started = false;
                                    foreach($list_ujian_pengguna as $ujian_pengguna){
                                        if($ujian_pengguna->ujian_id == $ujian->id){
                                            $is_started = true;
                                            $ujian_status = $ujian_pengguna->status;
                                            $score = $ujian_pengguna->score;
                                            $ujian_end_date = $ujian_pengguna->end_date;
                                            $ujian_start_date = $ujian_pengguna->start_date;
                                            break;
                                        }
                                    }
                                    ?>
                                    <?php if(!$is_started){ ?>
                                        <?php 
                                        if($status == UJIAN_SEDANG_BERLANGSUNG){
                                            if($ujian_status == NULL){
                                                echo '<a href="'.$quiz_link.$ujian->id.'" class="btn btn-default btn-sm"><i class="fa fa-pen"></i>&nbsp;Kerjakan</a>';
                                            }else{
                                                if($ujian_status == QUIZ_ONGOING){
                                                    echo '<a href="'.$quiz_link.$ujian->id.'" class="btn btn-default btn-sm"><i class="fa fa-redo-alt"></i>&nbsp;Lanjutkan</a>';
                                                    echo '<div class="fs-6 fw-light t-sm">Tersisa: <strong><span class="quiz_timer_state" data-start-date="'.$current_time.'" data-end-date="'.(date("Y-m-d H:i:s", strtotime($ujian_start_date) + $ujian->duration_seconds)).'">-</span></strong></div>';
                                                }
                                            }
                                        }else if($status == UJIAN_BELUM_DIMULAI){ 
                                            echo '<a href="#" class="btn btn-default btn-sm"><i class="fa fa-clock"></i>&nbsp;Belum Dimulai</a>';
                                        }else if($status == UJIAN_SUDAH_BERAKHIR){
                                            echo '<a href="#" class="btn btn-default btn-sm"><i class="fa fa-check"></i>&nbsp;Selesai</a>';
                                        } 
                                        ?>
                                    <?php }else{ ?>
                                        <?php if($ujian_status == QUIZ_FINISHED){ ?>
                                            <?php echo $score; ?>
                                            <div class="fs-6 t-sm">
                                                <a href="<?php echo $quiz_link.$ujian->id; ?>" href="link-dark">Pembahasan <i class="fa fa-chevron-right"></i></a>
                                            </div>
                                        <?php }else{
                                             echo '<a href="'.$quiz_link.$ujian->id.'" class="btn btn-default btn-sm"><i class="fa fa-redo-alt"></i>&nbsp;Lanjutkan</a>';
                                             echo '<div class="fs-6 fw-light t-sm">Tersisa: <strong><span class="quiz_timer_state" data-start-date="'.$current_time.'" data-end-date="'.(date("Y-m-d H:i:s", strtotime($ujian_start_date) + $ujian->duration_seconds)).'">-</span></strong></div>';
                                        } ?>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-7">
                                    <?php if($ujian->ujian_status == QUIZ_ONGOING){ ?>
                                        <?php if($status == UJIAN_BELUM_DIMULAI){ ?>
                                            <div class="fs-6 fw-light t-sm">Mulai <span class="to_ago" data-from="<?php echo $current_time; ?>" data-to="<?php echo $ujian->start_date; ?>">-</span></div>
                                        <?php }else{ ?>
                                            <div class="fs-6 fw-light t-sm">Berakhir <span class="to_ago" data-from="<?php echo $current_time; ?>" data-to="<?php echo $ujian->end_date; ?>">-</span></div>
                                        <?php } ?>
                                    <?php }else{ ?>
                                        <div class="fs-6 fw-light t-sm">Dikerjakan <span class="to_ago" data-from="<?php echo $current_time; ?>" data-to="<?php echo $ujian->ujian_end_date; ?>">-</span></div>
                                    <?php } ?>
                                </div>
                                <div class="col-5">
                                    <div class="t-xs mt-1 text-end">
                                        <i class="fa fa-clock"></i>&nbsp;<?php echo ($ujian->duration_seconds) / 60; ?> Menit
                                    </div>
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