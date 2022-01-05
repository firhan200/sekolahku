<div class="container main-container">
<?php
global $wpdb;
$table_name = _tbl_users;

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
$action_label = "Client";
if($action_type != null){
    $administrator_id = $_SESSION[SESSION_ADMIN_ID];
    $email_address = '';
    $password = null;
    $company_name = '';
    $pic_name = '';
    $company_address = '';
    $phone = '';
    $fax = '';
    $company_npwp = '';
    $main_director = '';
    $nib = '';
    $identity_number = '';
    $website = '';
    $npwp = '';
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
            $administrator_id = $data->administrator_id;
            $email_address = $data->email_address;
            $company_name = $data->company_name;
            $pic_name = $data->pic_name;
            $company_address = $data->company_address;
            $phone = $data->phone;
            $fax = $data->fax;
            $company_npwp = $data->company_npwp;
            $main_director = $data->main_director;
            $nib = $data->nib;
            $identity_number = $data->identity_number;
            $website = $data->website;
            $npwp = $data->npwp;
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
$modul_name = 'users';
$list_url = site_url('/')._admin_pages_home.'?';
$perizinan_url = site_url('/')._admin_pages_perizinan.'?user_id=';
$hki_url = admin_url('/admin.php?page=hki&user_id=');
$faktur_url = admin_url('/admin.php?page=faktur&user_id=');
$ppn_url = admin_url('/admin.php?page=ppn&user_id=');
$spt_url = admin_url('/admin.php?page=spt&user_id=');
$add_url = $list_url.'&action_type=add';
$edit_url = $list_url.'&action_type=edit&id=';
$delete_url = $list_url.'&action_type=delete&id=';

//check if submit
if(count($errors) < 1){
    if($_POST['submit']){
        $administrator_id = $_SESSION[SESSION_ADMIN_ID];
        $company_name = $_POST['company_name'];
        $email_address = $_POST['email_address'];
        $password = $_POST['password'];
        $pic_name = $_POST['pic_name'];
        $company_address = $_POST['company_address'];
        $phone = $_POST['phone'];
        $fax = $_POST['fax'];
        $company_npwp = $_POST['company_npwp'];
        $main_director = $_POST['main_director'];
        $nib = $_POST['nib'];
        $identity_number = $_POST['identity_number'];
        $website = $_POST['website'];
        $npwp = $_POST['npwp'];
        $is_active = $_POST['is_active'] == 'on' ? 1 : 0;

        //validation
        if(empty($company_name)){
            $errors[] = '<b>Nama Perusahaan</b> Tidak Boleh Kosong';
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
                        'administrator_id' => $administrator_id,
                        'company_name' => $company_name,
                        'email_address' => $email_address,
                        'password' => $encrypted_password,
                        'pic_name' => $pic_name,
                        'company_address' => $company_address,
                        'phone' => $phone,
                        'fax' => $fax,
                        'company_npwp' => $company_npwp,
                        'main_director' => $main_director,
                        'nib' => $nib,
                        'identity_number' => $identity_number,
                        'website' => $website,
                        'npwp' => $npwp,
                        'is_active' => $is_active
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
                    'administrator_id' => $administrator_id,
                    'company_name' => $company_name,
                    'email_address' => $email_address,
                    'pic_name' => $pic_name,
                    'company_address' => $company_address,
                    'phone' => $phone,
                    'fax' => $fax,
                    'company_npwp' => $company_npwp,
                    'main_director' => $main_director,
                    'nib' => $nib,
                    'identity_number' => $identity_number,
                    'website' => $website,
                    'npwp' => $npwp,
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
    <a href="<?php echo $add_url; ?>" class="page-title-action btn btn-primary btn-sm">Tambah Baru</a>
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
$query = "SELECT u.*, (SELECT COUNT(*) FROM "._tbl_perizinan." WHERE user_id=u.id) AS total_perizinan, (SELECT COUNT(*) FROM "._tbl_hki." WHERE user_id=u.id) AS total_hki, (SELECT COUNT(*) FROM "._tbl_faktur." WHERE user_id=u.id) AS total_faktur, (SELECT COUNT(*) FROM "._tbl_ppn." WHERE user_id=u.id) AS total_ppn, (SELECT COUNT(*) FROM "._tbl_spt_tahunan." WHERE user_id=u.id) AS total_spt FROM ".$table_name." AS u WHERE u.administrator_id=".$_SESSION[SESSION_ADMIN_ID];
$keyword = '';

//filter
if($_POST['keyword']){
    $keyword = $_POST['keyword'];
    $query .= " AND u.company_name LIKE '%".$keyword."%'";
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
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                        <span>Nama Perusahaan</span>
                    </th>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                        <span>Total Perizinan</span>
                    </th>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                        <span>Total HKI</span>
                    </th>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                        <span>Total Faktur</span>
                    </th>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                        <span>Total PPN</span>
                    </th>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                        <span>Total SPT Tahunan</span>
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
                        <?php echo $data->company_name; ?>

                        <div class="row-actions action_container">
                            <span class="edit">
                                <a href="<?php echo $perizinan_url.$data->id ?>" aria-label="Edit">
                                    Perizinan
                                </a> 
                                | 
                            </span>
                            <span class="edit">
                                <a href="<?php echo $hki_url.$data->id ?>" aria-label="Edit">
                                    HKI
                                </a> 
                                | 
                            </span>
                            <span class="edit">
                                <a href="<?php echo $faktur_url.$data->id ?>" aria-label="Edit">
                                    Faktur
                                </a> 
                                | 
                            </span>
                            <span class="edit">
                                <a href="<?php echo $ppn_url.$data->id ?>" aria-label="Edit">
                                    PPN
                                </a> 
                                | 
                            </span>
                            <span class="edit">
                                <a href="<?php echo $spt_url.$data->id ?>" aria-label="Edit">
                                    SPT
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
                    <td class="column-status" data-colname="status">
                        <a href="<?php echo $perizinan_url.$data->id ?>" aria-label="Edit">
                            <?php 
                            echo $data->total_perizinan;
                            ?>
                        </a>
                    </td>
                    <td class="column-status" data-colname="status">
                        <a href="<?php echo $hki_url.$data->id ?>" aria-label="Edit">
                            <?php 
                            echo $data->total_hki;
                            ?>
                        </a>
                    </td>
                    <td class="column-status" data-colname="status">
                        <a href="<?php echo $faktur_url.$data->id ?>" aria-label="Edit">
                            <?php 
                            echo $data->total_faktur;
                            ?>
                        </a>
                    </td>
                    <td class="column-status" data-colname="status">
                        <a href="<?php echo $ppn_url.$data->id ?>" aria-label="Edit">
                            <?php 
                            echo $data->total_ppn;
                            ?>
                        </a>
                    </td>
                    <td class="column-status" data-colname="status">
                        <a href="<?php echo $spt_url.$data->id ?>" aria-label="Edit">
                            <?php 
                            echo $data->total_spt;
                            ?>
                        </a>
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
        </table>
    </div>
</form>
    <?php
    // Display the paging information
    echo '<div id="paging"><p>', $prevlink, ' Halaman <b>', $page, '</b> dari <b>', $pages, '</b> , Menampilkan <b>', $start, '-', $end, '</b> dari <b>', $total, '</b> Hasil ', $nextlink, ' </p></div>';
    ?>
<?php 
}else if($is_add || $is_edit){

$list_admin = $wpdb->get_results("SELECT * FROM "._tbl_administrators." ORDER BY full_name ASC");
?>
<?php
if($is_edit){
    //kelola dokumen
    echo '<a href="'.$perizinan_url.$id.'" class="button button-primary">Kelola Perizinan</a>&nbsp;';
    echo '<a href="'.$hki_url.$id.'" class="button button-primary">Kelola HKI</a>&nbsp;';
    echo '<a href="'.$faktur_url.$id.'" class="button button-primary">Kelola Faktur</a>&nbsp;';
    echo '<a href="'.$ppn_url.$id.'" class="button button-primary">Kelola PPN</a>&nbsp;';
    echo '<a href="'.$spt_url.$id.'" class="button button-primary">Kelola SPT Tahunan</a>&nbsp;';
}
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
                <th scope="row"><label for="name">Nama Perusahaan</label></th>
                <td><input type="text" class="regular-text" name="company_name" value="<?php echo $company_name; ?>" maxlength="250" required></td>
            </tr>
            <tr>
                <th scope="row"><label for="name">Nama PIC</label></th>
                <td><input type="text" class="regular-text" name="pic_name" value="<?php echo $pic_name; ?>" maxlength="250"></td>
            </tr>
            <tr>
                <th scope="row"><label for="name">Alamat Perusahaan</label></th>
                <td><input type="text" class="regular-text" name="company_address" value="<?php echo $company_address; ?>" maxlength="250"></td>
            </tr>
            <tr>
                <th scope="row"><label for="name">Telephone</label></th>
                <td><input type="text" class="regular-text" name="phone" value="<?php echo $phone; ?>" maxlength="150"></td>
            </tr>
            <tr>
                <th scope="row"><label for="name">Fax</label></th>
                <td><input type="text" class="regular-text" name="fax" value="<?php echo $fax; ?>" maxlength="150"></td>
            </tr>
            <tr>
                <th scope="row"><label for="name">No. NPWP Perusahaan</label></th>
                <td><input type="text" class="regular-text" name="company_npwp" value="<?php echo $company_npwp; ?>" maxlength="150"></td>
            </tr>
            <tr>
                <th scope="row"><label for="name">Direktur Utama</label></th>
                <td><input type="text" class="regular-text" name="main_director" value="<?php echo $main_director; ?>" maxlength="150"></td>
            </tr>
            <tr>
                <th scope="row"><label for="name">NIB</label></th>
                <td><input type="text" class="regular-text" name="nib" value="<?php echo $nib; ?>" maxlength="150"></td>
            </tr>
            <tr>
                <th scope="row"><label for="name">Nomor Identitas</label></th>
                <td><input type="text" class="regular-text" name="identity_number" value="<?php echo $identity_number; ?>" maxlength="150"></td>
            </tr>
            <tr>
                <th scope="row"><label for="name">Website</label></th>
                <td><input type="text" class="regular-text" name="website" value="<?php echo $website; ?>" maxlength="150"></td>
            </tr>
            <tr>
                <th scope="row"><label for="name">NPWP</label></th>
                <td><input type="text" class="regular-text" name="npwp" value="<?php echo $npwp; ?>" maxlength="150"></td>
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

</div>