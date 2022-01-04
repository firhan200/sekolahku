<?php
global $wpdb;
$table_users_name = _tbl_users;
$table_name = _tbl_spt_tahunan;

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
$action_label = "SPT Tahunan";
if(count($errors) < 1){
    $action_label .= " (<a href='".admin_url('/admin.php?page=users&action_type=edit&id='.$user->id)."'>".$user->company_name."</a>)";
}
if($action_type != null){
    $tahun = date('Y');
    $attachment_id = null;
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
            $tahun = $data->tahun;
            $attachment_id = $data->attachment_id;
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
$modul_name = 'spt';
$list_url = admin_url('/admin.php?page='.$modul_name.'&user_id='.$user->id);
$add_url = $list_url.'&action_type=add';
$edit_url = $list_url.'&action_type=edit&id=';
$delete_url = $list_url.'&action_type=delete&id=';

//check if submit
if(count($errors) < 1){
    if($_POST['submit']){
        $user_id = $_POST['user_id'];
        $tahun = $_POST['tahun'];
        $attachment_id = $_POST['attachment_id'];
        $status = $_POST['status'];

        //validation
        if(empty($tahun)){
            $errors[] = '<b>Bulan</b> Tidak Boleh Kosong';
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
                        'tahun' => $tahun,
                        'attachment_id' => $attachment_id,
                        'status' => $status
                    ),
                    array(
                        '%d',
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
                        'tahun' => $tahun,
                        'attachment_id' => $attachment_id,
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
                <th scope="col" id="description" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Tahun SPT</span>
                    </a>
                </th>
                <th scope="col" id="description" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Link Dokumen</span>
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
                        Pilih <?php echo $data->tahun; ?>
                    </label>
                    <input id="cb-select-1" type="checkbox" name="post[]" value="<?php echo $data->id; ?>">
                    <div class="locked-indicator">
                        <span class="locked-indicator-icon" aria-hidden="true"></span>
                        <span class="screen-reader-text">
                            “<?php echo $data->tahun; ?>” terkunci
                        </span>
                    </div>
                </th>
                <td class="title column-title has-row-actions column-primary page-name">
                    <?php echo $data->tahun; ?>

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
                    <?php echo '<a href="'.wp_get_attachment_url($data->attachment_id).'" target="_blank">'.wp_get_attachment_url($data->attachment_id).'</a>'; ?>
                </td>
                <td class="column-status" data-colname="status">
                    <?php 
                        if($data->status == PERIZINAN_PENDING){ 
                            echo '<span class="badge bg-success">Pending</span>';
                        }else if($data->status == PERIZINAN_ON_PROGRESS){
                            echo '<span class="badge bg-danger">On-Progress</span>';
                        }else if($data->status == PERIZINAN_DONE){
                            echo '<span class="badge bg-danger">Done</span>';
                        }else if($data->status == PERIZINAN_CANCELLED){
                            echo '<span class="badge bg-danger">Cancelled</span>';
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
                        <span>Tahun SPT</span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>Link Dokumen</span>
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
    wp_enqueue_media();
?>
<form method="post">
    <input type="hidden" name="submit" value="true"/>
    <input type="hidden" name="user_id" value="<?php echo $user->id; ?>"/>
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row"><label for="tahun">Tahun</label></th>
                <td>
                    <select name="tahun" required>
                        <?php
                        for($year = 1960; $year <= 2030; $year++){
                            $isSelected = $tahun == $year ? 'selected' : '';
                            echo '<option value="'.$year.'" '.$isSelected.'>'.$year.'</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="name">File</label></th>
                <td>
                    <div id='link-preview'>
                        <?php if($attachment_id != null && $attachment_id != ''){ ?>
                        <a href="<?php echo wp_get_attachment_url($attachment_id) ?>" target="_blank"><?php echo wp_get_attachment_url($attachment_id) ?></a>
                        <br/>
                        <br/>
                        <?php } ?>
                    </div>
                    <input type='hidden' name='attachment_id' id='attachment_id' value='<?php echo $attachment_id == null ? get_option( 'media_selector_attachment_id' ) : $attachment_id; ?>'>
                    <input id="upload_image_button" type="button" class="button" value="<?php _e( 'Browse File' ); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="name">Status</label></th>
                <td>
                    <select name="status" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="<?php echo PERIZINAN_PENDING ?>" <?php echo $status == PERIZINAN_PENDING ? 'selected' : '' ?>><?php echo 'Pending'; ?></option>
                        <option value="<?php echo PERIZINAN_ON_PROGRESS ?>" <?php echo $status == PERIZINAN_ON_PROGRESS ? 'selected' : '' ?>><?php echo 'On-Progress'; ?></option>
                        <option value="<?php echo PERIZINAN_DONE ?>" <?php echo $status == PERIZINAN_DONE ? 'selected' : '' ?>><?php echo 'Done'; ?></option>
                        <option value="<?php echo PERIZINAN_CANCELLED ?>" <?php echo $status == PERIZINAN_CANCELLED ? 'selected' : '' ?>><?php echo 'Cancelled'; ?></option>
                    </select>
                </td>
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


<?php
$my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 );
?>
<script type='text/javascript'>
    jQuery( document ).ready( function( $ ) {
        // Uploading files
        var file_frame;
        var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
        var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
        jQuery('#upload_image_button').on('click', function( event ){
            event.preventDefault();
            // If the media frame already exists, reopen it.
            if ( file_frame ) {
                // Set the post ID to what we want
                file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
                // Open frame
                file_frame.open();
                return;
            } else {
                // Set the wp.media post id so the uploader grabs the ID we want when initialised
                wp.media.model.settings.post.id = set_to_post_id;
            }
            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Select a image to upload',
                button: {
                    text: 'Use this image',
                },
                multiple: false // Set to true to allow multiple files to be selected
            });
            // When an image is selected, run a callback.
            file_frame.on( 'select', function() {
                // We set multiple to false so only get one image from the uploader
                attachment = file_frame.state().get('selection').first().toJSON();

                console.log(attachment);
                // Do something with attachment.id and/or attachment.url here
                //$( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
                $( '#attachment_id' ).val( attachment.id );

                $('#link-preview').html('<a href="'+attachment.url+'" target="_blank">'+attachment.url+'</a><br/><br/>');
                // Restore the main post ID
                wp.media.model.settings.post.id = wp_media_post_id;
            });
                // Finally, open the modal
                file_frame.open();
        });
        // Restore the main ID when the add media button is pressed
        jQuery( 'a.add_media' ).on( 'click', function() {
            wp.media.model.settings.post.id = wp_media_post_id;
        });
    });
</script>