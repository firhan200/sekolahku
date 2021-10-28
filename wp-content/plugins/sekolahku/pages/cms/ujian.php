<?php
global $wpdb;
$table_name = $wpdb->prefix . 'sekolahku_ujian';
$table_paket = $wpdb->prefix . 'sekolahku_paket';
$table_mapel = $wpdb->prefix . 'sekolahku_matapelajaran';
$table_kelas = $wpdb->prefix . 'sekolahku_kelas';

//var
$is_add = false;
$is_edit = false;
$is_list = false;
$is_delete = false;
$is_bulk_delete = false;
$success = [];
$errors = [];

//get page to show
$action_type = $_GET['action_type'];

if($_POST['action_type_val'] != null){
    $action_type_val = $_POST['action_type_val'];
    if($action_type_val == 'bulk_delete'){
        $action_type = $action_type_val;
    }
}

//set label
$action_label = get_admin_page_title();
if($action_type != null){
    $paket_id = null;
    $kelas_id = '';
    $dates = null;
    $duration_seconds = 3600;
    $randomize_questions = 1;

    if($action_type == 'add'){
        $is_add = true;
        $action_label = 'Tambah '.$action_label;
    }else if($action_type == 'edit'){
        $is_edit = true;
        $action_label = 'Ubah '.$action_label;

        //get id
        $id = $_GET['id'];
        $data = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE id = $id");
        if($data != null){
            $paket_id = $data->paket_id;
            $kelas_id = $data->kelas_id;
            $randomize_questions = $data->randomize_questions;
            $duration_seconds = $data->duration_seconds;

            $start_date_data = date("d/m/Y, H:i", strtotime($data->start_date)); 
            $end_date_data = date("d/m/Y, H:i", strtotime($data->end_date)); 
            $dates = $start_date_data.' - '.$end_date_data;
        }else{
            $errors[] = 'Data tidak ditemukan';
        }

    }else if($action_type == 'delete'){
        $is_delete = true;
        //get id
        $id = $_GET['id'];
        $data = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE id = $id");
        if($data == null){
            $errors[] = 'Data tidak ditemukan';
        }
    }else if($action_type == 'bulk_delete'){
        $is_bulk_delete = true;
    }
}else{
    $is_list = true;
}

//get urls
$modul_name = 'ujian';
$list_url = admin_url('/admin.php?page='.$modul_name);
$paket_soal_url = admin_url('/admin.php?page=paket_soal&paket_id=');
$add_url = $list_url.'&action_type=add';
$edit_url = $list_url.'&action_type=edit&id=';
$delete_url = $list_url.'&action_type=delete&id=';

