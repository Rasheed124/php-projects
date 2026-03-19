<?php
require __DIR__ . '/inc/all.inc.php';

use App\blogAdmin\Controller\AuthController;
use App\blogAdmin\Controller\NotFoundController;
use \App\blogFrontend\Controller\PagesController;

$route = @(string) ($_GET['route'] ?? 'pages');

if ($route === 'pages') {
    $page            = @(string) ($_GET['page'] ?? 'index');
    $pagesController = new PagesController();
    $pagesController->renderPage($page);

} else if ($route === 'admin/pages') {
    echo "Admin Page";

} else if ($route === 'admin/auth') {
    $page = @(string) ($_GET['page'] ?? 'login');

    $pagesController = new AuthController();
    $pagesController->renderAuthScreens($page);
} else {
    $notFoundController = new NotFoundController();
    $notFoundController->error404();
}
