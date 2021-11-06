<?php
global $wpdb;
$table_pengguna = $wpdb->prefix . 'sekolahku_pengguna';
$table_paket = $wpdb->prefix . 'sekolahku_paket';
$table_ujian = $wpdb->prefix . 'sekolahku_ujian';
$table_kelas = $wpdb->prefix . 'sekolahku_kelas';
$table_paket_soal = $wpdb->prefix . 'sekolahku_paket_soal';
$table_soal = $wpdb->prefix . 'sekolahku_soal';
$table_soal_pilihan = $wpdb->prefix . 'sekolahku_soal_pilihan';
$table_name = $wpdb->prefix . 'sekolahku_ujian_pengguna';
$table_ujian_pengguna_jawaban = $wpdb->prefix . 'sekolahku_ujian_pengguna_jawaban';

//var
$is_add = false;
$is_edit = false;
$is_list = false;
$is_delete = false;
$is_bulk_delete = false;
$success = [];
$errors = [];
$is_lock = 1;

//get parent id
$parent_id = $_GET['ujian_id'];
if($parent_id == null){
    $errors[] = 'Ujian tidak ditemukan';
}else{
    $ujian = $wpdb->get_row("SELECT u.*, p.name AS paket_name, k.name AS kelas_name FROM $table_ujian AS u LEFT JOIN $table_paket AS p ON u.paket_id=p.id LEFT JOIN $table_kelas AS k ON k.id=u.kelas_id WHERE u.id = $parent_id");
    if($ujian == null){
        $errors[] = 'Ujian tidak ditemukan';
    }
}

//get page to show
$action_type = $_GET['action_type'];

if($_POST['action_type_val'] != null){
    $action_type_val = $_POST['action_type_val'];
    if($action_type_val == 'bulk_delete'){
        $action_type = $action_type_val;
    }
}