//check if submit
if(count($errors) < 1){
    if($_POST['submit']){
        $paket_id = $_POST['paket_id'];
        $kelas_id = $_POST['kelas_id'];
        $duration_seconds = $_POST['duration_seconds'] * 60; //convert to seconds
        $randomize_questions = $_POST['randomize_questions'] == 'on' ? 1 : 0;
        $dates_new = $_POST['dates'];

        /* PREPARE DATES */       
        //explode daterange into start and end date
        $dates_new = explode("-", $dates_new);
        $start_date = trim($dates_new[0]);
        $end_date = trim($dates_new[1]);

        //start date arr
        $startDateArr = explode(",", $start_date);
        $startTime = $startDateArr[1];
        
        //explode d-m-Y string format
        $start_date_exploded = explode("/", $startDateArr[0]);
        $start_day = trim($start_date_exploded[0]);
        $start_month = trim($start_date_exploded[1]);
        $start_year = trim($start_date_exploded[2]);
        
        //start date arr
        $endDateArr = explode(",", $end_date);
        $endTime = $endDateArr[1];

        $end_date_exploded = explode("/", $endDateArr[0]);
        $end_day = trim($end_date_exploded[0]);
        $end_month = trim($end_date_exploded[1]);
        $end_year = trim($end_date_exploded[2]);
        
        $start_date_val = $start_year."-".$start_month."-".$start_day.' '.$startTime.':00';
        $end_date_val = $end_year."-".$end_month."-".$end_day.' '.$endTime.':59';
        /* PREPARE DATES */

        //validation
        if(empty($paket_id)){
            $errors[] = '<b>Paket</b> Tidak Boleh Kosong';
        }

        if(empty($kelas_id)){
            $errors[] = '<b>Kelas</b> Tidak Boleh Kosong';
        }

        if(empty($dates_new)){
            $errors[] = '<b>Waktu Ujian</b> Tidak Boleh Kosong';
        }

        if(empty($duration_seconds) || $duration_seconds == 0){
            $errors[] = '<b>Durasi Ujian</b> Tidak Boleh Kosong';
        }

        if(count($errors) == 0){
            //all input valid
            //check if edit or add
            if($is_add){
                //insert into wpdb database
                $wpdb->insert(
                    $table_name,
                    array(
                        'paket_id' => $paket_id,
                        'kelas_id' => $kelas_id,
                        'start_date' => $start_date_val,
                        'end_date' => $end_date_val,
                        'randomize_questions' => $randomize_questions,
                        'duration_seconds' => $duration_seconds
                    ),
                    array(
                        '%d',
                        '%d',
                        '%s',
                        '%s',
                        '%d',
                        '%d',
                    )
                );
            }else if($is_edit){
                //update into wpdb database
                $wpdb->update(
                    $table_name,
                    array(
                        'paket_id' => $paket_id,
                        'kelas_id' => $kelas_id,
                        'start_date' => $start_date_val,
                        'end_date' => $end_date_val,
                        'randomize_questions' => $randomize_questions,
                        'duration_seconds' => $duration_seconds
                    ),
                    array('id' => $id)
                );
            }

            //success
            $success[] = 'Data berhasil disimpan';

            //show list
            $is_list = true;

            echo "<script>window.history.pushState('page2', 'Title', '".$list_url."');</script>";
        }
    }

    //check if delete
    if($is_delete){
        //delete from wpdb database
        $wpdb->delete(
            $table_name,
            array('id' => $id)
        );

        //success
        $success[] = 'Data berhasil dihapus';

        //show list
        $is_list = true;

        echo "<script>window.history.pushState('page2', 'Title', '".$list_url."');</script>";
    }

    //check if bulk delete
    if($is_bulk_delete){
        //get id
        $posts_to_delete = $_POST['post'];
        $ids = null;

        if($posts_to_delete != null){
            foreach($posts_to_delete as $key => $postId){
                if($key == count($posts_to_delete) - 1){
                    $ids .= $postId;
                }else{
                    $ids .= $postId.',';
                }
                
                if($postId != null && $postId != ""){
                    //delete relationship
                }
            }
        }

        if($ids != null){
            //delete from wpdb database
            $wpdb->query("DELETE FROM ".$table_name." WHERE id IN (".$ids.")");

            //success
            $success[] = 'Data berhasil dihapus';
        }

        //show list
        $is_list = true;

        echo "<script>window.history.pushState('page2', 'Title', '".$list_url."');</script>";
    }
}
?>

<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo $action_label; ?>
    </h1>

    <?php if($is_list){ ?>
    <a href="<?php echo $add_url; ?>" class="page-title-action">Tambah Baru</a>
    <?php } ?>

    <?php if(count($success) > 0){
        foreach($success as $msg){
            echo '<div class="notice notice-success is-dismissible"><p>'.$msg.'</p></div>';
        }
    } ?>

    <?php if(count($errors) > 0){
        foreach($errors as $msg){
            echo '<div class="notice notice-error is-dismissible"><p>'.$msg.'</p></div>';
        }
    } ?>

    <hr class="wp-header-end">

