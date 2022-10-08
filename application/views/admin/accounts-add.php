<?php
$title = 'Accounts Add';
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
                                <form method="POST" enctype="multipart/form-data" action="<?= base_url('admin/authentication/account_add'); ?>" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="name" class="form-label"> Name</label>
                                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Your First Name" value="<?= set_value('name'); ?>">
                                                <?= form_error('name'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="role" class="form-label"> Role</label>
                                                <select class="form-control" name="role">
                                                        <option value="">Select</option>
                                                        <option value='user'>User</option>
                                                        <option value='admin'>Admin</option>
                                                </select>
                                                <?= form_error('role'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Username</label>
                                                <input type="text" class="form-control" name="username" id="username" placeholder="Enter Your Username" value="<?= set_value('username'); ?>">
                                                <?= form_error('username'); ?>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="mobile" class="form-label">Mobile</label>
                                                <input type="number" class="form-control" name="mobile" id="mobile" placeholder="Enter Your Mobile" value="<?= set_value('mobile'); ?>">
                                                <?= form_error('mobile'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter Your Email" value="<?= set_value('email'); ?>">
                                                <?= form_error('email'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="file" class="form-label">Photo</label>
                                                <input type="file" class="form-control" id="file" name="file" placeholder="Enter Your file">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter Your Password" value="<?= set_value('password'); ?>">
                                                <?= form_error('password'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Confirm Password</label>
                                                <input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="Enter Your Confirm Password" value="<?= set_value('cpassword'); ?>">
                                                <?= form_error('cpassword'); ?>
                                            </div>
                                        </div>

                                    </div>

                                    <div>
                                        <center><button type="submit" class="btn btn-primary w-md">Submit </button></center>
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