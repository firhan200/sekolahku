
<?php
$menu_ppn = true;
$menu_client = true;
$selected_user_id = $_GET['user_id'];

global $wpdb;
$table_users_name = _tbl_users;
$table_name = _tbl_ppn;

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
$action_label = "PPN";
if($action_type != null){
    $bulan_pajak = date('m');
    $tahun_pajak = date('Y');
    $attachment_id = null;
    $status = PERIZINAN_PENDING;
    $filename = null;

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
            $bulan_pajak = $data->bulan_pajak;
            $tahun_pajak = $data->tahun_pajak;
            $attachment_id = $data->attachment_id;
            $status = $data->status;
            $filename = $data->filename;
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
$modul_name = 'ppn';
$list_url = site_url('/')._admin_pages_ppn.'?user_id='.$user->id;
$add_url = $list_url.'&action_type=add';
$edit_url = $list_url.'&action_type=edit&id=';
$delete_url = $list_url.'&action_type=delete&id=';

//check if submit
if(count($errors) < 1){
    if($_POST['submit']){
        $user_id = $_POST['user_id'];
        $bulan_pajak = $_POST['bulan_pajak'];
        $tahun_pajak = $_POST['tahun_pajak'];
        $attachment_id = $_POST['custom_attachment_id'];
        $status = $_POST['status'];
        $filename = $_POST['filename'];

        $post_id = $attachment_id;
        if($_FILES['async-upload']['name']){
            if ( isset( $_POST['html-upload'] ) && ! empty( $_FILES ) ) {
                require_once( ABSPATH . 'wp-admin/includes/admin.php' );
                $att_id = media_handle_upload( 'async-upload', $post_id ); //post id of Client Files page
                unset( $_FILES );
                if( is_wp_error( $att_id ) ) {
                    $errors['upload_error'] = $att_id;
                    $att_id = false;
                }

                if( $errors ) {
                    echo "<p>There was an error uploading your file.</p>";
                }
            }

            $attachment_id = $att_id;
        }


        //validation
        if(empty($bulan_pajak)){
            $errors[] = '<b>Bulan</b> Tidak Boleh Kosong';
        }

        if(empty($tahun_pajak)){
            $errors[] = '<b>Tahun</b> Tidak Boleh Kosong';
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
                        'bulan_pajak' => $bulan_pajak,
                        'tahun_pajak' => $tahun_pajak,
                        'attachment_id' => $attachment_id,
                        'status' => $status,
                        'filename' => $filename
                    ),
                    array(
                        '%d',
                        '%s',
                        '%s',
                        '%s',
                        '%d',
                        '%s',
                    )
                );

                $object_id = $wpdb->insert_id;
            }else if($is_edit){
                $object_id = $id;

                //update into wpdb database
                $wpdb->update(
                    $table_name,
                    array(
                        'bulan_pajak' => $bulan_pajak,
                        'tahun_pajak' => $tahun_pajak,
                        'attachment_id' => $attachment_id,
                        'status' => $status,
                        'filename' => $filename
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
                        <li class="breadcrumb-item"><a href="<?php echo site_url('/')._admin_pages_home ?>">Client</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo site_url('/')._admin_pages_home."?action_type=edit&id=".$parent_id ?>"><?php echo $user->company_name ?></a></li>
                        <?php if($is_list){ ?>
                            <li class="breadcrumb-item active" aria-current="page">PPN</li>
                        <?php }else{ ?>
                            <li class="breadcrumb-item"><a href="<?php echo $list_url ?>">PPN</a></li>
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
                            <span>Tanggal Pajak</span>
                    </th>
                    <th scope="col" id="description" class="manage-column column-title column-primary sortable desc">
                            <span>Nama File</span>
                    </th>
                    <th scope="col" id="description" class="manage-column column-title column-primary sortable desc">
                            <span>Link Dokumen</span>
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
                        <?php echo intToMonth($data->bulan_pajak).' '.$data->tahun_pajak; ?>

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
                        <?php echo $data->filename; ?>
                    </td>
                    <td class="column-target-date" data-colname="target_date">
                        <?php echo '<a href="'.wp_get_attachment_url($data->attachment_id).'" target="_blank">'.wp_get_attachment_url($data->attachment_id).'</a>'; ?>
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
    wp_enqueue_media();
?>
<form class="box p-5" method="post" enctype="multipart/form-data">
    <input type="hidden" name="submit" value="true"/>
    <input type="hidden" name="user_id" value="<?php echo $user->id; ?>"/>

    <div class="mb-3 row align-items-center">
        <label class="col-sm-12 col-md-4 col-lg-3">
            Nama File
        </label>
        <div class="col-sm-12 col-md-8 col-ld-9">
            <input type="text" class="form-control" name="filename" value="<?php echo $filename; ?>" maxlength="250">
        </div>
    </div>

    <div class="mb-3 row align-items-center">
        <label class="col-sm-12 col-md-4 col-lg-3">
            Bulan Pajak
        </label>
        <div class="col-sm-12 col-md-8 col-ld-9">
            <select name="bulan_pajak" class="form-control" required>
                <option value="1" <?php echo $bulan_pajak == 1 ? 'selected' : '' ?>>Januari</option>
                <option value="2" <?php echo $bulan_pajak == 2 ? 'selected' : '' ?>>Februari</option>
                <option value="3" <?php echo $bulan_pajak == 3 ? 'selected' : '' ?>>Maret</option>
                <option value="4" <?php echo $bulan_pajak == 4 ? 'selected' : '' ?>>April</option>
                <option value="5" <?php echo $bulan_pajak == 5 ? 'selected' : '' ?>>Mei</option>
                <option value="6" <?php echo $bulan_pajak == 6 ? 'selected' : '' ?>>Juni</option>
                <option value="7" <?php echo $bulan_pajak == 7 ? 'selected' : '' ?>>Juli</option>
                <option value="8" <?php echo $bulan_pajak == 8 ? 'selected' : '' ?>>Agustus</option>
                <option value="9" <?php echo $bulan_pajak == 9 ? 'selected' : '' ?>>September</option>
                <option value="10" <?php echo $bulan_pajak == 10 ? 'selected' : '' ?>>Oktober</option>
                <option value="11" <?php echo $bulan_pajak == 11 ? 'selected' : '' ?>>November</option>
                <option value="12" <?php echo $bulan_pajak == 12 ? 'selected' : '' ?>>Desember</option>
            </select>
        </div>
    </div>
    <div class="mb-3 row align-items-center">
        <label class="col-sm-12 col-md-4 col-lg-3">
            Tahun Pajak
        </label>
        <div class="col-sm-12 col-md-8 col-ld-9">
            <select name="tahun_pajak" class="form-control" required>
                <?php
                for($year = 1960; $year <= 2030; $year++){
                    $isSelected = $tahun_pajak == $year ? 'selected' : '';
                    echo '<option value="'.$year.'" '.$isSelected.'>'.$year.'</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div class="mb-3 row align-items-center">
        <label class="col-sm-12 col-md-4 col-lg-3">
            File
        </label>
        <div class="col-sm-12 col-md-8 col-ld-9">
            <?php if($attachment_id != null && $attachment_id != ''){ ?>
                <input type="hidden" name="custom_attachment_id" value="<?php echo $attachment_id ?>"/>
                <a href="<?php echo wp_get_attachment_url($attachment_id) ?>" target="_blank"><?php echo wp_get_attachment_url($attachment_id) ?></a>
                <br/>
                <br/>
            <?php } ?>
            <p id="async-upload-wrap"><label for="async-upload"></label>
            <input type="file" class="form-control" d="async-upload" name="async-upload"> </p>

            <p><input type="hidden" name="post_id" id="post_id" value="<?php echo $post_id ?>" />
            <?php wp_nonce_field( 'client-file-upload' ); ?>
            <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" /></p>
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

        </label>
        <div class="col-sm-12 col-md-8 col-ld-9">
            <a href="<?php echo $list_url; ?>" class="btn btn-secondary">Back</a>
            <input type="submit" class="btn btn-danger" value="Submit" name="html-upload">
        </div>
    </div>
</form>
<?php
}
?>

</div>
</div>