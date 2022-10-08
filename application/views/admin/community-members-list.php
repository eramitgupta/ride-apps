<?php
$title = 'Community-Members-List';
require_once('template/head.php');
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css">
<!--datatable -->
<div id="layout-wrapper">

    <?php require_once('template/header.php'); ?>
    <!-- ========== Left Sidebar Start ========== -->
    <?php require_once('template/side-menu.php'); ?>
    <!-- Left Sidebar End -->
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18"><?= $title ?></h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="text-center"><?= $ArrayGroup[0]['name']; ?></h5>
                                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                                            <div class="card">
                                                <table id="datatable" class="table table-bordered  dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>S.N</th>
                                                            <th>UserName</th>
                                                            <th>Role</th>
                                                            <th>Status</th>
                                                            <th>Date Time</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $x = 1;
                                                        foreach ($ArrayGroupMmembers as $row) {
                                                        ?>
                                                            <tr>
                                                                <td><?= $x ?></td>
                                                                <td><?= ucfirst($row['name']) ?></td>
                                                                <td><?= ucfirst($row['role']) ?></td>

                                                                <td>
                                                                    <?php
                                                                    if ($row['CommunityStatus'] == 'Active') {
                                                                        $color = 'success';
                                                                        $status = $row['CommunityStatus'];
                                                                    } else {
                                                                        $color = 'danger';
                                                                        $status = $row['CommunityStatus'];
                                                                    }
                                                                    ?>
                                                                    <a href="<?= base_url('admin/community/StatusMembers/?id=' . $row['CommunityID'] . '&status=' . $row['CommunityStatus'].'&GroupID=' . $ArrayGroup[0]['id']) ?>" class="btn btn-<?= $color; ?> btn-rounded waves-effect waves-light"><?= $status ?>
                                                                    </a>
                                                                </td>

                                                                <td><?= $row['date_time'] ?></td>

                                                                <td>
                                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                                        <a href="<?= base_url('admin/community/mypost/'.$ArrayGroup[0]['id'].'/' . $row['user_id']); ?>" class="btn btn-primary" title="Group Members">
                                                                            <i class="bx bxs-user-detail"></i>
                                                                        </a>

                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                            $x++;
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div> <!-- end col -->
                                </div> <!-- end row -->

                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- End Page-content -->
            </div>
        </div>
    </div>
    <!-- end main content-->
</div>
<!-- END layout-wrapper -->
<?php require_once('template/script.php'); ?>
<script src="<?= base_url('public/js/code.js') ?>"></script>
<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excel', 'pdf', 'print'
            ]
        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>