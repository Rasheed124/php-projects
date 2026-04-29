<?php

define('ROOT_PATH', dirname(__DIR__));

require_once ROOT_PATH . '/src/Support/EnvLoader.php';
\App\Support\EnvLoader::load(ROOT_PATH . '/.env');

define('ADMIN_VIEWS_PATH', ROOT_PATH . '/views/admin');
define('FRONTEND_VIEWS_PATH', ROOT_PATH . '/views/frontend');

define('TINY_MCE_KEY', $_ENV['TINY_MCE_KEY'] ?? 'kb3lys78ua2nrx6j6c7btgwpeipbrfmiu2qwm9o8dotstazc');



require __DIR__ . '/autoload.inc.php';
require_once __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/functions.inc.php';
