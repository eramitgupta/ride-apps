<?php
$title = 'Post-Details';
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
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-tabs-custom justify-content-center pt-2" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#all-post" role="tab">
                                        All Post Group <?= $ArrayGroup[0]['name']; ?>
                                    </a>
                                </li>

                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content p-4">
                                <div class="tab-pane active" id="all-post" role="tabpanel">
                                    <div>
                                        <div class="row justify-content-center">
                                            <div class="col-xl-8">
                                                <div>
                                                    <hr class="mb-4">
                                                    <?php
                                                    foreach ($ArrayGroupCommunityPost as $row) {
                                                        $CountComment = $this->db->where(['post_id'=>$row['PostID']])->count_all_results('tbl_ride_comment');
                                                        $sharePost = $this->db->where(['post_id'=>$row['PostID']])->count_all_results('tbl_share_post');

                                                    ?>
                                                        <div>
                                                            <h5><a  class="text-dark"> <?= $row['name'] ?></a></h5>
                                                            <!-- <p class="text-muted">10 Apr, 2020</p> -->
                                                            <div class="position-relative mb-3">
                                                                <?php
                                                                $img = json_decode($row['images']);
                                                                for ($i = 0; $i < count($img); $i++) {
                                                                    echo '<img src="'.base_url('uploads/group_post/'.$img[$i]).'" class="img-thumbnail">';
                                                                }
                                                                ?>
                                                            </div>

                                                            <ul class="list-inline">
                                                                <li class="list-inline-item me-3">
                                                                    <a href="javascript: void(0);" class="text-muted">
                                                                        <i class="bx bx-purchase-tag-alt align-middle text-muted me-1"></i> <?= $row['tag'] ?>
                                                                    </a>
                                                                </li>
                                                                <li class="list-inline-item me-3">
                                                                    <a href="javascript: void(0);" class="text-muted">
                                                                        <i class="bx bx-comment-dots align-middle text-muted me-1"></i> <?= $CountComment ?> Comments
                                                                    </a>
                                                                </li>
                                                                <li class="list-inline-item me-3">
                                                                    <a href="javascript: void(0);" class="text-muted">
                                                                        <i class="bx bx-share-alt align-middle text-muted me-1"></i> <?= $sharePost ?> Share
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                            <p><?= $row['text'] ?></p>
                                                        </div>
                                                        <hr class="my-5">
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
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