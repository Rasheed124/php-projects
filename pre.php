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

// $container->bind('adminPagesRepository', function () use ($container) {
//     $pdo = $container->get('pdo');
//     return new \BlogApp\Repository\Admin\AdminPagesRepository($pdo);
// });

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
    return new \BlogApp\Admin\Controller\Auth\AuthPagesController(
        $container->get('authPagesRepository'),
        $container->get('sessionController')

    );
});

$container->bind('sessionController', function () {
    return new \BlogApp\Admin\Support\AdminSupport();
});

$container->bind('dashboardController', function () use ($container) {
    return new \BlogApp\Admin\Controller\Pages\DashboardController(
        $container->get('adminPagesRepository'),
        $container->get('sessionController')
    );
});

// ============================================  ROUTES ============================================ //


$basePath = BASE_PATH;
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($basePath && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

$uri = trim($uri, '/');
$segments = $uri ? explode('/', $uri) : [];


// DEFAULT (HOME)
if (empty($segments)) {
    $container->get('pagesController')->showPage('index');
}

// FRONTEND
elseif ($segments[0] !== 'admin') {
    $page = $segments[0];
    $container->get('pagesController')->showPage($page);
}

// ADMIN
elseif ($segments[0] === 'admin') {

    if (($segments[1] ?? '') === 'auth') {
        $container->get('authPagesController')
                  ->renderAuthPages($segments[2] ?? 'login');
    }

    elseif (($segments[1] ?? '') === 'dashboard') {
        $container->get('dashboardController')
                  ->renderDashboardPages($segments[2] ?? 'index');
    }

    elseif (($segments[1] ?? '') === 'posts') {

        $controller = $container->get('postsController');
        $action = $segments[2] ?? 'index';
        $id = $segments[3] ?? null;

        match ($action) {
            'create' => $controller->create(),
            'edit'   => $controller->edit($id),
            'delete' => $controller->delete($id),
            default  => $controller->index(),
        };
    }

    else {
        $container->get('notFoundAdminController')->error404();
    }
}

// 404
else {
    $container->get('notFoundFrontendController')->error404();
}