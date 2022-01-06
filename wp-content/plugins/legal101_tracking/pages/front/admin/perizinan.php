
<?php
$menu_perizinan = true;
$menu_client = true;
$selected_user_id = $_GET['user_id'];

global $wpdb;
$table_users_name = _tbl_users;
$table_name = _tbl_perizinan;

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
$action_label = "Perizinan";
if($action_type != null){
    $description = '';
    $progress_message = '';
    $target_date = null;
    $status = PERIZINAN_PENDING;

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
            $description = $data->description;
            $progress_message = $data->progress_message;
            $target_date = $data->target_date;
            $status = $data->status;
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
$modul_name = 'perizinan';
$list_url = $list_url = site_url('/')._admin_pages_perizinan.'?user_id='.$user->id;
$add_url = $list_url.'&action_type=add';
$edit_url = $list_url.'&action_type=edit&id=';
$delete_url = $list_url.'&action_type=delete&id=';

//check if submit
if(count($errors) < 1){
    if($_POST['submit']){
        $user_id = $_POST['user_id'];
        $description = $_POST['description'];
        $progress_message = $_POST['progress_message'];
        $target_date = $_POST['target_date'];
        $status = $_POST['status'];

        //validation
        if(empty($description)){
            $errors[] = '<b>Deskripsi</b> Tidak Boleh Kosong';
        }

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
                        'description' => $description,
                        'progress_message' => $progress_message,
                        'target_date' => $target_date,
                        'status' => $status
                    ),
                    array(
                        '%d',
                        '%s',
                        '%s',
                        '%s',
                        '%d',
                    )
                );

                $object_id = $wpdb->insert_id;
            }else if($is_edit){
                $object_id = $id;

                //update into wpdb database
                $wpdb->update(
                    $table_name,
                    array(
                        'description' => $description,
                        'progress_message' => $progress_message,
                        'target_date' => $target_date,
                        'status' => $status
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

<div class="main-header" style="background-image:url('<?php echo $my_plugin; ?>/assets/img/header.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="legal-ts-breadcrumb breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('/')._admin_pages_home ?>">Client</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo site_url('/')._admin_pages_home."?action_type=edit&id=".$parent_id ?>"><?php echo $user->company_name ?></a></li>
                        <?php if($is_list){ ?>
                            <li class="breadcrumb-item active" aria-current="page">Perizinan</li>
                        <?php }else{ ?>
                            <li class="breadcrumb-item"><a href="<?php echo $list_url ?>">Perizinan</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $is_add ? 'Create' : 'Edit' ?></li>
                        <?php } ?>
                    </ol>
                </nav>
                <h1 class="legal-ts-main-title"><?php echo $action_label; ?></h1>
            </div>
        </div>
    </div>
</div>
<div class="container main-container">
<div class="wrap">
    <div class="row">
        <div class="col-md-12 text-end">
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
$query = "SELECT * FROM ".$table_name.' WHERE user_id='.$user->id;
$keyword = '';

//filter
if($_POST['keyword']){
    $keyword = $_POST['keyword'];
    $query .= " AND name LIKE '%".$keyword."%'";
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

    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th scope="col" id="description" class="manage-column column-title column-primary sortable desc">
                            <span>Deskripsi</span>
                    </th>
                    <th scope="col" id="description" class="manage-column column-title column-primary sortable desc">
                            <span>Target Date</span>
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
                        <?php echo $data->description; ?>

                        <div class="row-actions action_container">
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
                            echo date('d M Y', strtotime($data->target_date));
                        ?>
                    </td>
                    <td class="column-status" data-colname="status">
                        <?php 
                            if($data->status == PERIZINAN_PENDING){ 
                                echo '<span class="badge bg-secondary">Pending</span>';
                            }else if($data->status == PERIZINAN_ON_PROGRESS){
                                echo '<span class="badge bg-warning">On-Progress</span>';
                            }else if($data->status == PERIZINAN_DONE){
                                echo '<span class="badge bg-success">Done</span>';
                            }else if($data->status == PERIZINAN_CANCELLED){
                                echo '<span class="badge bg-danger">Cancelled</span>';
                            }
                        ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
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
<form class="box p-5" method="post">
    <input type="hidden" name="submit" value="true"/>
    <input type="hidden" name="user_id" value="<?php echo $user->id; ?>"/>

    <div class="mb-3 row align-items-center">
        <label class="col-sm-12 col-md-4 col-lg-3">
            Deskripsi
        </label>
        <div class="col-sm-12 col-md-8 col-ld-9">
            <textarea rows="5" class="form-control" cols="100" name="description" maxlength="250"><?php echo $description; ?></textarea>
        </div>
    </div>
    <div class="mb-3 row align-items-center">
        <label class="col-sm-12 col-md-4 col-lg-3">
            Progress Message
        </label>
        <div class="col-sm-12 col-md-8 col-ld-9">
            <textarea rows="5" class="form-control" cols="100" name="progress_message" maxlength="250"><?php echo $progress_message; ?></textarea>
        </div>
    </div>
    <div class="mb-3 row align-items-center">
        <label class="col-sm-12 col-md-4 col-lg-3">
            Status
        </label>
        <div class="col-sm-12 col-md-8 col-ld-9">
            <select name="status" class="form-control" required>
                <option value="">-- Pilih Status --</option>
                <option value="<?php echo PERIZINAN_PENDING ?>" <?php echo $status == PERIZINAN_PENDING ? 'selected' : '' ?>><?php echo 'Pending'; ?></option>
                <option value="<?php echo PERIZINAN_ON_PROGRESS ?>" <?php echo $status == PERIZINAN_ON_PROGRESS ? 'selected' : '' ?>><?php echo 'On-Progress'; ?></option>
                <option value="<?php echo PERIZINAN_DONE ?>" <?php echo $status == PERIZINAN_DONE ? 'selected' : '' ?>><?php echo 'Done'; ?></option>
                <option value="<?php echo PERIZINAN_CANCELLED ?>" <?php echo $status == PERIZINAN_CANCELLED ? 'selected' : '' ?>><?php echo 'Cancelled'; ?></option>
            </select>
        </div>
    </div>
    <div class="mb-3 row align-items-center">
        <label class="col-sm-12 col-md-4 col-lg-3">
            Target Date
        </label>
        <div class="col-sm-12 col-md-8 col-ld-9">
            <input type="text" class="form-control custom_date" name="target_date" value="<?php echo $target_date; ?>" maxlength="50">
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