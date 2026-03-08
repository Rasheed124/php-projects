<?php

require __DIR__ . '/inc/all.inc.php';

$page = @(string) ($_GET['page'] ?? 'index');

if ($page === 'index') {
    echo "This is the index page";
} else {

    http_response_code(404);

    $notFoundPage = new \App\Frontend\Controller\NotFoundController();
    $notFoundPage->error404();

}
