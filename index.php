<?php
require __DIR__ . '/inc/all.inc.php';

use \App\blogFrontend\Controller\PagesController;

$route = @(string) ($_GET['route'] ?? 'pages');

if ($route === 'pages') {
    $page = @(string) ($_GET['page'] ?? 'index');

   $pagesController = new PagesController();
   $pagesController->index();

} else if ($route === 'admin/pages') {
    echo "Admin Page";
}
