<?php
global $wpdb;
$menu_hki = true;

//get profile
$list_hki = $wpdb->get_results("SELECT h.* FROM "._tbl_hki." AS h WHERE h.user_id = '".$_SESSION[SESSION_ID]."'");
$list_hki_dokumen = $wpdb->get_results("SELECT hd.* FROM "._tbl_hki_documents." AS hd LEFT JOIN "._tbl_hki." AS h ON hd.hki_id=h.id WHERE h.user_id = '".$_SESSION[SESSION_ID]."'");
?>

<div class="container main-container">
    <div class="row">
        <div class="col-12">
            <h2 class="text-center mb-3 section-heading">Project Status HKI</h2>
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-3">
                    <thead>
                        <tr>
                            <th>Pemohon</th>
                            <th>Pekerjaan</th>
                            <th>No Agenda</th>
                            <th>Class</th>
                            <th>Tanggal Penerimaan</th>
                            <th>Status</th>
                            <th>Deadline</th>
                            <th>Doc</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($list_hki as $hki){
                            echo '<tr>';
                            echo '<td>'.htmlspecialchars($hki->pemohon).'</td>';
                            echo '<td>'.htmlspecialchars($hki->pekerjaan).'</td>';
                            echo '<td>'.htmlspecialchars($hki->no_agenda).'</td>';
                            echo '<td>'.htmlspecialchars($hki->class).'</td>';
                            echo '<td>'.date("d/m/Y", strtotime($hki->tanggal_penerimaan)).'</td>';
                            echo '<td>'.htmlspecialchars($hki->status).'</td>';
                            echo '<td>'.date("d/m/Y", strtotime($hki->deadline)).'</td>';
                            echo '<td><a href="#" data-bs-toggle="modal" data-bs-target="#hki_dokumen_'.$hki->id.'">Lihat Dokumen</a></td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <strong>Minuta:</strong>
            <div class="bg-light p-4">
                ??
            </div>
        </div>
    </div>
</div>

<?php
foreach($list_hki as $hki){
?>
    <div class="modal fade" id="hki_dokumen_<?php echo $hki->id ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Dokumen <?php echo $hki->pemohon.' '.$hki->pekerjaan ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Dokumen</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($list_hki_dokumen as $hki_dokumen){
                            if($hki_dokumen->hki_id == $hki->id){
                                echo '<tr>';
                                echo '<td>'.date('d M Y', strtotime($hki_dokumen->created_on)).'</td>';
                                echo '<td><a href="'.wp_get_attachment_url($hki_dokumen->attachment_id).'" target="_blank">'.wp_get_attachment_url($hki_dokumen->attachment_id).'</a></td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>