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
$container->bind('authPagesRepository', function () use ($container) {
    $pdo = $container->get('pdo');
    
    return new \BlogApp\Admin\Repository\AuthRepository\AuthPagesRepository($pdo);
});
$container->bind('pagesController', function () use ($container) {
    $pagesRepository = $container->get('pagesRepository');
    return new \BlogApp\Frontend\Controller\PagesController($pagesRepository);
});
$container->bind('notFoundFrontendController', function () use ($container) {
    $pagesRepository = $container->get('pagesRepository');
    return new \BlogApp\Frontend\Controller\NotFoundFrontendController($pagesRepository);
});
$container->bind('authPagesController', function () use ($container) {
    $authPagesRepository = $container->get('authPagesRepository');

    return new \BlogApp\Admin\Controller\AuthPagesController($authPagesRepository);
});
$container->bind('dashboardController', function () {
    $sessionController = new \BlogApp\Admin\Controller\SessionController();
    return new \BlogApp\Admin\Controller\pagesController\DashboardController($sessionController);
});

// ============================================  ROUTES ============================================ //

$route = @(string) ($_GET['route'] ?? 'pages');

if ($route === 'pages') {
    $page = @(string) ($_GET['page'] ?? 'index');

    $pageController = $container->get('pagesController');

    $pageController->showPage($page);

} else if ($route === 'admin/pages') {
    $page = @(string) ($_GET['page'] ?? 'dashboard');

    $dashboardController = $container->get('dashboardController');
    $dashboardController->renderDashboardPages($page);

}
 else if ($route === 'admin/auth') {
    $page = @(string) ($_GET['page'] ?? 'login');

    $authPagesController = $container->get('authPagesController');
    $authPagesController->renderAuthScreens($page);
} else {
    $notFoundController = $container->get('notFoundFrontendController');
    $notFoundController->error404();
}
