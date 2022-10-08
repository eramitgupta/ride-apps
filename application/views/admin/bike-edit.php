<?php
$title = 'Bike Edit';
require_once('template/head.php');
?>
<style>
    .error {
        color: red;
    }
</style>
<!-- Begin page -->
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
                                <form method="POST" enctype="multipart/form-data" action="<?= base_url('admin/bike/editBike'); ?>">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label"> Bike Model </label>
                                                <input type="hidden" name="id" value="<?= $ArrayBike[0]['id']; ?>">
                                                <input type="text" class="form-control" name="model" id="bikeModel" placeholder="Bike Model" value="<?= $ArrayBike[0]['model']; ?>">
                                                <?= form_error('bikeModel'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label"> Bike Name </label>
                                                <input type="text" class="form-control" name="brand" id="bikeName" placeholder="Bike Name" value="<?= $ArrayBike[0]['brand']; ?>">
                                                <?= form_error('bikeName'); ?>
                                            </div>
                                        </div>


                                    </div>

                                    <div>
                                        <center><button type="submit" class="btn btn-primary w-md">Update </button></center>
                                    </div>
                                </form>
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