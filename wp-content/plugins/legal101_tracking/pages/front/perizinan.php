<?php
global $wpdb;
$menu_perizinan = true;

//get profile
$list_perizinan = $wpdb->get_results("SELECT p.*, u.company_name AS company_name, u.company_address AS company_address FROM "._tbl_perizinan." AS p LEFT JOIN "._tbl_users." AS u ON p.user_id=u.id WHERE p.user_id = '".$_SESSION[SESSION_ID]."'");
?>

<div class="container main-container">
    <div class="row">
        <div class="col-12">
            <h2 class="text-center mb-3 section-heading">Project Status Perizinan</h2>
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-3">
                    <thead>
                        <tr>
                            <th>Description Job</th>
                            <th>Customer</th>
                            <th>Location</th>
                            <th>Target Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($list_perizinan as $perizinan){
                            echo '<tr>';
                            echo '<td>'.htmlspecialchars($perizinan->description).'</td>';
                            echo '<td>'.htmlspecialchars($perizinan->company_name).'</td>';
                            echo '<td>'.htmlspecialchars($perizinan->company_address).'</td>';
                            echo '<td>'.date("d/m/Y", strtotime($perizinan->target_date)).'</td>';
                            echo '<td>';

                            if($perizinan->status == PERIZINAN_PENDING){
                                echo '<span class="badge bg-secondary">Pending</span>';
                            }
                            else if($perizinan->status == PERIZINAN_ON_PROGRESS){
                                echo '<span class="badge bg-warning">On-Progress</span>';
                            }
                            else if($perizinan->status == PERIZINAN_DONE){
                                echo '<span class="badge bg-success">Done</span>';
                            }
                            else if($perizinan->status == PERIZINAN_CANCELLED){
                                echo '<span class="badge bg-danger">Cancelled</span>';
                            }

                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <strong>Progress Message:</strong>
            <div class="bg-light p-4">
                <?php
                echo '<ul>';
                foreach($list_perizinan as $perizinan){
                    echo '<li>'.htmlspecialchars($perizinan->progress_message).'</li>';
                }
                echo '</ul>';
                ?>
            </div>
        </div>
    </div>
</div>