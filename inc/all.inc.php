<?php

// Assuming this file is in /inc/all.inc.php
// This gets the absolute path to the root of your project
define('ROOT_PATH', dirname(__DIR__));

// Define the specific path to your admin views
define('ADMIN_VIEWS_PATH', ROOT_PATH . '/views/admin');

// Define a general views path if needed for the frontend
define('FRONTEND_VIEWS_PATH', ROOT_PATH . '/views/frontend');

require __DIR__ . '/autoload.inc.php';
// require __DIR__ . '/db-connect.inc.php';
require __DIR__ . '/functions.inc.php';
