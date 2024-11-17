<?= $this->extend('layouts/admin-login-register') ?>
<?= $this->section('content') ?>
<div class="container">

    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                    <div class="d-flex justify-content-center py-4">
                        <a href="index.html" class="logo d-flex align-items-center w-auto">
                            <img src="assets/img/logo.png" alt="">
                            <span class="d-none d-lg-block">Admin <?php echo $title; ?></span>
                        </a>
                    </div><!-- End Logo -->

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

                            <form action="<?php echo base_url('registerProcess'); ?>" method="post" class="row g-3 needs-validation" novalidate>
                                <?= csrf_field() ?>
                                <div class="col-12">
                                    <label for="fname" class="form-label">Your first Name</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-person"></i></span>
                                        <input type="text" name="fname" class="form-control" id="fname" required>
                                        <div class="invalid-feedback">Please, enter your first name!</div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="lname" class="form-label">Your Last Name</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-person"></i></span>
                                        <input type="text" name="lname" class="form-control" id="lname" required>
                                        <div class="invalid-feedback">Please, enter your last name!</div>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <label for="yourEmail" class="form-label">Your Email</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                                        <input type="text" name="email" class="form-control" id="YourEmail" required>
                                        <div class="invalid-feedback">Please Enter a email.</div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="yourPassword" class="form-label">Password</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-key"></i></span>
                                        <input type="password" name="password" class="form-control" id="yourPassword" required>
                                        <div class="invalid-feedback">Please enter your password!</div>
                                    </div>
                                </div>

                                <div class="col-12"><label for="yourPassword" class="form-label">Confirm Your Password</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-key"></i></span>
                                        <input type="password" name="confirm_password" class="form-control" id="confirm_password" required>
                                        <div class="invalid-feedback">Please enter your conirm password!</div>
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
                                    <p class="small mb-0">Already have an account? <a href="<?php echo base_url('/admin-login'); ?>">Log in</a></p>
                                </div>
                            </form>

                        </div>
                    </div>


                </div>
            </div>
        </div>

    </section>

</div>
</main><!-- End #main -->
<?= $this->endSection() ?>