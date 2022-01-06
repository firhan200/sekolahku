<?php
global $wpdb;
$menu_pajak = true;

$selected_faktur_date = date("Y-m");

if($_GET['filter'] != ''){
    $selected_faktur_date = $_GET['filter'];
}

//get profile
$list_faktur = $wpdb->get_results("SELECT * FROM "._tbl_faktur." WHERE user_id = '".$_SESSION[SESSION_ID]."' AND tanggal_faktur LIKE '%".$selected_faktur_date."-%'");
$list_ppn = $wpdb->get_results("SELECT * FROM "._tbl_ppn." WHERE user_id = '".$_SESSION[SESSION_ID]."' ORDER BY tahun_pajak, bulan_pajak DESC");
$list_spt = $wpdb->get_results("SELECT * FROM "._tbl_spt_tahunan." WHERE user_id = '".$_SESSION[SESSION_ID]."' ORDER BY tahun DESC");

//get available years
$available_dates = $wpdb->get_results('SELECT * FROM '._tbl_faktur.' f WHERE tanggal_faktur IS NOT NULL AND user_id = '.$_SESSION[SESSION_ID].' GROUP BY YEAR(f.tanggal_faktur), MONTH(f.tanggal_faktur) ORDER BY tanggal_faktur DESC');

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
<div class="main-header" style="background-image:url('<?php echo $my_plugin; ?>/assets/img/header.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="legal-ts-breadcrumb breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('/')._pages_home ?>">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Perpajakan</li>
                    </ol>
                </nav>
                <h1 class="legal-ts-main-title">Perpajakan</h1>
            </div>
        </div>
    </div>
</div>
<div class="container main-container">
    <div class="row">
        <div class="col-12">
            <div class="row mb-4">
                <div class="col-sm-12 col-lg-2 col-md-3 align-middle">
                    Faktur
                </div>
                <div class="col-sm-12 col-lg-4 col-md-6">
                    <form id="form_filter_faktur" method="GET">
                        <select class="form-select" name="filter" id="filter_faktur">
                            <?php
                            foreach($available_dates as $available_date){
                                $strTime = strtotime($available_date->tanggal_faktur);

                                $isSelected = $selected_faktur_date == date("Y-m", $strTime) ? 'selected' : '';

                                $dateLabel = intToMonth(date('m', $strTime)).' '.date('Y', $strTime);
                                echo '<option value="'.date("Y-m", $strTime).'" '.$isSelected.'>'.$dateLabel.'</option>';
                            }
                            ?>
                        </select>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
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
            </div>

            <div class="mb-5">
                Jumlah Faktur : <?php echo count($list_faktur); ?>
            </div>

            <h4 class="mb-3">1. Laporan PPN</h4>
            <div class="table-responsive">
                <table class="table table-responsive table-hover table-bordered mb-3">
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
                            echo '<td><a href="'.wp_get_attachment_url($ppn->attachment_id).'" target="_blank">Pdf</a></td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <h4 class="mb-3 mt-5">2. Laporan SPT Tahunan</h4>
            <div class="table-responsive">
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
                            echo '<td><a href="'.wp_get_attachment_url($spt->attachment_id).'" target="_blank">Pdf</a></td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>