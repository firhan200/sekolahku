
<?php
$menu_hki = true;
$menu_client = true;

global $wpdb;
$table_hki_name = _tbl_hki;
$table_name = _tbl_hki_documents;

//var
$is_add = false;
$is_edit = false;
$is_list = false;
$is_delete = false;
$is_bulk_delete = false;
$success = [];
$errors = [];

//get parent id
$parent_id = $_GET['hki_id'];
if($parent_id == null){
    $errors[] = 'HKI tidak ditemukan';
}else{
    $hki = $wpdb->get_row("SELECT * FROM $table_hki_name WHERE id = $parent_id");
    if($hki == null){
        $errors[] = 'HKI tidak ditemukan';
    }
}

$selected_user_id = $hki->user_id;

//get user
$user = $wpdb->get_row("SELECT * FROM "._tbl_users." WHERE id = $selected_user_id");
if($user == null){
    $errors[] = 'User tidak ditemukan';
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
$action_label = "Dokumen HKI";
if($action_type != null){
    $attachment_id = null;
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
            $attachment_id = $data->attachment_id;
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
$modul_name = 'hki_dokumen';
$list_url = site_url('/')._admin_pages_hki_dokumen.'?hki_id='.$hki->id;
$add_url = $list_url.'&action_type=add';
$edit_url = $list_url.'&action_type=edit&id=';
$delete_url = $list_url.'&action_type=delete&id=';

//check if submit
if(count($errors) < 1){
    if($_POST['submit']){
        $hki_id = $_POST['hki_id'];
        $attachment_id = $_POST['custom_attachment_id'];
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


        if(count($errors) == 0){
            //all input valid
            $object_id = null;
            //check if edit or add
            if($is_add){
                //insert into wpdb database
                $wpdb->insert(
                    $table_name,
                    array(
                        'hki_id' => $hki_id,
                        'attachment_id' => $attachment_id,
                        'filename' => $filename,
                    ),
                    array(
                        '%d',
                        '%d',
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
                        'attachment_id' => $attachment_id,
                        'filename' => $filename,
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
                        <li class="breadcrumb-item"><a href="<?php echo site_url('/')._admin_pages_hki."?&user_id=".$hki->user_id ?>"><?php echo $hki->pemohon.' - '.$hki->pekerjaan ?></a></li>
                        <?php if($is_list){ ?>
                            <li class="breadcrumb-item active" aria-current="page">Dokumen</li>
                        <?php }else{ ?>
                            <li class="breadcrumb-item"><a href="<?php echo $list_url ?>">Dokumen</a></li>
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
$query = "SELECT * FROM ".$table_name.' WHERE hki_id='.$hki->id;
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
                            <span>Nama File</span>
                    </th>
                    <th scope="col" id="description" class="manage-column column-title column-primary sortable desc">
                            <span>Link</span>
                    </th>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                            <span>Tanggal</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($list_of_data as $key => $data){ ?>
                <tr id="post-<?php echo $key+1; ?>" class="type-post">
                    <td class="column-target-date" data-colname="target_date">
                        <?php 
                            echo $data->filename;
                        ?>
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
                    <td class="title column-title has-row-actions column-primary page-name">
                        <?php echo '<a href="'.wp_get_attachment_url($data->attachment_id).'" target="_blank">'.wp_get_attachment_url($data->attachment_id).'</a>'; ?>
                    </td>
                    <td class="column-target-date" data-colname="target_date">
                        <?php 
                            echo date('d M Y', strtotime($data->created_on));
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
    <input type="hidden" name="hki_id" value="<?php echo $hki->id; ?>"/>

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
            <input type="file" id="async-upload" class="form-control" name="async-upload"> </p>

            <p><input type="hidden" name="post_id" id="post_id" value="<?php echo $post_id ?>" />
            <?php wp_nonce_field( 'client-file-upload' ); ?>
            <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" /></p>
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