//set label
$action_label = "Hasil Ujian";
if(count($errors) < 1){
    $action_label .= " (".$ujian->paket_name." - ".$ujian->kelas_name.")";
}
if($action_type != null){
    $soal_id = null;
    $is_active = 1;

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
            $soal_id = $data->soal_id;
            $is_active = $data->is_active;
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
$modul_name = 'ujian_pengguna';
$list_url = admin_url('/admin.php?page='.$modul_name.'&ujian_id='.$ujian->id);
$pengguna_url = admin_url('/admin.php?page=pengguna&action_type=edit&id=');
$add_url = $list_url.'&action_type=add';
$edit_url = $list_url.'&action_type=edit&id=';
$delete_url = $list_url.'&action_type=delete&id=';

//check if submit
if(count($errors) < 1){
    if($_POST['submit']){
        $soal_id = $_POST['soal_id'];
        $paket_id = $_POST['paket_id'];
        $is_active = $_POST['is_active'] == 'on' ? 1 : 0;

        //validation
        if(empty($soal_id)){
            $errors[] = '<b>Soal</b> Tidak Boleh Kosong';
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
                        'soal_id' => $soal_id,
                        'is_active' => $is_active
                    ),
                    array(
                        '%d',
                        '%d',
                        '%d'
                    )
                );
            }else if($is_edit){
                //update into wpdb database
                $wpdb->update(
                    $table_name,
                    array(
                        'soal_id' => $soal_id,
                        'is_active' => $is_active
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
        foreach($posts_to_delete as $key => $postId){
            if($key == count($posts_to_delete) - 1){
                $ids .= $postId;
            }else{
                $ids .= $postId.',';
            }
        }

        if($ids != null){
            //delete from wpdb database
            $wpdb->query("DELETE FROM ".$table_name." WHERE id IN (".$ids.")");

            //success
            $success[] = 'Data berhasil dihapus';

            //show list
            $is_list = true;

            echo "<script>window.history.pushState('page2', 'Title', '".$list_url."');</script>";
        }
    }
}
?>

<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo $action_label; ?>
    </h1>

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
$query = "SELECT up.*, p.full_name as pengguna_name, p.id as pengguna_id FROM $table_name AS up LEFT JOIN $table_pengguna AS p ON up.pengguna_id=p.id WHERE up.ujian_id=".$ujian->id;

$keyword = '';

//filter
if($_POST['keyword']){
    $keyword = $_POST['keyword'];
    $query .= " AND p.full_name LIKE '%".$keyword."%'";
}

//order by query
$query .= " ORDER BY up.id DESC";

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
?>

<form action="<?php echo $list_url ?>" method="post">
    <input type="hidden" name="page" value="<?php echo $modul_name; ?>"/>

    <p class="search-box">
        <label class="screen-reader-text" for="post-search-input">Cari:</label>
        <input type="search" id="post-search-input" name="keyword" value="<?php echo $keyword; ?>">
		<input type="submit" id="search-submit" class="button" value="search">
    </p>

    <table class="wp-list-table widefat fixed striped table-view-list posts">
        <thead>
            <tr>
                <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Siswa</span>
                    </a>
                </th>
                <th scope="col" id="description" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Score</span>
                    </a>
                </th>
                <th scope="col" id="description" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Waktu Mulai</span>
                    </a>
                </th>
                <th scope="col" id="description" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Waktu Selesai</span>
                    </a>
                </th>
                <th scope="col" id="description" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Lama Pengerjaan</span>
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
            <?php foreach($list_of_data as $key => $data){ ?>
            <tr id="post-<?php echo $key+1; ?>" class="type-post">
                <td class="title column-title has-row-actions column-primary page-name">
                    <?php echo '<a href="'.$pengguna_url.$data->pengguna_id.'">'.$data->pengguna_name.'</a>'; ?>

                    <div class="row-actions action_container">
                        <span class="edit">
                            <a href="<?php echo $edit_url.$data->id ?>" aria-label="Edit">
                                Lihat Detil
                            </a> 
                        </span>
                    </div>
                </td>
                <td class="column-status" data-colname="status">
                    <?php 
                       echo $data->score;
                    ?>
                </td>
                <td class="column-status" data-colname="status">
                    <?php 
                       echo date("H:i:s, d-m-Y", strtotime($data->start_date));
                    ?>
                </td>
                <td class="column-status" data-colname="status">
                    <?php 
                        if($data->end_date != null){
                            echo date("H:i:s, d-m-Y", strtotime($data->end_date));
                        }
                    ?>
                </td>
                <td class="column-status" data-colname="status">
                    <?php 
                        if($data->end_date != null){
                            echo '<div class="duration" data-from="'.date("Y-m-d H:i:s", strtotime($data->start_date)).'" data-to="'.date("Y-m-d H:i:s", strtotime($data->end_date)).'"></div>';
                        }
                    ?>
                </td>
                <td class="column-status" data-colname="status">
                    <?php 
                        if($data->status == QUIZ_FINISHED){ 
                            echo '<span class="badge bg-success">Selesai</span>';
                        }else{
                            echo '<span class="badge bg-light">Belum Selesai</span>';
                        }
                    ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th scope="col" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Siswa</span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Score</span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Waktu Mulai</span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Waktu Selesai</span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Lama Pengerjaan</span>
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
    $questions = $wpdb->get_results("SELECT s.* FROM $table_paket_soal AS ps LEFT JOIN $table_soal AS s ON ps.soal_id=s.id WHERE ps.paket_id=$ujian->paket_id");

    //get all question ids
    $question_ids = '';
    foreach($questions as $question){
        $question_ids .= $question->id.',';
    }
    if($question_ids != ''){
        $question_ids = substr($question_ids, 0, -1);
    }

    //get question options
    $options = $wpdb->get_results("SELECT * FROM $table_soal_pilihan WHERE soal_id IN ($question_ids)");

    //get user answer
    $answers = $wpdb->get_results("SELECT * FROM $table_ujian_pengguna_jawaban AS upj LEFT JOIN $table_name AS up ON upj.ujian_pengguna_id=up.id WHERE up.id=$id");

    $user = $wpdb->get_row("SELECT up.*, p.full_name as pengguna_name FROM $table_name AS up LEFT JOIN $table_pengguna AS p ON up.pengguna_id=p.id WHERE up.id=$id");
?>
<form method="post">
    <table class="form-table">
        <tbody>
            <tr>
                <th></th>
                <td>
                    <?php echo '<a href="'.$pengguna_url.$user->pengguna_id.'">'.$user->pengguna_name.'</a>'; ?>
                    <br/>
                    Nilai: <b><?php echo $user->score; ?></b>
                    <br/>
                    Mulai Mengerjakan : <b><?php echo date("H:i:s, d-m-Y", strtotime($user->start_date)); ?></b> 
                    <br/>
                    Selesai Mengerjakan : <b><?php echo date("H:i:s, d-m-Y", strtotime($user->end_date)); ?></b> 
                    <br/>
                    <?php 
                        if($data->end_date != null){
                            echo '<span>Lama Pengerjaan :</span>&nbsp;<span class="duration" data-from="'.date("Y-m-d H:i:s", strtotime($data->start_date)).'" data-to="'.date("Y-m-d H:i:s", strtotime($data->end_date)).'"></span>';
                        }
                    ?>
                </td>
            </tr>
            <?php
            foreach($questions as $number => $question){
            ?>
            <tr>
                <th><?php echo ($number + 1).'.'; ?></th>
                <td>
                    <?php echo $question->question; ?>
                    <br/>
                    <b>Jawaban:</b>
                    <br/>
                    <?php 
                    foreach($options as $option){ 
                        if($option->soal_id == $question->id){
                            $isUserAnswer = false;
                            foreach($answers as $answer){
                                if($answer->soal_pilihan_id == $option->id){
                                    $isUserAnswer = true;
                                }
                            }

                            echo '<div class="question_option">';
                            echo '<span class="per_answer '.($option->score > 0 ? 'correct' : '').'"><input type="radio" value="'.$option->id.'" '.($isUserAnswer ? 'checked' : '').' disabled> '.$option->label.'</span>';
                            echo '</div>';
                        }
                    }
                    ?>
                </td>
            </tr>
            <?php } ?>
            <tr>
                <td>
                </td>
                <td>
                    <a href="<?php echo $list_url; ?>" class="button button-secondary">Back</a>
                </td>
            </tr>
        </tbody>
    </table>
</form>
<?php
}
?>

</div>