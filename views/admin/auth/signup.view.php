 
 <?php if (!empty($signUpError)): ?>
    <p>Ensure all fields are properly field</p>
<?php endif; ?>
 <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
            <div class="card card-primary">
              <div class="card-header">
                <h4>Register</h4>
              </div>
              <div class="card-body">
                <form method="POST" action="index.php?<?php echo http_build_query(['route' => 'admin/auth', 'page'=> 'signup']);?>">
                  <div class="row">
                    <div class="form-group col-6">
                      <label for="user_name">User Name</label>
                      <input id="user_name" type="text" class="form-control" name="user_name" value="<?php if (!empty($_POST['user_name'])) echo e($_POST['user_name']); ?>" autofocus>
                    </div>
                    <div class="form-group col-6">
                      <label for="email">Email</label>
                      <input id="email" type="email" class="form-control" value="<?php if (!empty($_POST['email'])) echo e($_POST['email']); ?>" name="email">
                    </div>
                  </div>
              
                  <div class="row">
                    <div class="form-group col-6">
                      <label for="password" class="d-block">Password</label>
                      <input id="password" type="password" class="form-control pwstrength" data-indicator="pwindicator"
                        name="password">
                   
                    </div>
                    <div class="form-group col-6">
                      <label for="password2" class="d-block">Password Confirmation</label>
                      <input id="password2" type="password" class="form-control" name="confirm_password">
                    </div>
                  </div>
              
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                      Register
                    </button>
                  </div>
                </form>
              </div>
              <div class="mb-4 text-muted text-center">
                Already Registered? <a href="index.php?<?php echo http_build_query(['route' => 'admin/auth', 'page' => 'login']) ?>">Login</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>