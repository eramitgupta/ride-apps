
<?php
        $title = 'Home - Dashboard';
        require_once('template/head.php');
?>
<!-- Begin page -->
<div id="layout-wrapper">
    <?php require_once('template/array.php'); ?>
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
                            <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card overflow-hidden">
                            <div class="bg-primary bg-soft">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="text-primary p-3">
                                            <h5 class="text-primary">Welcome Back !</h5>
                                            <p><?= $welcome ; ?></p>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <!-- end row -->




                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                <?php require_once('template/footer.php'); ?>

            </div>
            <!-- end main content-->
        </div>
        <!-- END layout-wrapper -->



        <?php require_once('template/script.php'); ?>