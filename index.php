<?php
require __DIR__ . '/inc/all.inc.php';

// Create the container instance
$container = new \App\Support\Container;

// ============================================  Containers ============================================ //

// Bind PDO (database connection)
$container->bind('pdo', function () {
    return require __DIR__ . '/inc/db-connect.inc.php';
});

$container->bind('pagesRepository', function () use ($container) {
    $pdo = $container->get('pdo');
    return new \App\Repository\PagesRepository($pdo);
});

$container->bind('pagesController', function () use ($container) {
    return new \App\Frontend\Controller\PagesController(
        $container->get('pagesRepository'),

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

// 1. DEFAULT / HOME
if (empty($segments)) {
    $container->get('pagesController')->showPage('index');
}

// 2. ADMIN ROUTES
elseif ($segments[0] === 'admin') {
    $subAction = $segments[1] ?? 'dashboard';
    if ($subAction === 'auth') {
    } elseif ($subAction === 'posts') {
    } else {
    }
}

// 3. DYNAMIC FRONTEND PAGES
else {
    $slug = $segments[0];
    $container->get('pagesController')->showPage($slug);
}
