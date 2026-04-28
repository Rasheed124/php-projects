<?php

define('ROOT_PATH', dirname(__DIR__));

define('ADMIN_VIEWS_PATH', ROOT_PATH . '/views/admin');

define('FRONTEND_VIEWS_PATH', ROOT_PATH . '/views/frontend');

// Define it as a constant instead of a variable
define('TINY_MCE_KEY', 'kb3lys78ua2nrx6j6c7btgwpeipbrfmiu2qwm9o8dotstazc');



require __DIR__ . '/autoload.inc.php';
require_once __DIR__ . '/vendor/autoload.php';
// require __DIR__ . '/db-connect.inc.php';
require __DIR__ . '/functions.inc.php';
