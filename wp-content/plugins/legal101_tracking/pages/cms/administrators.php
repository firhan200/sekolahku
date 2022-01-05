<?php
global $wpdb;
$table_name = _tbl_administrators;

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
    $email_address = '';
    $password = null;
    $full_name = '';
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
            $email_address = $data->email_address;
            $full_name = $data->full_name;
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
$modul_name = 'administrators';
$list_url = admin_url('/admin.php?page='.$modul_name);
$add_url = $list_url.'&action_type=add';
$edit_url = $list_url.'&action_type=edit&id=';
$delete_url = $list_url.'&action_type=delete&id=';

//check if submit
if(count($errors) < 1){
    if($_POST['submit']){
        $full_name = $_POST['full_name'];
        $email_address = $_POST['email_address'];
        $password = $_POST['password'];
        $is_active = $_POST['is_active'] == 'on' ? 1 : 0;

        //validation
        if(empty($full_name)){
            $errors[] = '<b>Nama</b> Tidak Boleh Kosong';
        }

        if(empty($email_address)){
            $errors[] = '<b>Email</b> Tidak Boleh Kosong';
        }

        if($is_add){
            if(empty($password)){
                $errors[] = '<b>Password</b> Tidak Boleh Kosong';
            }
        }

        //check if email already exist
        if($is_add){
            $existUser = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE email_address = '$email_address'");
            if($existUser != null){
                $errors[] = '<b>Email</b> Telah Terpakai.';
            }
        }

        if(count($errors) == 0){
            //all input valid
            $object_id = null;
            //check if edit or add
            if($is_add){
                //insert into wpdb database
                $encrypted_password = sha1($password);
                $wpdb->insert(
                    $table_name,
                    array(
                        'full_name' => $full_name,
                        'email_address' => $email_address,
                        'password' => $encrypted_password,
                        'is_active' => $is_active
                    ),
                    array(
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%d'
                    )
                );

                $object_id = $wpdb->insert_id;
                $id = $object_id;
            }else if($is_edit){
                $object_id = $id;

                $updatedArr = array(
                    'full_name' => $full_name,
                    'email_address' => $email_address,
                    'is_active' => $is_active
                );

                //check if update password
                if($password != null && $password != ''){
                    $updatedArr['password'] = sha1($password);
                }

                //update into wpdb database
                $wpdb->update(
                    $table_name,
                    $updatedArr,
                    array('id' => $id)
                );
            }

            //success
            $success[] = 'Data berhasil disimpan';

            //show list
            $is_list = false;
            $is_edit = true;

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

        //delete relationship

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
            
            if($postId != null && $postId != ""){
                //delete relationship
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
$query = "SELECT u.* FROM ".$table_name." AS u";
$keyword = '';

//filter
if($_POST['keyword']){
    $keyword = $_POST['keyword'];
    $query .= " WHERE u.full_name LIKE '%".$keyword."%'";
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
                        <span>Nama</span>
                    </a>
                </th>
                <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Email</span>
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
                <th scope="row" class="check-column">
                    <label class="screen-reader-text" for="cb-select-1">
                        Pilih <?php echo $data->full_name; ?>
                    </label>
                    <input id="cb-select-1" type="checkbox" name="post[]" value="<?php echo $data->id; ?>">
                    <div class="locked-indicator">
                        <span class="locked-indicator-icon" aria-hidden="true"></span>
                        <span class="screen-reader-text">
                            “<?php echo $data->full_name; ?>” terkunci
                        </span>
                    </div>
                </th>
                <td class="title column-title has-row-actions column-primary page-name">
                    <?php echo $data->full_name; ?>

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
                <td class="column-status" data-colname="status">
                    <?php 
                    echo $data->email_address;
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
                        <span>Nama</span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Email</span>
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

<form method="post">
    <input type="hidden" name="submit" value="true"/>
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row"><label for="name">Email Address</label></th>
                <td><input type="email" class="regular-text" name="email_address" value="<?php echo $email_address; ?>" maxlength="150" required></td>
            </tr>
            <tr>
                <th scope="row"><label for="name">Password</label></th>
                <td>
                    <div class="wp-pwd">
                        <span class="password-input-wrapper">
                            <input type="password" name="password" id="password" class="regular-text" autocomplete="off" <?php echo $is_add ? 'required' : ''; ?>>
                        </span>
                        <button type="button" class="button btn-password-show wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="Tampilkan sandi">
                            <span class="dashicons dashicons-visibility" aria-hidden="true"></span>
                            <span class="text">Tampilkan</span>
                        </button>
                    </div>
                    <?php if($is_edit){
                        echo '<div class="alert alert-warning">Kosongkan <b>Password</b> apabila tidak ingin mengubah password lama.</div>';
                    } ?>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="name">Nama</label></th>
                <td><input type="text" class="regular-text" name="full_name" value="<?php echo $full_name; ?>" maxlength="250" required></td>
            </tr>
            <tr>
                <th scope="row"><label for="is_active">Status</label></th>
                <td><input class="form-control" name="is_active" type="checkbox" <?php echo $is_active==1 ? 'checked' : ''; ?>/>&nbsp;Is Active</td>
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