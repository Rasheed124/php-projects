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

$route = @(string) ($_GET['route'] ?? 'pages');

if ($route === 'pages') {
    $page           = @(string) ($_GET['page'] ?? 'index');
    $pageController = $container->get('pagesController');
    $pageController->showPage($page);

} else if ($route === 'admin/pages') {
    $page                = @(string) ($_GET['page'] ?? 'dashboard');
    // $page                = @(string) ($_GET['page'] ?? 'dashboard');
    $dashboardController = $container->get('dashboardController');
    $dashboardController->renderDashboardPages($page);

} else if ($route === 'admin/auth') {
    $page                = @(string) ($_GET['page'] ?? 'login');
    $authPagesController = $container->get('authPagesController');
    $authPagesController->renderAuthScreens($page);

} else {
    $notFoundController = $container->get('notFoundFrontendController');
    $notFoundController->error404();
}
