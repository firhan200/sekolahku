<?php
global $wpdb;
$menu_faktur = true;

//get profile
$list_faktur = $wpdb->get_results("SELECT * FROM "._tbl_faktur." WHERE user_id = '".$_SESSION[SESSION_ID]."'");
$list_ppn = $wpdb->get_results("SELECT * FROM "._tbl_ppn." WHERE user_id = '".$_SESSION[SESSION_ID]."' ORDER BY tahun_pajak, bulan_pajak DESC");
$list_spt = $wpdb->get_results("SELECT * FROM "._tbl_spt_tahunan." WHERE user_id = '".$_SESSION[SESSION_ID]."' ORDER BY tahun DESC");


//extra function
function intToMonth($int){
    $month = null;
    switch($int){
        case 1:
            $month = 'Januari';
            break;
        case 2:
            $month = 'Februari';
            break;
        case 3:
            $month = 'Maret';
            break;
        case 4:
            $month = 'April';
            break;
        case 5:
            $month = 'Mei';
            break;
        case 6:
            $month = 'Juni';
            break;
        case 7:
            $month = 'Juli';
            break;
        case 8:
            $month = 'Agustus';
            break;
        case 9:
            $month = 'September';
            break;
        case 10:
            $month = 'Oktober';
            break;
        case 11:
            $month = 'November';
            break;
        case 12:
            $month = 'Desember';
            break;
    }
    return $month;
}
?>

<div class="container main-container">
    <div class="row">
        <div class="col-12">
            <h2 class="text-center mb-3">Perpajakan</h2>
            <table class="table table-hover table-bordered mb-3">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>No. Faktur</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($list_faktur as $faktur){
                        echo '<tr>';
                        echo '<td>'.date("d/m/Y", strtotime($faktur->tanggal_faktur)).'</td>';
                        echo '<td>'.htmlspecialchars($faktur->nomor_faktur).'</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>

            <h4 class="mb-3">1. Laporan PPN</h4>
            <table class="table table-hover table-bordered mb-3">
                <thead>
                    <tr>
                        <th>Masa Pajak</th>
                        <th>Status Pelaporan</th>
                        <th>Doc</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($list_ppn as $ppn){
                        echo '<tr>';
                        echo '<td>'.intToMonth($ppn->bulan_pajak).' '.$ppn->tahun_pajak.'</td>';
                        echo '<td>';

                        if($ppn->status == PERIZINAN_PENDING){
                            echo '<span class="badge bg-secondary">Pending</span>';
                        }
                        else if($ppn->status == PERIZINAN_ON_PROGRESS){
                            echo '<span class="badge bg-warning">On-Progress</span>';
                        }
                        else if($ppn->status == PERIZINAN_DONE){
                            echo '<span class="badge bg-success">Done</span>';
                        }
                        else if($ppn->status == PERIZINAN_CANCELLED){
                            echo '<span class="badge bg-danger">Cancelled</span>';
                        }

                        echo '</td>';
                        echo '<td><a href="'.wp_get_attachment_url($ppn->attachment_id).'" target="_blank">'.wp_get_attachment_url($ppn->attachment_id).'</a></td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>

            <h4 class="mb-3">1. Laporan SPT Tahunan</h4>
            <table class="table table-hover table-bordered mb-3">
                <thead>
                    <tr>
                        <th>Pajak Tahunan</th>
                        <th>Status Pelaporan</th>
                        <th>Doc</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($list_spt as $spt){
                        echo '<tr>';
                        echo '<td>'.$spt->tahun.'</td>';
                        echo '<td>';

                        if($spt->status == PERIZINAN_PENDING){
                            echo '<span class="badge bg-secondary">Pending</span>';
                        }
                        else if($spt->status == PERIZINAN_ON_PROGRESS){
                            echo '<span class="badge bg-warning">On-Progress</span>';
                        }
                        else if($spt->status == PERIZINAN_DONE){
                            echo '<span class="badge bg-success">Done</span>';
                        }
                        else if($spt->status == PERIZINAN_CANCELLED){
                            echo '<span class="badge bg-danger">Cancelled</span>';
                        }

                        echo '</td>';
                        echo '<td><a href="'.wp_get_attachment_url($spt->attachment_id).'" target="_blank">'.wp_get_attachment_url($spt->attachment_id).'</a></td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>