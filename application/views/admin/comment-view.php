<?php
$title = 'All Comment Details';
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

                <div class="d-lg-flex">
                    <div class="chat-leftsidebar me-lg-4">
                        <div class="">
                            <div class="chat-leftsidebar-nav">
                                <ul class="nav nav-pills nav-justified">
                                    <li class="nav-item">
                                        <a href="#chat" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                                            <i class="bx bx-chat font-size-20 d-sm-none"></i>
                                            <span class="d-none d-sm-block">Post Details</span>
                                        </a>
                                    </li>

                                </ul>


                                <div class="tab-content py-4">
                                    <div class="tab-pane show active" id="chat">
                                        <div>
                                            <h5 class="font-size-14 mb-3">Post</h5>
                                            <ul class="list-unstyled chat-list" data-simplebar style="max-height: 410px;">
                                                <?php
                                                if (!empty($ArrayComment)) {
                                                ?>
                                                    <li class="active">
                                                        <a href="javascript: void(0);">

                                                            <div class="mb-5">
                                                                <h5 class="text-truncate font-size-14 mb-1"><?= ucwords($ArrayComment[0]['name']); ?></h5>
                                                                <p class="text-truncate mb-0"><?= ucwords($ArrayComment[0]['text']); ?></p>
                                                            </div>

                                                            <div class="d-flex">

                                                                <img src="<?= base_url('uploads/create_ride/' . $ArrayComment[0]['image']); ?>" alt="" style="250px; width:auto;margin-left: auto; margin-right: auto;">
                                                            </div>
                                                        </a>
                                                    </li>
                                                <?php } ?>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="w-100 user-chat">
                        <div class="card">
                            <div class="p-4 border-bottom ">
                                <div class="row">
                                    <div class="col-md-4 col-9">
                                        <h5 class="font-size-15 mb-1">All Comment</h5>
                                    </div>
                                </div>
                            </div>


                            <div>
                                <div class="chat-conversation p-3">
                                    <ul class="list-unstyled mb-0" data-simplebar style="max-height: 486px;">
                                        <?php
                                        foreach ($ArrayComment as $row) {
                                        ?>
                                            <li class="right">
                                                <div class="conversation-list">
                                                    <div class="ctext-wrap">
                                                        <div class="conversation-name">
                                                            <?php
                                                            $user = $this->db->where('id', $row['tbl_ride_comment_user_id'])->get('tbl_login')->row();
                                                            echo $user->name;
                                                            ?>
                                                        </div>
                                                        <p>
                                                           <?= $row['comments'] ?>
                                                        </p>
                                                        <p class="chat-time mb-0"><i class="bx bx-time-five align-middle me-1"></i> <?= $row['date'] ?></p>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php
                                        }
                                        ?>

                                    </ul>
                                </div>
                                <div class="p-3 chat-input-section">
                                    <div class="row">
                                        <div class="col-auto">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- End Page-content -->
            </div>
        </div>
    </div>
    <!-- end main content-->
</div>
<!-- END layout-wrapper -->
<?php require_once('template/script.php'); ?>