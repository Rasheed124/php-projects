<?php

require __DIR__ . '/inc/all.inc.php';

$route = @(string) ($_GET['route'] ?? 'pages');

if ($route === 'pages') {
    $page = @(string) ($_GET['page'] ?? 'index');

    $pagesRepository = new \App\Repository\PagesRespository($pdo);

    $pageController = new \App\Frontend\Controller\PageController($pagesRepository);
    $pageController->showPage($page);
} else {

    http_response_code(404);

    $pagesRepository = new \App\Repository\PagesRespository($pdo);

    $pageController = new \App\Frontend\Controller\PageController($pagesRepository);

    $notFoundPage = new \App\Frontend\Controller\NotFoundController($pagesRepository);
    $notFoundPage->error404();

}
