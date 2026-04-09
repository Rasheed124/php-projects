<?php
require __DIR__ . '/inc/all.inc.php';

// Create the container instance
$container = new \App\Support\Container;

// ============================================  Containers ============================================ //

// Bind PDO (database connection)
$container->bind('pdo', function () {
    return require __DIR__ . '/inc/db-connect.inc.php';
});

$container->bind('AdminSupport', function () use ($container) {
    return new \App\Admin\Support\AdminSupport();
});

// ===============================  Repositories ============================= //

$container->bind('pagesRepository', function () use ($container) {
    $pdo = $container->get('pdo');
    return new \App\Repository\PagesRepository($pdo);
});
$container->bind('AuthPagesRepository', function () use ($container) {
    return new \App\Repository\Auth\AuthPagesRepository(
        $container->get('pdo')
    );
});
$container->bind('postRepository', function () use ($container) {
    return new \App\Repository\Admin\PostsRepository(
        $container->get('pdo')
    );
});

// ===============================  404 Controllers ============================= //

$container->bind('notFoundFrontendController', function () use ($container) {
    return new \App\Frontend\Controller\NotFoundFrontendController(
        $container->get('signupController'),
        $container->get('AdminSupport'),

    );
});
$container->bind('notFoundAdminController', function () use ($container) {
    return new \App\Admin\Controller\NotFoundAdminController(
        $container->get('AdminSupport')

    );
});

// ===============================  FRONTEND PAGES Controllers ============================= //

$container->bind('pagesController', function () use ($container) {
    return new \App\Frontend\Controller\PagesController(
        $container->get('pagesRepository'),
        $container->get('AdminSupport'),

    );
});

// ===============================  ADMIN PAGES Controllers ============================= //

$container->bind('adminPagesController', function () use ($container) {
    return new \App\Admin\Controller\Pages\AdminPagesController(
        $container->get('AdminSupport'),
        $container->get('pagesRepository'),

    );
});

$container->bind('postController', function () use ($container) {
    return new \App\Admin\Controller\Post\PostController(
        $container->get('AdminSupport'),
        $container->get('postRepository')
    );
});
$container->bind('taxonomyController', function () use ($container) {
    return new \App\Admin\Controller\Post\TaxonomyController(
        $container->get('AdminSupport'),
        $container->get('postRepository')
    );
});

$container->bind('adminDashboardController', function () use ($container) {
    return new \App\Admin\Controller\Pages\AdminDashboardController(
        $container->get('AdminSupport'),
        $container->get('pagesRepository'),
    );
});

// Auth Controllers (Inject repositories/sessions here if needed)
$container->bind('loginController', function () use ($container) {
    return new \App\Admin\Controller\Auth\LoginController(
        $container->get('AdminSupport'),
        $container->get('AuthPagesRepository')

    );
});
$container->bind('signupController', function () use ($container) {
    return new \App\Admin\Controller\Auth\SignupController(
        $container->get('AdminSupport'),
        $container->get('AuthPagesRepository')
    );
});
$container->bind('authPagesController', function () use ($container) {
    return new \App\Admin\Controller\Auth\AuthPagesController(
        $container->get('loginController'),
        $container->get('signupController'),
        $container->get('AdminSupport'),
    );
});

// ============================================  Routes ============================================ //

$basePath = BASE_PATH;
$uri      = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($basePath && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

$uri      = trim($uri, '/');
$segments = $uri ? explode('/', $uri) : [];

// 1. HOME
if (empty($segments)) {
    $container->get('pagesController')->showPage('index');
}

// 2. ADMIN & AUTH
elseif ($segments[0] === 'admin') {

    $subAction = $segments[1] ?? 'dashboard';

    if ($subAction !== 'auth' && ! $container->get('AdminSupport')->isLoggedIn()) {
        header('Location: ' . url('admin/auth/login'));
        exit;
    }

    if ($subAction === 'auth') {
        $container->get('authPagesController')->renderAuthPages($segments[2] ?? 'login');
    } elseif ($subAction === 'dashboard') {
        $container->get('adminDashboardController')->index();
    } elseif ($subAction === 'pages') {
        $container->get('adminPagesController')->handleAction($segments[2] ?? 'index');
    } elseif ($subAction === 'posts') {
        $container->get('postController')->handleAction($segments[2] ?? 'index');
    }

    elseif ($subAction === 'taxonomy') {
        $action = isset($segments[3]) ? $segments[2] . '/' . $segments[3] : ($segments[2] ?? 'index');
        $container->get('taxonomyController')->handleAction($action);
    } else {
        $container->get('notFoundAdminController')->error404();
    }
}

// 3. FRONTEND DYNAMIC PAGES
else {
    $slug = $segments[0];
    $container->get('pagesController')->showPage($slug);
}
