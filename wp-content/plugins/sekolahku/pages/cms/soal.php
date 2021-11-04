<?php
global $wpdb;
$table_name = $wpdb->prefix . 'sekolahku_soal';
$table_soal_pilihan = $wpdb->prefix . 'sekolahku_soal_pilihan';

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
    $name = '';
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
            $title = $data->title;
            $question = $data->question;
            $explanation = $data->explanation;
            $question_type = $data->question_type;
            $is_lock = $data->is_lock;
            $is_active = $data->is_active;

            //get options
            $options = $wpdb->get_results("SELECT * FROM ".$table_soal_pilihan." WHERE soal_id = $id");
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
$modul_name = 'soal';
$list_url = admin_url('/admin.php?page='.$modul_name);
$soal_pilihan_url = admin_url('/admin.php?page=bab&soal_id=');
$add_url = $list_url.'&action_type=add';
$edit_url = $list_url.'&action_type=edit&id=';
$delete_url = $list_url.'&action_type=delete&id=';

function insertQuestionOptions($soal_id, $options, $scores){
    global $wpdb;
    $table_soal_pilihan = $wpdb->prefix . 'sekolahku_soal_pilihan';

    //delete all options
    $wpdb->delete($table_soal_pilihan, ['soal_id' => $soal_id]);
    
    foreach($options as $index => $option){
        //get option score
        $score = 0;

        //check if multiple or single correct answer
        if(is_array($scores)){
            if(array_key_exists($index, $scores)){
                $score = 1;
            }
        }else{
            if($scores == $index){
                $score = 1;
            }
        }

        //insert into db
        $wpdb->insert(
            $table_soal_pilihan,
            array(
                'soal_id' => $soal_id,
                'label' => $option,
                'is_active' => 1, //auto active
                'score' => $score
            ),
            array(
                '%d',
                '%s',
                '%d',
                '%d'
            )
        );
    }
}

