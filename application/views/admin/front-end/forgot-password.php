<?php require_once('head.php'); ?>

<div class="account-pages my-5 pt-sm-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card overflow-hidden">
                    <div class="bg-primary bg-soft">
                        <div class="row">
                            <div class="col-7">
                                <div class="text-primary p-4">
                                    <h5 class="text-primary">Reset Password !</h5>

                                </div>
                            </div>
                            <div class="col-5 align-self-end">
                                <img src="<?= base_url() . 'public/' ?>assets/images/profile-img.png" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0 mt-3">
                        <div class="p-2">
                            <form class="form-horizontal mb-3" method="POST" id="FormData">

                                <div class="mb-3">
                                    <label for="username" class="form-label">Username Or Email And Mobile</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="">
                                    <?php echo form_error('username'); ?>
                                </div>

                                <div class="mt-3 d-grid">
                                    <button class="btn btn-primary waves-effect waves-light" id="passwordReset" name="passwordReset" type="button">Submit</button>
                                </div>

                                <div class="mt-4 text-center">
                                    <a href="<?= base_url('') ?>" class="text-muted"><i class="mdi mdi-lock me-1"></i>Back to login?</a>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

                <?php require_once('footer.php'); ?>