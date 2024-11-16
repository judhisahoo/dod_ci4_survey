<?= $this->extend('layouts/fe-login-register') ?>
<?= $this->section('content') ?>
<section class="section register  d-flex flex-column align-items-center justify-content-center py-4">
    <div class="container">

        <div class="row" style="height: 65px;">
            <h1 class="display-4 fb-bold">CMI Portal Register</h1>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-12 d-flex flex-column align-items-center justify-content-center">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="pt-4 pb-2">
                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <?php echo session()->getFlashdata('error'); ?>
                                </div>
                            <?php endif; ?>

                            <?php
                            if (session()->getFlashdata('validation')):
                                $dataArr = session()->getFlashdata('validation');
                            ?>
                                <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <?php foreach ($dataArr as $k => $v): ?>
                                        <p class="text-start small" style="margin-bottom: 0.25rem!important;">
                                            <?= $v ?>
                                        </p>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <?php if (session()->getFlashdata('success')): ?>
                                <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <?php echo session()->getFlashdata('success'); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <form action="<?php echo base_url('survey-register'); ?>" method="post" class="row g-3 needs-validation" novalidate>
                            <?= csrf_field() ?>
                            <div class="col-6">
                                <label for="fname" class="form-label float-start form-label-required">Your full Name</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-person"></i></span>
                                    <input type="text" name="name" class="form-control" id="name" required>
                                    <div class="invalid-feedback text-start">Please, enter your full name!</div>
                                </div>
                            </div>

                            <div class="col-6">
                                <label for="phone" class="form-label float-start">Your Phone</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-phone"></i></span>
                                    <input type="text" name="phone" class="form-control" id="phone">

                                    <div class="invalid-feedback text-start">Please, enter your phone!</div>
                                </div>
                            </div>

                            <div class="col-6">
                                <label for="yourEmail" class="form-label float-start form-label-required">Your Email</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-envelope"></i></span>
                                    <input type="text" name="email" class="form-control" id="YourEmail" required>
                                    <div class="invalid-feedback text-start">Please Enter a email.</div>
                                </div>
                            </div>

                            <div class="col-6">
                                <label for="lname" class="form-label float-start form-label-required">Select User Type</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-person-lines-fill"></i></span>
                                    <select class="form-select" id="user_type" name="user_type" style="width: 300px;" required>
                                        <option value="">&nbsp;</option>
                                        <option value="1">Employer</option>
                                        <option value="2">Institution</option>
                                    </select>
                                    <div class="invalid-feedback text-start">Please, select user type!</div>
                                </div>
                            </div>

                            <div class="col-6">

                                <label for="address" class="form-label float-start">Enter Your Address</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-key"></i></span>
                                    <input type="text" name="address" class="form-control" id="address">
                                    <div class="invalid-feedback text-start">Please enter your address!</div>
                                </div>
                            </div>

                            <div class="col-6">
                                <label for="yourPassword" class="form-label float-start form-label-required">Password</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-key"></i></span>
                                    <input type="password" name="password" class="form-control" id="yourPassword" required>
                                    <div class="invalid-feedback text-start">Please enter your password!</div>
                                </div>
                            </div>

                            <div class="col-6">

                                <label for="yourPassword" class="form-label float-start form-label-required">Confirm Your Password</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-key"></i></span>
                                    <input type="password" name="confirm_password" class="form-control" id="confirm_password" required>
                                    <div class="invalid-feedback text-start">Please enter your conirm password!</div>
                                </div>
                            </div>

                            <!--<div class="col-12">
            <div class="form-check">
                <input class="form-check-input" name="terms" type="checkbox" value="" id="acceptTerms" required>
                <label class="form-check-label" for="acceptTerms">I agree and accept the <a href="#">terms and conditions</a></label>
                <div class="invalid-feedback">You must agree before submitting.</div>
            </div>
        </div>-->
                            <div class="col-12">
                                <button class="btn btn-primary w-100" type="submit">Create Account</button>
                            </div>
                            <div class="col-12">
                                <p class="small mb-0">Already have an account? for Survey <a href="<?php echo base_url('/login'); ?>">Survey Log in</a></p>
                            </div>
                        </form>

                    </div>
                </div>


            </div>
        </div>
    </div>

</section>
</main><!-- End #main -->
<?= $this->endSection() ?>