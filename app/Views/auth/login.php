<?= $this->extend('layouts/admin-login-register') ?>
<?= $this->section('content') ?>
<div class="container">

  <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

          <div class="d-flex justify-content-center py-4">
            <a href="<?php echo base_url(); ?>adminpanel" class="logo d-flex align-items-center w-auto">
              <img src="assets/img/logo.png" alt="">
              <span class="d-none d-lg-block">Admin <?php echo $title; ?></span>
            </a>
          </div><!-- End Logo -->

          <div class="card mb-3">

            <div class="card-body">

              <div class="pt-4 pb-2">
                <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                <?php if (session()->getFlashdata('error')): ?>
                  <p class="text-center small"><?php echo session()->getFlashdata('error'); ?></p>
                <?php endif; ?>
              </div>

              <form action="<?php echo base_url('loginProcess'); ?>" method="post" class="row g-3 needs-validation" novalidate>

                <?= csrf_field() ?>
                <div class="col-12">
                  <label for="yourUsername" class="form-label float-start">Username</label>
                  <div class="input-group has-validation">
                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-envelope"></i></span>
                    <input type="text" name="email" class="form-control" id="email" required>
                    <div class="invalid-feedback text-start">Please enter your email.</div>
                  </div>
                </div>

                <div class="col-12">
                  <label for="yourPassword" class="form-label float-start">Password</label>
                  <div class="input-group has-validation">
                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-key"></i></span>
                    <input type="password" name="password" class="form-control" id="yourPassword" required>
                    <div class="invalid-feedback text-start">Please enter your password!</div>
                  </div>
                </div>

                <!--<div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                      </div>
                    </div>-->
                <div class="col-12">
                  <button class="btn btn-primary w-100" type="submit">Login</button>
                </div>
                <!--<div class="col-12">
                      <p class="small mb-0">Don't have account? <a href="<?php //echo base_url('/admin-register');
                                                                          ?>">Create an account</a></p>
                    </div>-->
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