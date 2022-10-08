<?php
$title = 'Api Details';
require_once('template/head.php');
?>
<style>
    .error {
        color: red;
    }

    span {
        color: #34c38f !important;
    }

    p {
        color: white !important;
    }
    li {
        color: white !important;
    }
</style>
<!-- Begin page -->
<div id="layout-wrapper">

    <?php require_once('template/header.php'); ?>
    <!-- ========== Left Sidebar Start ========== -->
    <?php require_once('template/side-menu.php'); ?>
    <!-- Left Sidebar End -->
    <div class="main-content bg-dark">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18 text-white"><?= $title ?></h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <p>
                        Api UserName - <span>API-KEY</span>
                        <br>
                        Api Key - <span>1d7a8eb389ddbb0f4b7ee0f5</span>
                    </p>

                    <p>
                        Basic Authentication Username - <span>admin</span>
                        <br>
                        Password - <span>1234</span>
                    </p>

                    <hr class="text-white">

                    <p>Sign Up Api - Method POST</p>
                    <p>
                        URL <span><?= base_url('/api/AppApi/Signup') ?></span>
                        <br>
                    </p>
                    <p> Parameters & Fields
                        <li class='parameters'>name</li>
                        <li>username</li>
                        <li>mobile</li>
                        <li>email</li>
                        <li>password</li>
                    </p>

                </div>

                <!-- End Page-content -->
            </div>
        </div>
    </div>
    <!-- end main content-->
</div>
<!-- END layout-wrapper -->
<?php require_once('template/script.php'); ?>