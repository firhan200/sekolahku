<div class="container main-container">

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
if(count($errors) < 1){
    $action_label .= " (<a href='".site_url('/')._admin_pages_home.'?action_type=edit&id='.$user->id."'>".$user->company_name."</a>)";
}
if($action_type != null){
    $bulan_pajak = date('m');
    $tahun_pajak = date('Y');
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
            $bulan_pajak = $data->bulan_pajak;
            $tahun_pajak = $data->tahun_pajak;
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
                        'bulan_pajak' => $bulan_pajak,
                        'tahun_pajak' => $tahun_pajak,
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

<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo $action_label; ?>
    </h1>

    <?php if($is_list){ ?>
    <a href="<?php echo $add_url; ?>" class="page-title-action btn btn-sm btn-primary">Tambah Baru</a>
    <?php } ?>

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
            <tfoot>
                <tr>
                    <th scope="col" class="manage-column column-title column-primary sortable desc">
                            <span>Tanggal Pajak</span>
                    </th>
                    <th scope="col" class="manage-column column-title column-primary sortable desc">
                            <span>Link Dokumen</span>
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
    wp_enqueue_media();
?>
<form class="box p-5" method="post">
    <input type="hidden" name="submit" value="true"/>
    <input type="hidden" name="user_id" value="<?php echo $user->id; ?>"/>
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row"><label for="bulan_pajak">Bulan Pajak</label></th>
                <td>
                    <select name="bulan_pajak" required>
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
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="tahun_pajak">Tahun Pajak</label></th>
                <td>
                    <select name="tahun_pajak" required>
                        <?php
                        for($year = 1960; $year <= 2030; $year++){
                            $isSelected = $tahun_pajak == $year ? 'selected' : '';
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
                    <input type='hidden' name='custom_attachment_id' id='attachment_id' value='<?php echo $attachment_id == null ? get_option( 'media_selector_attachment_id' ) : $attachment_id; ?>'>
                    <input id="upload_image_button" type="button" class="button btn btn-primary" value="<?php _e( 'Browse File' ); ?>" />
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
                    <a href="<?php echo $list_url; ?>" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-primary">Submit</button>
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


<?php
$my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 );
?>
<script type="text/javascript" src="<?php echo $my_plugin ?>/assets/js/jquery-3.6.0.min.js"></script>
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