<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title><?php echo $pageTitle; ?> - Building Construction Website Template</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="lib/animate/animate.min.css" rel="stylesheet">

        <!-- Customized Bootstrap Stylesheet -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style.css" rel="stylesheet">
    </head>

    <body>

         <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Topbar Start -->
        <div class="container-fluid topbar d-none d-xl-block w-100">
            <div class="row gx-0 align-items-center" style="height: 45px;">
                <div class="col-lg-6 text-center text-lg-start mb-lg-0">
                    <div class="d-flex flex-wrap">
                        <a href="#" class="text-muted me-4"><i class="fas fa-map-marker-alt text-secondary me-2"></i>Find A Location</a>
                        <a href="#" class="text-muted me-4"><i class="fas fa-phone-alt text-secondary me-2"></i>+01234567890</a>
                        <a href="#" class="text-muted me-0"><i class="fas fa-envelope text-secondary me-2"></i>Example@gmail.com</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center text-lg-end">
                    <div class="d-flex align-items-center justify-content-end">
                        <a href="#" class="text-muted me-3"><i class="fas fa-clock text-secondary me-2"></i>Mon - Sat 8:00 - 17:30, Sunday - CLOSED</a>
                        <a href="#" class="btn btn-primary btn-square border border-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-primary btn-square border border-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="btn btn-primary btn-square border border-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="btn btn-primary btn-square border border-white me-3"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Topbar End -->



      <!-- Navbar & Hero Start -->
      <div class="container-fluid sticky-top px-0">
            <nav class="navbar navbar-expand-lg navbar-dark bg-light py-3 px-4">
                <a href="index.html" class="navbar-brand p-0">
                    <h1 class="text-secondary display-6"><i class="fas fa-city text-primary me-3"></i>Constra</h1>
                    <!-- <img src="img/logo.png" alt="Logo"> -->
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto pt-2 pt-lg-0">
                        <a href="index.php" class="nav-item nav-link <?php if ($pageKey === 'home'): ?> active <?php endif; ?> ">Home</a>
                        <a href="about.php" class="nav-item nav-link <?php if ($pageKey === 'about'): ?> active <?php endif; ?> ">About</a>
                        <a href="service.html" class="nav-item nav-link">Services</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle text-dark" data-bs-toggle="dropdown">Pages</a>
                            <div class="dropdown-menu m-lg-0">
                                <a href="feature.html" class="dropdown-item">Features</a>
                                <a href="blog.html" class="dropdown-item">Our Blog</a>
                            </div>
                        </div>
                        <a href="contact.html" class="nav-item nav-link">Contact</a>
                    </div>
                    <div class="d-flex align-items-center flex-nowrap pt-3  pt-lg-0 ms-lg-2">
                        <a href="https://htmlcodex.com/downloading/?item=3351" class="btn btn-secondary py-2 px-4 flex-wrap flex-sm-shrink-0">Buy Pro Version</a>
                    </div>
                </div>
            </nav>
        </div>
        <!-- Navbar & Hero End -->





