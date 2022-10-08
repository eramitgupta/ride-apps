<?php
$title = 'Password Change';
require_once('template/head.php');
?>
<style>
    .error{
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
                                <form method="post" id="FormData">
                                    <div class="row">
                                    <input type="hidden" name="id" value="<?= set_value('id',$AccountArray['id']); ?>">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="CurrentPassword" class="form-label">Current Password</label>
                                                <input type="text" class="form-control" name="CurrentPassword" id="CurrentPassword" placeholder="Enter Your Current Password">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label"> Password</label>
                                                <input type="text" class="form-control" name="password" id="password" placeholder="Enter Your Password" >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label"> Confirm Password</label>
                                                <input type="text" class="form-control" name="cpassword" id="cpassword" placeholder="Enter Your Confirm Password" >
                                            </div>
                                        </div>

                                    </div>
                                    <div>
                                        <center><button type="button" id="PasswordUpdate" class="btn btn-primary w-md mt-3">Update Account </button></center>
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
<script src="<?= base_url('public/js/code.js') ?>"></script>
