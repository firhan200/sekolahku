<div class="container main-container">

<?php
$menu_hki = true;
$menu_client = true;
$selected_user_id = $_GET['user_id'];

global $wpdb;
$table_users_name = _tbl_users;
$table_name = _tbl_hki;

//var
$is_add = false;
$is_edit = false;
$is_list = false;
$is_delete = false;
$is_bulk_delete = false;
$success = [];
$errors = [];

//get parent id
$parent_id = $_GET['user_id'];
if($parent_id == null){
    $errors[] = 'User tidak ditemukan';
}else{
    $user = $wpdb->get_row("SELECT * FROM $table_users_name WHERE id = $parent_id");
    if($user == null){
        $errors[] = 'User tidak ditemukan';
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
$action_label = "Hak Kekayaan Intelektual";
if($action_type != null){
    $minuta = '';
    $pemohon = '';
    $pekerjaan = '';
    $no_agenda = '';
    $class = '';
    $tanggal_penerimaan = null;
    $status = '';
    $deadline = null;

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
            $minuta = $data->minuta;
            $pemohon = $data->pemohon;
            $pekerjaan = $data->pekerjaan;
            $no_agenda = $data->no_agenda;
            $class = $data->class;
            $tanggal_penerimaan = $data->tanggal_penerimaan;
            $status = $data->status;
            $deadline = $data->deadline;
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
$modul_name = 'hki';
$list_url = site_url('/')._admin_pages_hki.'?user_id='.$user->id;
$hki_documents_url = site_url('/')._admin_pages_hki_dokumen.'?hki_id=';
$add_url = $list_url.'&action_type=add';
$edit_url = $list_url.'&action_type=edit&id=';
$delete_url = $list_url.'&action_type=delete&id=';

//check if submit
if(count($errors) < 1){
    if($_POST['submit']){
        $user_id = $_POST['user_id'];
        $minuta = $_POST['minuta'];
        $pemohon = $_POST['pemohon'];
        $pekerjaan = $_POST['pekerjaan'];
        $no_agenda = $_POST['no_agenda'];
        $class = $_POST['class'];
        $tanggal_penerimaan = $_POST['tanggal_penerimaan'];
        $status = $_POST['status'];
        $deadline = $_POST['deadline'];


        if(count($errors) == 0){
            //all input valid
            $object_id = null;
            //check if edit or add
            if($is_add){
                //insert into wpdb database
                $wpdb->insert(
                    $table_name,
                    array(
                        'user_id' => $user_id,
                        'minuta' => $minuta,
                        'pemohon' => $pemohon,
                        'pekerjaan' => $pekerjaan,
                        'no_agenda' => $no_agenda,
                        'class' => $class,
                        'tanggal_penerimaan' => $tanggal_penerimaan,
                        'status' => $status,
                        'deadline' => $deadline
                    ),
                    array(
                        '%d',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s'
                    )
                );

                $object_id = $wpdb->insert_id;
            }else if($is_edit){
                $object_id = $id;

                //update into wpdb database
                $wpdb->update(
                    $table_name,
                    array(
                        'minuta' => $minuta,
                        'pemohon' => $pemohon,
                        'pekerjaan' => $pekerjaan,
                        'no_agenda' => $no_agenda,
                        'class' => $class,
                        'tanggal_penerimaan' => $tanggal_penerimaan,
                        'status' => $status,
                        'deadline' => $deadline
                    ),
                    array('id' => $id)
                );
            }

            //success
            $success[] = 'Data berhasil disimpan';

            //show list
            $is_list = false;

            echo "<script>window.history.pushState('page2', 'Title', '".$edit_url.$object_id."');</script>";
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
    <div class="row">
        <div class="col-md-6">
            <h1 class="wp-heading-inline">
                <?php echo $action_label; ?>
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo site_url('/')._admin_pages_home."?action_type=edit&id=".$parent_id ?>"><?php echo $user->company_name ?></a></li>
                    <?php if($is_list){ ?>
                        <li class="breadcrumb-item active" aria-current="page">HKI</li>
                    <?php }else{ ?>
                        <li class="breadcrumb-item"><a href="<?php echo $list_url ?>">HKI</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $is_add ? 'Create' : 'Edit' ?></li>
                    <?php } ?>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-end">
            <?php if($is_list){ ?>
                <a href="<?php echo $add_url; ?>" class="page-title-action btn btn-danger"><i class="fa fa-plus"></i> Tambah Baru</a>
            <?php } ?>
        </div>
    </div>

    <?php if(count($success) > 0){
        foreach($success as $msg){
            echo '<div class="alert alert-success"><p>'.$msg.'</p></div>';
        }
    } ?>

    <?php if(count($errors) > 0){
        foreach($errors as $msg){
            echo '<div class="alert alert-danger"><p>'.$msg.'</p></div>';
        }
    } ?>

    <div class="mb-3"></div>

<?php
if($is_list){
//get list from database
$query = "SELECT h.*, (SELECT COUNT(*) FROM "._tbl_hki_documents." WHERE hki_id=h.id) AS total_dokumen FROM ".$table_name.' AS h WHERE h.user_id='.$user->id;
$keyword = '';

//filter
if($_POST['keyword']){
    $keyword = $_POST['keyword'];
    $query .= " AND h.pemohon LIKE '%".$keyword."%'";
}

//order by query
$query .= " ORDER BY h.id DESC";

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

    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th scope="col" id="description" class="manage-column column-title column-primary sortable desc">
                            <span>Pemohon</span>
                    </th>
                    <th scope="col" id="description" class="manage-column column-title column-primary sortable desc">
                            <span>Pekerjaan</span>
                    </th>
                    <th scope="col" id="description" class="manage-column column-title column-primary sortable desc">
                            <span>Total Dokumen</span>
                    </th>
                    <th scope="col" id="description" class="manage-column column-title column-primary sortable desc">
                            <span>No. Agenda</span>
                    </th>
                    <th scope="col" id="description" class="manage-column column-title column-primary sortable desc">
                            <span>Class</span>
                    </th>
                    <th scope="col" id="description" class="manage-column column-title column-primary sortable desc">
                            <span>Tanggal Penerimaan</span>
                    </th>
                    <th scope="col" id="description" class="manage-column column-title column-primary sortable desc">
                            <span>Deadline</span>
                    </th>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                            <span>Status</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($list_of_data as $key => $data){ ?>
                <tr id="post-<?php echo $key+1; ?>" class="type-post">
                    <td class="title column-title has-row-actions column-primary page-name">
                        <?php echo $data->pemohon; ?>

                        <div class="row-actions action_container">
                            <span class="edit">
                                <a href="<?php echo $hki_documents_url.$data->id ?>" aria-label="Edit">
                                    Kelola Dokumen
                                </a> 
                                | 
                            </span>
                            <span class="edit">
                                <a href="<?php echo $edit_url.$data->id ?>" aria-label="Edit">
                                    Edit
                                </a> 
                                | 
                            </span>
                            <span class="delete">
                                <a href="<?php echo $delete_url.$data->id ?>" onclick="return confirm('Hapus <?php echo $data->name; ?>?')" aria-label="Delete">
                                    Delete
                                </a>
                            </span>
                        </div>
                    </td>
                    <td class="column-target-date" data-colname="target_date">
                        <?php 
                            echo htmlspecialchars($data->pekerjaan);
                        ?>
                    </td>
                    <td class="column-target-date" data-colname="target_date">
                        <a href="<?php echo $hki_documents_url.$data->id ?>" aria-label="Edit">
                            <?php 
                                echo $data->total_dokumen;
                            ?>
                        </a>
                    </td>
                    <td class="column-target-date" data-colname="target_date">
                        <?php 
                            echo htmlspecialchars($data->no_agenda);
                        ?>
                    </td>
                    <td class="column-target-date" data-colname="target_date">
                        <?php 
                            echo htmlspecialchars($data->class);
                        ?>
                    </td>
                    <td class="column-target-date" data-colname="target_date">
                        <?php 
                            echo date('d M Y', strtotime($data->tanggal_penerimaan));
                        ?>
                    </td>
                    <td class="column-target-date" data-colname="target_date">
                        <?php 
                            echo date('d M Y', strtotime($data->deadline));
                        ?>
                    </td>
                    <td class="column-target-date" data-colname="target_date">
                        <?php 
                            echo htmlspecialchars($data->status);
                        ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th scope="col" class="manage-column column-title column-primary sortable desc">
                            <span>Pemohon</span>
                    </th>
                    <th scope="col" class="manage-column column-title column-primary sortable desc">
                            <span>Pekerjaan</span>
                    </th>
                    <th scope="col" class="manage-column column-title column-primary sortable desc">
                            <span>Total Dokumen</span>
                    </th>
                    <th scope="col" class="manage-column column-title column-primary sortable desc">
                            <span>No. Agenda</span>
                    </th>
                    <th scope="col" class="manage-column column-title column-primary sortable desc">
                            <span>Class</span>
                    </th>
                    <th scope="col" class="manage-column column-title column-primary sortable desc">
                            <span>Tanggal Penerimaan</span>
                    </th>
                    <th scope="col" class="manage-column column-title column-primary sortable desc">
                            <span>Deadline</span>
                    </th>
                    <th scope="col" class="manage-column column-title column-primary sortable desc">
                            <span>Status</span>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</form>
    <?php
    // Display the paging information
    echo '<div id="paging"><p>', $prevlink, ' Halaman <b>', $page, '</b> dari <b>', $pages, '</b> , Menampilkan <b>', $start, '-', $end, '</b> dari <b>', $total, '</b> Hasil ', $nextlink, ' </p></div>';
    ?>
<?php 
}else if($is_add || $is_edit){
?>

<?php
if($is_edit){
    //kelola dokumen
    echo '<a href="'.$hki_documents_url.$id.'" class="btn btn-secondary">Kelola Dokumen</a>';
    echo '<div class="mb-4"></div>';
}
?>

<form class="box p-5" method="post">
    <input type="hidden" name="submit" value="true"/>
    <input type="hidden" name="user_id" value="<?php echo $user->id; ?>"/>

    <div class="mb-3 row align-items-center">
        <label class="col-sm-12 col-md-4 col-lg-3">
            Pemohon
        </label>
        <div class="col-sm-12 col-md-8 col-ld-9">
            <input type="text" class="form-control" name="pemohon" value="<?php echo $pemohon; ?>" maxlength="250">
        </div>
    </div>
    <div class="mb-3 row align-items-center">
        <label class="col-sm-12 col-md-4 col-lg-3">
            Pekerjaan
        </label>
        <div class="col-sm-12 col-md-8 col-ld-9">
            <input type="text" class="form-control" name="pekerjaan" value="<?php echo $pekerjaan; ?>" maxlength="250">
        </div>
    </div>
    <div class="mb-3 row align-items-center">
        <label class="col-sm-12 col-md-4 col-lg-3">
            No. Agenda
        </label>
        <div class="col-sm-12 col-md-8 col-ld-9">
            <input type="text" class="form-control" name="no_agenda" value="<?php echo $no_agenda; ?>" maxlength="250">
        </div>
    </div>
    <div class="mb-3 row align-items-center">
        <label class="col-sm-12 col-md-4 col-lg-3">
            Class
        </label>
        <div class="col-sm-12 col-md-8 col-ld-9">
            <input type="text" class="form-control" name="class" value="<?php echo $class; ?>" maxlength="250">
        </div>
    </div>
    <div class="mb-3 row align-items-center">
        <label class="col-sm-12 col-md-4 col-lg-3">
            Tanggal Penerimaan
        </label>
        <div class="col-sm-12 col-md-8 col-ld-9">
            <input type="text" class="form-control custom_date" name="tanggal_penerimaan" value="<?php echo $tanggal_penerimaan; ?>" maxlength="50">
        </div>
    </div>
    <div class="mb-3 row align-items-center">
        <label class="col-sm-12 col-md-4 col-lg-3">
            Deadline
        </label>
        <div class="col-sm-12 col-md-8 col-ld-9">
            <input type="text" class="form-control custom_date" name="deadline" value="<?php echo $deadline; ?>" maxlength="50">
        </div>
    </div>
    <div class="mb-3 row align-items-center">
        <label class="col-sm-12 col-md-4 col-lg-3">
            Status
        </label>
        <div class="col-sm-12 col-md-8 col-ld-9">
            <textarea rows="5" cols="100" class="form-control" name="status" maxlength="250"><?php echo $status; ?></textarea>
        </div>
    </div>
    <div class="mb-3 row align-items-center">
        <label class="col-sm-12 col-md-4 col-lg-3">
            Minuta
        </label>
        <div class="col-sm-12 col-md-8 col-ld-9">
            <textarea rows="5" cols="100" class="form-control" name="minuta" maxlength="250"><?php echo $minuta; ?></textarea>
        </div>
    </div>
    <div class="mb-3 row align-items-center">
        <label class="col-sm-12 col-md-4 col-lg-3">
            
        </label>
        <div class="col-sm-12 col-md-8 col-ld-9">
            <a href="<?php echo $list_url; ?>" class="btn btn-secondary">Back</a>
            <button type="submit" class="btn btn-danger">Submit</button>
        </div>
    </div>
</form>
<?php
}
?>

</div>
</div>