//check if submit
if(count($errors) < 1){
    if($_POST['submit']){
        if($_POST['lock'] == 'true'){
            //lock question
            $is_lock = 1;

            //update into wpdb database
            $wpdb->update(
                $table_name,
                array(
                    'is_lock' => $is_lock
                ),
                array('id' => $id)
            );

            //success
            $success[] = 'Berhasil Mengunci Soal.';
        }else{
            $title = $_POST['title'];
            $question = $_POST['question'];
            $explanation = $_POST['explanation'];
            $question_type = $_POST['question_type'];
            $is_lock = $_POST['is_lock'] == 'on' ? 1 : 0;
            $is_active = $_POST['is_active'] == 'on' ? 1 : 0;

            $options = $_POST['answer_options'];
            $scores = $_POST['answer_options_score'];

            //validation
            if(empty($title)){
                $errors[] = '<b>Judul Soal</b> Tidak Boleh Kosong';
            }

            if(empty($question)){
                $errors[] = '<b>Soal</b> Tidak Boleh Kosong';
            }

            if(empty($question_type)){
                $errors[] = '<b>Tipe Soal</b> Tidak Boleh Kosong';
            }

            if(count($errors) == 0){
                $soal_id = null;
                //all input valid
                //check if edit or add
                if($is_add){
                    //insert into wpdb database
                    $wpdb->insert(
                        $table_name,
                        array(
                            'title' => $title,
                            'question' => $question,
                            'explanation' => $explanation,
                            'question_type' => $question_type,
                            'is_active' => $is_active,
                            'is_lock' => $is_lock
                        ),
                        array(
                            '%s',
                            '%s',
                            '%d',
                            '%d',
                            '%d',
                        )
                    );

                    $soal_id = $wpdb->insert_id;
                }else if($is_edit){
                    //update into wpdb database
                    $wpdb->update(
                        $table_name,
                        array(
                            'title' => $title,
                            'question' => $question,
                            'explanation' => $explanation,
                            'question_type' => $question_type,
                            'is_active' => $is_active,
                            'is_lock' => $is_lock
                        ),
                        array('id' => $id)
                    );

                    $soal_id = $id;
                }

                //insert question options
                insertQuestionOptions($soal_id, $options, $scores);

                //success
                $success[] = 'Data berhasil disimpan';

                //show list
                $is_list = true;

                echo "<script>window.history.pushState('page2', 'Title', '".$list_url."');</script>";
            }else{
                $is_lock = false;
            }
        }
    }else if($_POST['unlock']){
        //update into wpdb database
        $wpdb->update(
            $table_name,
            array(
                'is_lock' => 0 //unlock
            ),
            array('id' => $id)
        );

        //set lock to false
        $is_lock = 0;

        //success
        $success[] = 'Berhasil Membuka Kunci Soal.';
    }

    //check if delete
    if($is_delete){
        //delete from wpdb database
        $wpdb->delete(
            $table_name,
            array('id' => $id)
        );

        //delete relationship
        $wpdb->delete(
            $table_soal_pilihan,
            array('soal_id' => $id)
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
                    $wpdb->delete(
                        $table_soal_pilihan,
                        array('soal_id' => $postId)
                    );
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
$query = "SELECT * FROM ".$table_name;
$keyword = '';

//filter
if($_POST['keyword']){
    $keyword = $_POST['keyword'];
    $query .= " WHERE name LIKE '%".$keyword."%'";
}

//order by query
$query .= " ORDER BY id DESC";

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
                <th scope="col" id="title_h" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Judul Soal</span>
                    </a>
                </th>
                <th scope="col" id="question_type_h" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Tipe</span>
                    </a>
                </th>
                <th scope="col" id="is_lock_h" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Kunci</span>
                    </a>
                </th>
                <th scope="col" id="status_h" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Status</span>
                    </a>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($list_of_data as $key => $data){ ?>
            <tr id="post-<?php echo $key+1; ?>" class="type-post">
                <th scope="row" class="check-column">
                    <?php if(!$data->is_lock){ ?>
                    <label class="screen-reader-text" for="cb-select-1">
                        Pilih <?php echo $data->title; ?>
                    </label>
                    <input id="cb-select-1" type="checkbox" name="post[]" value="<?php echo $data->id; ?>">
                    <div class="locked-indicator">
                        <span class="locked-indicator-icon" aria-hidden="true"></span>
                        <span class="screen-reader-text">
                            “<?php echo $data->title; ?>” terkunci
                        </span>
                    </div>
                    <?php } ?>
                </th>
                <td class="title column-title has-row-actions column-primary page-name">
                    <?php echo '#'.$data->id.' '.$data->title; ?>

                    <div class="row-actions action_container">
                        <span class="edit">
                            <a href="<?php echo $edit_url.$data->id ?>" aria-label="Edit">
                                Edit
                            </a> 
                            | 
                        </span>
                        <?php if(!$data->is_lock){ ?>
                        <span class="delete">
                            <a href="<?php echo $delete_url.$data->id ?>" onclick="return confirm('Hapus <?php echo $data->name; ?>?')" aria-label="Delete">
                                Delete
                            </a>
                        </span>
                        <?php }else{ ?>
                            <span class="delete">
                            Soal Terkunci
                        </span>
                        <?php } ?>
                    </div>
                </td>
                <td class="column-status" data-colname="status">
                    <?php 
                        if($data->question_type == PILIHAN_GANDA){ 
                            echo PILIHAN_GANDA_LABEL;
                        }else if($data->question_type == PILIHAN_GANDA_KOMPLEKS){ 
                            echo PILIHAN_GANDA_KOMPLEKS_LABEL;
                        }
                    ?>
                </td>
                <td class="column-status" data-colname="status">
                    <?php 
                        if($data->is_lock == 1){ 
                            echo '<span class="badge bg-success">Dikunci</span>';
                        }else{
                            echo '';
                        }
                    ?>
                </td>
                <td class="column-status" data-colname="status">
                    <?php 
                        if($data->is_active == 1){ 
                            echo '<span class="badge bg-success">Aktif</span>';
                        }else{
                            echo '<span class="badge bg-danger">Tidak Aktif</span>';
                        }
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
                        <span>Judul Soal</span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Tipe</span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Kunci</span>
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

    
?>

<input type="hidden" id="PILIHAN_GANDA" value="<?php echo PILIHAN_GANDA; ?>"/>
<input type="hidden" id="PILIHAN_GANDA_KOMPLEKS" value="<?php echo PILIHAN_GANDA_KOMPLEKS; ?>"/>

<form id="soal_form" method="post">
    <?php if(!$is_lock){ ?>
        <input type="hidden" name="submit" value="true"/>
    <?php }else{ ?>
        <input type="hidden" name="unlock" value="true"/>
    <?php } ?>
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row"><label for="name">Judul Soal</label></th>
                <td><input type="text" class="regular-text" name="title" value="<?php echo $title; ?>" maxlength="100" <?php echo $is_lock ? 'disabled' : '' ?>></td>
            </tr>
            <tr>
                <th scope="row"><label for="name">Soal</label></th>
                <td>
                    <?php 
                    $wp_editor_setting = array(
                        'textarea_name' => 'question',
                    );

                    if($is_lock){
                        $wp_editor_setting['tinymce']['readonly'] = true;
                    }

                    wp_editor( $question, 'soal_wysiwyg', $wp_editor_setting); 
                    ?>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="name">Tipe Soal</label></th>
                <td>
                    <select id="question_type" name="question_type" <?php echo $is_lock ? 'disabled' : '' ?>>
                        <option value="1" <?php if($question_type == 1) echo 'selected'; ?>><?php echo PILIHAN_GANDA_LABEL ?></option>
                        <option value="2" <?php if($question_type == 2) echo 'selected'; ?>><?php echo PILIHAN_GANDA_KOMPLEKS_LABEL ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="name">Jawaban</label></th>
                <td>
                    Centang Jawaban Yang Benar
                    <br/>
                    <br/>
                    <div id="answer_options">
                        <?php
                        if($options != null){
                            foreach($options as $index => $option){
                                $selected = $option->score >= 1 ? 'checked' : '';
                                $isDisabled = $is_lock ? 'disabled' : '';

                                echo '<div class="form-group question_option">';

                                //check question type
                                if($question_type == PILIHAN_GANDA){
                                    echo '<input type="radio" name="answer_options_score" value="'.$index.'" class="form-control" '.$selected.' '.$isDisabled.'>';
                                }else if($question_type == PILIHAN_GANDA_KOMPLEKS){
                                    echo '<input type="checkbox" name="answer_options_score['.$index.']" class="form-control" '.$selected.' '.$isDisabled.'>';
                                }

                                echo '<input type="text" name="answer_options['.$index.']" class="regular-text" placeholder="Jawaban" value="'.$option->label.'" '.$isDisabled.'>';
                                if(!$isDisabled){
                                    echo '&nbsp;&nbsp;<a href="#!" class="btn btn-danger btn_remove_option">Hapus</a>';
                                }
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                    <?php if(!$is_lock){ ?>
                    <a href="#!" id="add_answer_option">+&nbsp;Tambah Jawaban</a>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="name">Pembahasan Soal</label></th>
                <td>
                    <?php 
                    $wp_editor_expla_setting = array(
                        'textarea_name' => 'explanation',
                    );

                    if($is_lock){
                        $wp_editor_expla_setting['tinymce']['readonly'] = true;
                    }

                    wp_editor( $explanation, 'explanation_wysiwyg', $wp_editor_expla_setting); 
                    ?>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="is_active">Kunci Soal</label></th>
                <td><input class="form-control" name="is_lock" type="checkbox" <?php echo $is_lock==1 ? 'checked' : ''; ?> <?php echo $is_lock ? 'disabled' : '' ?>/>&nbsp;Soal yang di kunci tidak dapat di ubah!</td>
            </tr>
            <tr>
                <th scope="row"><label for="is_active">Status</label></th>
                <td><input class="form-control" name="is_active" type="checkbox" <?php echo $is_active==1 ? 'checked' : ''; ?> <?php echo $is_lock ? 'disabled' : '' ?>/>&nbsp;Is Active</td>
            </tr>
            <?php if(!$is_lock){ ?>
            <tr>
                <td>
                </td>
                <td>
                    <a href="<?php echo $list_url; ?>" class="button button-secondary">Back</a>
                    <button type="submit" class="button button-primary">Submit</button>
                    <?php if($is_edit){ ?>
                    <button type="submit" name="lock" value="true" class="button button-warning">Kunci Soal</button>
                    <?php } ?>
                </td>
            </tr>
            <?php }else{ ?>
                <td>
                </td>
                <td>
                    <a href="<?php echo $list_url; ?>" class="button button-secondary">Back</a>
                    <button type="submit" class="button button-primary">Buka Kunci</button>
                </td>
            <?php } ?>
        </tbody>
    </table>
</form>
<?php
}
?>

</div>