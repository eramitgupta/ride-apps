<?php require_once('head.php'); ?>

<div class="account-pages my-5 pt-sm-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card">
                    <div class="card-body">
                        <div class="p-2">
                            <div class="text-center">

                                <div class="avatar-md mx-auto">
                                    <div class="avatar-title rounded-circle bg-light">
                                        <i class="bx bxs-envelope h1 mb-0 text-primary"></i>
                                    </div>
                                </div>
                                <div class="p-2 mt-4">
                                    <h4>Verify your email</h4>
                                    <p class="mb-5">Please enter the 4 digit code sent to
                                        <br>
                                        <span class="fw-semibold">example@abc.com</span>
                                        <br>
                                        <a href="<?= base_url('login/forgotPasswordSession'); ?>">Change your account</a>
                                    </p>
                                    <form method="post" id="FormData">
                                        <div class="row" id="otpVerificationRow">
                                            <div class="col-3">
                                                <div class="mb-3">
                                                    <label for="digit1-input" class="visually-hidden">Dight 1</label>
                                                    <input type="text" name="digit1-input" class="form-control form-control-lg text-center two-step" maxLength="1" data-value="1" id="digit1-input">
                                                </div>
                                            </div>

                                            <div class="col-3">
                                                <div class="mb-3">
                                                    <label for="digit2-input" class="visually-hidden">Dight 2</label>
                                                    <input type="text" class="form-control form-control-lg text-center two-step" maxLength="1" data-value="2" id="digit2-input" name="digit2-input">
                                                </div>
                                            </div>

                                            <div class="col-3">
                                                <div class="mb-3">
                                                    <label for="digit3-input" class="visually-hidden">Dight 3</label>
                                                    <input type="text" class="form-control form-control-lg text-center two-step" maxLength="1" data-value="3" id="digit3-input" name="digit3-input">
                                                </div>
                                            </div>

                                            <div class="col-3">
                                                <div class="mb-3">
                                                    <label for="digit4-input" class="visually-hidden">Dight 4</label>
                                                    <input type="text" class="form-control form-control-lg text-center two-step" maxLength="1" data-value="4" id="digit4-input" name="digit4-input">
                                                    <input type="hidden" id="email" name="email" value="<?= $forgot_password_check['email']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <span id="timer"></span>
                                        <div class="mt-4">
                                            <button type="button" name="otpVerification" id="otpVerification" class="btn btn-primary w-md">Confirm</button>
                                            <button type="button" class="btn btn-success w-md" id="resent" name="resent">Resent</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                <script>
                    let timerOn = true;
                    function timer(remaining) {
                        $("#resent").attr("disabled", "disabled");
                        var m = Math.floor(remaining / 60);
                        var s = remaining % 60;
                        m = m < 10 ? '0' + m : m;
                        s = s < 10 ? '0' + s : s;
                        document.getElementById('timer').innerHTML = m + ':' + s;
                        remaining -= 1;
                        if (remaining >= 0 && timerOn) {
                            setTimeout(function() {
                                timer(remaining);
                            }, 1000);
                            return;
                        }
                        if (!timerOn) {
                            // Do validate stuff here
                            return;
                        }
                        $("#resent").removeAttr("disabled", "disabled");
                    }
                    timer(120);
                </script>

                <script src="<?= base_url('public/assets/js/pages/two-step-verification.init.js') ?>"></script>
                <?php require_once('footer.php'); ?>