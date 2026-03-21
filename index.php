<?php
require __DIR__ . '/inc/all.inc.php';

$container = new \BlogApp\Support\Container;

// ============================================  Containers ============================================ //

$container->bind('pdo', function () {
    return require __DIR__ . '/inc/db-connect.inc.php';
});

$container->bind('pagesRepository', function () use ($container) {
    $pdo = $container->get('pdo');
    return new \BlogApp\Repository\PagesRepository($pdo);
});
$container->bind('pagesController', function () use ($container) {
    $pagesRepository = $container->get('pagesRepository');
    return new \BlogApp\Frontend\Controller\PagesController($pagesRepository);
});
$container->bind('notFoundFrontendController', function () use ($container) {
    $pagesRepository = $container->get('pagesRepository');
    return new \BlogApp\Frontend\Controller\NotFoundFrontendController($pagesRepository);
});

// ============================================  ROUTES ============================================ //

$route = @(string) ($_GET['route'] ?? 'pages');

if ($route === 'pages') {
    $page = @(string) ($_GET['page'] ?? 'index');

    $pageController = $container->get('pagesController');

    $pageController->showPage($page);

} else if ($route === 'admin/pages') {
    echo "Admin Page";

} else if ($route === 'admin/auth') {
    $page = @(string) ($_GET['page'] ?? 'login');

    // $pagesController = new AuthController();
    // $pagesController->renderAuthScreens($page);
} else {
    $notFoundController = $container->get('notFoundFrontendController');
    $notFoundController->error404();
}

// require __DIR__ . '/inc/all.inc.php';

// use App\blogAdmin\Controller\AuthController;
// use App\blogAdmin\Controller\NotFoundController;
// use \App\blogFrontend\Controller\PagesController;

// $route = @(string) ($_GET['route'] ?? 'pages');

// // Handle frontend (pages) route
// if ($route === 'pages') {
//     // Get the page from the URL (defaults to 'index' if not provided)
//     $page = @(string) ($_GET['page'] ?? 'index');

//     $pagesController = new PagesController();
//     if ($pagesController->isPageExists($page)) {
//         $pagesController->renderPage($page);
//     } else {
//         // Show a 404 error page for frontend pages
//         $notFoundController = new NotFoundController();
//         $notFoundController->error404('pages'); // Specify 'pages' for frontend
//     }

// // Handle admin route
// } else if ($route === 'admin/pages') {
//     // Handle admin page requests
//     echo "Admin Page";

// // Handle admin authentication (login/signup)
// } else if ($route === 'admin/auth') {
//     $page = @(string) ($_GET['page'] ?? 'login');
//     $authController = new AuthController();
//     $authController->renderAuthScreens($page);

// // Catch-all for undefined routes (both admin and frontend)
// } else {
//     // Check if the route is for admin, show admin 404 page if necessary
//     if (strpos($route, 'admin/') === 0) {
//         $notFoundController = new NotFoundController();
//         $notFoundController->error404('admin'); // Specify 'admin' for admin routes
//     } else {
//         // Show generic 404 page for frontend if route is invalid
//         $notFoundController = new NotFoundController();
//         $notFoundController->error404('pages'); // Specify 'pages' for frontend routes
//     }
// }
