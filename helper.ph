$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove base path dynamically
if ($basePath && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

$uri = trim($uri, '/');
$segments = $uri ? explode('/', $uri) : [];










header('Location: ' . base_url('admin/auth/login'));






$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove base path dynamically
if ($basePath && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

$uri = trim($uri, '/');
$segments = $uri ? explode('/', $uri) : [];










































<?php
require __DIR__ . '/inc/all.inc.php';

// Create the container instance
$container = new \BlogApp\Support\Container;

// ============================================  Containers ============================================ //

// Bind PDO (database connection)
$container->bind('pdo', function () {
    return require __DIR__ . '/inc/db-connect.inc.php';
});

// Bind Repositories
$container->bind('pagesRepository', function () use ($container) {
    $pdo = $container->get('pdo');
    return new \BlogApp\Repository\PagesRepository($pdo);
});

$container->bind('authPagesRepository', function () use ($container) {
    $pdo = $container->get('pdo');
    return new \BlogApp\Repository\Auth\AuthPagesRepository($pdo);
});

$container->bind('adminPagesRepository', function () use ($container) {
    $pdo = $container->get('pdo');
    return new \BlogApp\Repository\Admin\AdminPagesRepository($pdo);
});

// Controllers Bind with automatic dependency resolution
$container->bind('pagesController', function () use ($container) {
    return new \BlogApp\Frontend\Controller\PagesController(
        $container->get('pagesRepository'),
        $container->get('sessionController')

    );
});

$container->bind('notFoundFrontendController', function () use ($container) {
    return new \BlogApp\Frontend\Controller\NotFoundFrontendController(
        $container->get('pagesRepository'),
        $container->get('sessionController')

    );
});

$container->bind('authPagesController', function () use ($container) {
    return new \BlogApp\Admin\Controller\AuthPagesController(
        $container->get('authPagesRepository'),
        $container->get('sessionController')

    );
});

$container->bind('sessionController', function () {
    return new \BlogApp\Admin\Controller\SessionController();
});

$container->bind('dashboardController', function () use ($container) {
    return new \BlogApp\Admin\Controller\pagesController\DashboardController(
        $container->get('adminPagesRepository'),
        $container->get('sessionController')
    );
});

// ============================================  ROUTES ============================================ //



// $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// $uri = trim($uri, '/');
// $segments = explode('/', $uri);

// var_dump($uri);
// // var_dump($segments);
// die();

// // Default route
// if ($uri === '' || $segments[0] === 'pages') {
//     $page = $segments[1] ?? 'index';
//     $controller = $container->get('pagesController');
//     $controller->showPage($page);
// }

// // ADMIN ROUTES
// elseif ($segments[0] === 'admin') {

//     // /admin/dashboard
//     if (($segments[1] ?? '') === 'dashboard') {
//         $controller = $container->get('dashboardController');
//         $page = $segments[2] ?? 'index';
//         $controller->renderDashboardPages($page);
//     }

//     // /admin/auth/*
//     elseif (($segments[1] ?? '') === 'auth') {
//         $controller = $container->get('authPagesController');
//         $page = $segments[2] ?? 'login';
//         $controller->renderAuthScreens($page);
//     }

//     else {
//         $container->get('notFoundFrontendController')->error404();
//     }
// }

// else {
//     $container->get('notFoundFrontendController')->error404();
// }






// functions.php
define('BASE_PATH', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));

function url($path = '')
{
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    return ($base ? $base : '') . '/' . ltrim($path, '/');
}

$basePath = '/app/blog';

var_dump($_SERVER['SCRIPT_NAME']);
die();

// main index.php file
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

$uri = trim($uri, '/');
$segments = $uri ? explode('/', $uri) : [];

// DEFAULT (HOME)
if (empty($segments)) {
    $controller = $container->get('pagesController');
    $controller->showPage('index');
}

// FRONTEND PAGES
elseif ($segments[0] !== 'admin') {
    $page = $segments[0] ?? 'index';
    $controller = $container->get('pagesController');
    $controller->showPage($page);
}

// ADMIN ROUTES
elseif ($segments[0] === 'admin') {

    // /admin/auth/*
    if (($segments[1] ?? '') === 'auth') {
        $controller = $container->get('authPagesController');
        $page = $segments[2] ?? 'login';
        $controller->renderAuthScreens($page);
    }

    // /admin/dashboard
    elseif (($segments[1] ?? '') === 'dashboard') {
        $controller = $container->get('dashboardController');
        $page = $segments[2] ?? 'index';
        $controller->renderDashboardPages($page);
    }

    // /admin/posts/*
    elseif (($segments[1] ?? '') === 'posts') {
        $controller = $container->get('postsController');

        $action = $segments[2] ?? 'index';
        $id = $segments[3] ?? null;

        switch ($action) {
            case 'create':
                $controller->create();
                break;

            case 'edit':
                $controller->edit($id);
                break;

            case 'delete':
                $controller->delete($id);
                break;

            default:
                $controller->index();
        }
    }

    else {
        $container->get('notFoundAdminController')->error404();
    }
}

// FALLBACK 404
else {
    $container->get('notFoundFrontendController')->error404();
}




























// $route = @(string) ($_GET['route'] ?? 'pages');

// if ($route === 'pages') {
//     $page           = @(string) ($_GET['page'] ?? 'index');
//     $pageController = $container->get('pagesController');
//     $pageController->showPage($page);

// } else if ($route === 'admin/pages') {
//     $page                = @(string) ($_GET['page'] ?? 'dashboard');
//     $dashboardController = $container->get('dashboardController');
//     $dashboardController->renderDashboardPages($page);

// } else if ($route === 'admin/auth') {
//     $page                = @(string) ($_GET['page'] ?? 'login');
//     $authPagesController = $container->get('authPagesController');
//     $authPagesController->renderAuthScreens($page);

// } else {
//     $notFoundController = $container->get('notFoundFrontendController');
//     $notFoundController->error404();
// }












 <div class="collapse navbar-collapse" id="navbarResponsive">
              <ul class="navbar-nav ml-auto">
            <?php if (! empty($_GET['page'])):
                    $currentPage = $_GET['page'];
            endif; ?>
            <?php if (! empty($navigation)): ?>
                <!-- Loop through the navigation items -->
                <?php foreach ($navigation as $pageNav): ?>
                  <li class="nav-item <?php echo($pageNav->slug == $currentPage ? 'active' : ''); ?>">
                    <a class="nav-link" href="index.php?<?php echo http_build_query(['page' => $pageNav->slug]) ?>">
                      <?php echo $pageNav->title ?>
                      <?php if ($pageNav->slug == 'index'): ?>
                        <span class="sr-only">(current)</span>
                      <?php endif; ?>
                    </a>
                  </li>
                <?php endforeach; ?>
            <?php else: ?>
              <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                  <a class="nav-link" href="index.php?<?php echo http_build_query(['route' => 'pages', 'page' => $pageNav->slug]) ?>">Create menu</a>
                </li>
            <?php endif; ?>




              <?php if (! empty($isLoggedIn) && ! empty($isUserIdSession)): ?>
                 <li class="nav-item">
                      <a class="nav-link" href="index.php?<?php echo http_build_query(['route' => 'admin/pages', 'page' => 'dashbaord']) ?>">Dashboard</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="index.php?<?php echo http_build_query(['route' => 'admin/auth', 'page' => 'logout']) ?>">Logout</a>
                </li>
                <?php else: ?>
                    <li class="nav-item">
                      <a class="nav-link" href="index.php?<?php echo http_build_query(['route' => 'admin/auth', 'page' => 'login']) ?>">Login</a>
                    </li>
                <?php endif?>
              </ul>
          </div>





































































          I needed to do some proper restructuring of my routing structure for best practice and scalability.


Looking at what i have now, i wants to refractor the to the following


frontend Pages routes
  / -  home
  /about -  About
  /contact -  Contact



Auth
   /admin/auth/login
    /admin/auth/signup


Admin pages

/admin/dashboard - to main dashboard page

  /admin/pages
/admin/pages/create
/admin/pages/edit
/admin/pages/delete


Admin posts

  /admin/posts
/admin/post/create
/admin/post/edit
/admin/post/delete


Now for better management of session handling and likes, i wants to group each phase to  a render function just as the sample used below. But provided a better approach to fit the exisiting strucuture