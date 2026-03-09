<?php

require __DIR__ . '/inc/all.inc.php';

$page = @(string) ($_GET['page'] ?? 'index');

if ($page === 'index') {
    $pageController = new \App\Frontend\Controller\PageController();
    $pageController->showPage('index');
} else {

    http_response_code(404);

    $notFoundPage = new \App\Frontend\Controller\NotFoundController();
    $notFoundPage->error404();

}
