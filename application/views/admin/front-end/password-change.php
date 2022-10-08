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
                                    <h5 class="text-primary">New Password !</h5>

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
                                    <label class="form-label">Password</label>
                                    <div class="input-group auth-pass-inputgroup">
                                        <input type="password" class="form-control" placeholder="Enter password" name="password" id="password" aria-label="Password" aria-describedby="password-addon">
                                        <button class="btn btn-light " type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <div class="input-group auth-pass-inputgroup">
                                        <input type="password" class="form-control" placeholder="Enter confirm password" name="cpassword" id="cpassword" aria-label="cPassword" aria-describedby="password-addon">
                                        <input type="hidden" name="email" id="email" value="<?= $email['email']; ?>">
                                        <button class="btn btn-light " type="button" id="password-addon"></button>
                                    </div>
                                </div>

                                <div class="mt-3 d-grid">
                                    <button class="btn btn-primary waves-effect waves-light" id="Newpassword" name="Newpassword" type="button">Change</button>
                                </div>

                                <div class="mt-4 text-center">
                                    <a href="<?= base_url('') ?>" class="text-muted"><i class="mdi mdi-lock me-1"></i>Back to login?</a>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

                <?php require_once('footer.php'); ?>