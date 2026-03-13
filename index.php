<?php

require __DIR__ . '/inc/all.inc.php';

$container = new \App\Support\Container;

$container->bind('pdo', function () {
    return require __DIR__ . '/inc/db-connect.inc.php';
});

$container->bind('pagesRespository', function () use ($container) {
    $pdo = $container->get('pdo');
    return new \App\Repository\PagesRespository($pdo);
});
$container->bind('pageController', function () use ($container) {
    $pagesRepository = $container->get('pagesRespository');
    return new \App\Frontend\Controller\PageController($pagesRepository);
});
$container->bind('notFoundController', function () use ($container) {
    $pagesRepository = $container->get('pagesRespository');
    return new \App\Frontend\Controller\NotFoundController($pagesRepository);
});

/* ==============================
ADMIN CONTROLLER
==================================
*/
$container->bind('pagesAdminController', function () use ($container) {
    $pagesRepository = $container->get('pagesRespository');

    return new \App\Admin\Controller\PagesAdminController($pagesRepository);
});

$route = @(string) ($_GET['route'] ?? 'pages');

if ($route === 'pages') {
    $page           = @(string) ($_GET['page'] ?? 'index');
    $pageController = $container->get('pageController');

    $pageController->showPage($page);
} else if ($route === 'admin/pages') {
    $page = $container->get('pagesAdminController');
    $page->index();
}
else if ($route === 'admin/pages/create') {
    $pagesAdminController = $container->get('pagesAdminController');
    $pagesAdminController->create();
}
else if ($route === 'admin/pages/delete') {
    // $id = @(int) ($_GET['id'] || 0);
    $pagesAdminController = $container->get('pagesAdminController');
    $pagesAdminController->delete();
}
else {
    $notFoundController = $container->get('notFoundController');
    $notFoundController->error404();
}