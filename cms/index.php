<?php

require __DIR__ . '/inc/all.inc.php';

$page = @(string) ($_GET['page'] ?? 'index');

if ($page === 'index') {
    echo "This is the index page";
} else {
    // $notFoundPage->e;

}
