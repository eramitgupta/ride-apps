<?php
require_once('template/head.php');
?>
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
                                <form method="POST" enctype="multipart/form-data" action="<?= base_url('admin/smtp/update'); ?>">
                                    <input type="hidden"  name="id" value="<?= $smtpArray[0]['id'] ?>">
                                    <div class="row">


                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label"> Title </label>
                                                <input type="text" class="form-control" name="title" id="smtp_host" placeholder="title" value="<?= $smtpArray[0]['title'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label"> Email </label>
                                                <input type="text" class="form-control" name="email" id="email" placeholder="email" value="<?= $smtpArray[0]['email'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label"> Protocol </label>
                                                <input type="text" class="form-control" name="protocol" id="protocol" placeholder="Bike Model" value="<?= $smtpArray[0]['protocol'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label"> Smtp Host </label>
                                                <input type="text" class="form-control" name="smtp_host" id="smtp_host" placeholder="Bike Model" value="<?= $smtpArray[0]['smtp_host'] ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="name" class="form-label"> Smtp Port </label>
                                                <input type="text" class="form-control" name="smtp_port" id="smtp_port" placeholder="Bike Model" value="<?= $smtpArray[0]['smtp_port'] ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="name" class="form-label"> Smtp User Email </label>
                                                <input type="text" class="form-control" name="smtp_user_email" id="smtp_user_email" placeholder="Bike Model" value="<?= $smtpArray[0]['smtp_user_email'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="name" class="form-label"> Smtp Pass </label>
                                                <input type="text" class="form-control" name="smtp_pass" id="smtp_pass" placeholder="Bike Model" value="<?= $smtpArray[0]['smtp_pass'] ?>">
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