<?php
if($is_list){
//get list from database
$query = "SELECT u.*, p.name AS paket_name, k.name AS kelas_name FROM ".$table_name." AS u LEFT JOIN ".$table_paket." AS p ON u.paket_id=p.id LEFT JOIN ".$table_kelas." AS k ON u.kelas_id=k.id";
$keyword = '';

//filter
if($_POST['keyword']){
    $keyword = $_POST['keyword'];
    $query .= " WHERE p.name LIKE '%".$keyword."%'";
}

//order by query
$query .= " ORDER BY u.id DESC";

$list_of_data_total = $wpdb->get_results($query);

//limit
$limit = 20;

//get total
$total = count($list_of_data_total);

// How many pages will there be
$pages = ceil($total / $limit);

$page = $_GET['on_page'] == null ? 1 : $_GET['on_page'];

// Calculate the offset for the query
if($page > 1){
    $offset = ($page - 1) * $limit;
}else{
    $offset = 0;
}

// Some information to display to the user
$start = $offset + 1;
$end = min(($offset + $limit), $total);

// The "back" link
$prevlink = ($page > 1) ? '<a href="'.$list_url.'&on_page=1" title="First page" class="next-page button">Awal</a> <a href="'.$list_url.'&on_page=' . ($page - 1) . '" title="Previous page" class="next-page button">Laman Sebelumnya</a>' : '';

// The "forward" link
$nextlink = ($page < $pages) ? '<a href="'.$list_url.'&on_page=' . ($page + 1) . '" class="next-page button" title="Next page">Laman Selanjutnya</a> <a href="'.$list_url.'&on_page=' . $pages . '" title="Last page" class="next-page button">Akhir</a>' : '';

$list_of_data = $wpdb->get_results($query.' LIMIT '.$limit.' OFFSET '.$offset);

$current_time = $wpdb->get_row('SELECT NOW() AS data')->data;
?>

<form action="<?php echo $list_url ?>" method="post">
    <input type="hidden" name="page" value="<?php echo $modul_name; ?>"/>

    <p class="search-box">
        <label class="screen-reader-text" for="post-search-input">Cari:</label>
        <input type="search" id="post-search-input" name="keyword" value="<?php echo $keyword; ?>">
		<input type="submit" id="search-submit" class="button" value="search">
    </p>

    <div class="tablenav top">
        <div class="alignleft actions bulkactions">
            <label for="bulk-action-selector-top" class="screen-reader-text">Pilih tindakan sekaligus</label>
            <select name="action_type_val" id="bulk-action-selector-top">
                <option value="-1">Tindakan Massal</option>
                <option value="bulk_delete">Hapus</option>
            </select>
            <input type="submit" id="doaction" class="button action" value="Terapkan">
        </div>
    </div>

    <table class="wp-list-table widefat fixed striped table-view-list posts">
        <thead>
            <tr>
                <td id="cb" class="manage-column column-cb check-column">
                    <label class="screen-reader-text" for="cb-select-all-1">Pilih Semua</label>
                    <input id="cb-select-all-1" type="checkbox">
                </td>
                <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Paket Soal</span>
                    </a>
                </th>
                <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Kelas</span>
                    </a>
                </th>
                <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Waktu Ujian</span>
                    </a>
                </th>
                <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Acak Soal</span>
                    </a>
                </th>
                <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Status</span>
                    </a>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($list_of_data as $key => $data){

            $status = "";
            if($data->start_date <= $current_time && $data->end_date >= $current_time){ 
                $status = UJIAN_SEDANG_BERLANGSUNG;
            }else if($current_time < $data->start_date){
                $status = UJIAN_BELUM_DIMULAI;
            }else if($current_time > $data->end_date){
                $status = UJIAN_SUDAH_BERAKHIR;
            }    
            ?>
            <tr id="post-<?php echo $key+1; ?>" class="type-post">
                <th scope="row" class="check-column">
                    <?php if(!$data->is_lock){ ?>
                    <label class="screen-reader-text" for="cb-select-1">
                        Pilih <?php echo $data->name; ?>
                    </label>
                    <input id="cb-select-1" type="checkbox" name="post[]" value="<?php echo $data->id; ?>">
                    <div class="locked-indicator">
                        <span class="locked-indicator-icon" aria-hidden="true"></span>
                        <span class="screen-reader-text">
                            “<?php echo $data->name; ?>” terkunci
                        </span>
                    </div>
                    <?php } ?>
                </th>
                <td class="title column-title has-row-actions column-primary page-name">
                    <?php echo '<a href="'.$paket_soal_url.$data->paket_id.'">'.$data->paket_name.'</a>'; ?>

                    <div class="row-actions action_container">
                        <?php if($status != UJIAN_SEDANG_BERLANGSUNG && $status != UJIAN_SUDAH_BERAKHIR){ ?>
                        <span class="edit">
                            <a href="<?php echo $edit_url.$data->id ?>" aria-label="Edit">
                                Edit
                            </a> 
                            | 
                        </span>
                        <?php } ?>
                        <?php if(!$data->is_lock){ ?>
                        <span class="delete">
                            <a href="<?php echo $delete_url.$data->id ?>" onclick="return confirm('Hapus <?php echo $data->name; ?>?')" aria-label="Delete">
                                Delete
                            </a>
                        </span>
                        <?php }else{ ?>
                            <span class="delete">
                            Paket Terkunci
                        </span>
                        <?php } ?>
                    </div>
                </td>
                <td class="column-status" data-colname="status">
                    <?php echo $data->kelas_name; ?>
                </td>
                <td class="column-status" data-colname="status">
                    <?php
                    $start_date = date("H:i, d M y", strtotime($data->start_date));
                    $end_date = date("H:i, d M y", strtotime($data->end_date));
                    echo $start_date.' - '.$end_date;
                    ?>
                </td>
                <td class="column-status" data-colname="status">
                    <?php 
                        if($data->randomize_questions == 1){ 
                            echo '<span class="badge bg-success">Iya</span>';
                        }
                    ?>
                </td>
                <td class="column-status" data-colname="status">
                    <?php 
                       echo $status;
                    ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td class="manage-column column-cb check-column">
                    <label class="screen-reader-text" for="cb-select-all-2">Pilih Semua</label>
                    <input id="cb-select-all-2" type="checkbox">
                </td>
                <th scope="col" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Paket Soal</span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Kelas</span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Waktu Ujian</span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Acak Soal</span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Status</span>
                    </a>
                </th>
            </tr>
        </tfoot>
    </table>
</form>
    <?php
    // Display the paging information
    echo '<div id="paging"><p>', $prevlink, ' Halaman <b>', $page, '</b> dari <b>', $pages, '</b> , Menampilkan <b>', $start, '-', $end, '</b> dari <b>', $total, '</b> Hasil ', $nextlink, ' </p></div>';
    ?>
<?php 
}else if($is_add || $is_edit){
    $list_paket = $wpdb->get_results('SELECT p.*, m.name AS matapelajaran_name FROM '.$table_paket.' AS p LEFT JOIN '.$table_mapel.' AS m ON p.matapelajaran_id=m.id ORDER BY p.name ASC');

    $list_kelas = $wpdb->get_results('SELECT * FROM '.$table_kelas.' ORDER BY name ASC');
?>
<form method="post">
    <input type="hidden" name="submit" value="true"/>
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row"><label for="name">Paket Soal</label></th>
                <td>
                    <?php if(!$is_lock){ ?>
                    <select name="paket_id" required <?php echo $is_lock ? 'readonly' : '' ?>>
                        <option value="">-- Pilih Paket Soal --</option>
                        <?php 
                        foreach($list_paket as $paket){
                            $isSelected = $paket->id == $paket_id ? 'selected' : '';
                            echo '<option value="'.$paket->id.'" '.$isSelected.'>('.$paket->matapelajaran_name.') - '.$paket->name.'</option>';
                        }
                        ?>
                    </select>
                    <?php }else{ ?>
                        <input type="hidden" class="regular-text" name="paket_id" value="<?php echo $matapelajaran_id; ?>" readonly />
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="name">Kelas</label></th>
                <td>
                    <?php if(!$is_lock){ ?>
                    <select name="kelas_id" required <?php echo $is_lock ? 'readonly' : '' ?>>
                        <option value="">-- Pilih Kelas --</option>
                        <?php 
                        foreach($list_kelas as $kelas){
                            $isSelected = $kelas->id == $kelas_id ? 'selected' : '';
                            echo '<option value="'.$kelas->id.'" '.$isSelected.'>'.$kelas->name.'</option>';
                        }
                        ?>
                    </select>
                    <?php }else{ ?>
                        <input type="hidden" class="regular-text" name="kelas_id" value="<?php echo $matapelajaran_id; ?>" readonly />
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="name">Waktu Ujian</label></th>
                <td><input type="text" class="regular-text" name="dates" value="<?php echo $dates; ?>" maxlength="100"/></td>
            </tr>
            <tr>
                <th scope="row"><label for="name">Durasi Ujian (Menit)</label></th>
                <td><input type="number" class="regular-text" name="duration_seconds" value="<?php echo ($duration_seconds / 60); ?>" maxlength="6"/></td>
            </tr>
            <tr>
                <th scope="row"><label for="randomize_questions">Acak Soal</label></th>
                <td><input class="form-control" name="randomize_questions" type="checkbox" <?php echo $randomize_questions==1 ? 'checked' : ''; ?> />&nbsp;Acak Penampilan Soal Saat Pengerjaan Oleh Siswa</td>
            </tr>
            <tr>
                <td>
                </td>
                <td>
                    <a href="<?php echo $list_url; ?>" class="button button-secondary">Back</a>
                    <button type="submit" class="button button-primary">Submit</button>
                </td>
            </tr>
        </tbody>
    </table>
</form>
<?php
}
?>

</div>