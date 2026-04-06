    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="card card-primary">
              <div class="card-header">
                <h4>Login</h4>
              </div>
              <div class="card-body">
                <form method="POST" action="<?php echo url('admin', 'auth', 'login'); ?>" class="needs-validation" novalidate="">
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control" value="<?php if (!empty($_POST['email'])) echo e($_POST['email']); ?>" name="email" tabindex="1" required autofocus>
                    <div class="invalid-feedback">
                      Please fill in your email
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="d-block">
                      <label for="password" class="control-label">Password</label>
                    
                    </div>
                    <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                    <div class="invalid-feedback">
                      please fill in your password
                    </div>
                  </div>

                    <div class="w-full m-b-5 text-center text-danger text-center">
                      <?php echo $loginError; ?>
                    </div>
              
                
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                      Login
                    </button>
                  </div>
                </form>
               
              </div>
            </div>
            <div class="mt-5 text-muted text-center">
              Don't have an account? <a href="<?php echo url('admin/auth/signup') ?>">Create One</a>
            </div>
            <div class="mt-2 text-muted text-center">
             Go to <a href="<?php echo url('/') ?>">Home</a>
            </div>
          </div>
        </div>
      </div>
    </section>
