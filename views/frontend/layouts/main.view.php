<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
  <meta name="csrf-token" content="<?php echo $adminSupport->generateCsrfToken(); ?>">

    <meta name="author" content="TemplateMo">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&display=swap" rel="stylesheet">

    <title>BlogNest</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo asset('frontend/vendor/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="<?php echo asset('frontend/css/fontawesome.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('frontend/css/templatemo-stand-blog.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('frontend/css/owl.css'); ?>">

  </head>

  <body>

    <!-- ***** Preloader Start ***** -->
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <!-- ***** Preloader End ***** -->

    <!-- Header -->
    <header class="">
      <nav class="navbar navbar-expand-lg">
        <div class="container">
          <a class="navbar-brand" href="<?php echo url('/'); ?>"><h2>BlogNest<em>.</em></h2></a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>


          <div class="collapse navbar-collapse" id="navbarResponsive">
    <ul class="navbar-nav ml-auto">

        <?php if (! empty($navigation)): ?>

            <?php foreach ($navigation as $pageNav): ?>
                
             <li class="nav-item <?php echo(isset($page->slug) && $page->slug !== '' && $pageNav->slug == $page->slug ? 'active' : ''); ?>">

                    <a class="nav-link" href="<?php echo url($pageNav->slug); ?>">
                        <?php echo $pageNav->title ?>

                        <?php if ($pageNav->slug == 'index'): ?>
                            <span class="sr-only">(current)</span>
                        <?php endif; ?>
                    </a>

                </li>
            <?php endforeach; ?>

        <?php else: ?>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo url('create-menu'); ?>">
                    Create menu
                </a>
            </li>

        <?php endif; ?>

        <?php if (! empty($isLoggedIn) && ! empty($isUserIdSession)): ?>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo url('admin', 'dashboard'); ?>">
                    Dashboard
                </a>
            </li>

            <!-- <li class="nav-item">
                <a class="nav-link" href="<?php //echo url('admin', 'auth', 'logout'); ?>">
                    Logout
                </a>
            </li> -->

        <?php else: ?>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo url('admin', 'auth', 'login'); ?>">
                    Login
                </a>
            </li>

        <?php endif; ?>

    </ul>
</div>
        </div>
      </nav>
    </header>

    <!-- Page Contents -->
    <?php echo $contents ?>

    <!-- Footer -->
    <footer>
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <ul class="social-icons">
              <li><a href="#">Facebook</a></li>
              <li><a href="#">Twitter</a></li>
              <li><a href="#">Behance</a></li>
              <li><a href="#">Linkedin</a></li>
              <li><a href="#">Dribbble</a></li>
            </ul>
          </div>
          <div class="col-lg-12">
            <div class="copyright-text">
              <p>Copyright 2026 BlogNext</p>
            </div>
          </div>
        </div>
      </div>
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="<?php echo asset('frontend/vendor/jquery/jquery.min.js'); ?>"></script>
    <script src="<?php echo asset('frontend/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>


    <!-- Additional Scripts -->
    
    <script src="<?php echo asset('frontend/js/custom.js'); ?>"></script>
    <script src="<?php echo asset('frontend/js/owl.js'); ?>"></script>
    <script src="<?php echo asset('frontend/js/slick.js'); ?>"></script>
    <script src="<?php echo asset('frontend/js/isotope.js'); ?>"></script>
    <script src="<?php echo asset('frontend/js/accordions.js'); ?>"></script>


    <script language="text/Javascript">
      cleared[0] = cleared[1] = cleared[2] = 0; //set a cleared flag for each field
      function clearField(t){ //declaring the array outside of the function makes it static and global
        if(! cleared[t.id]){ // function makes it static and global
          cleared[t.id] = 1;  // you could use true and false, but that's more typing
          t.value='';         // with more chance of typos
          t.style.color='#fff';
        }
      }



      
    </script>

  </body>
</html>