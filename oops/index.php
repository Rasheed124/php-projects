<?php

use Admin\User;
use Client\User as Client;


require  __DIR__  .  '/./Admin/User.php';
require  __DIR__  .  '/./Admin/Role.php';
require  __DIR__  .  '/./Client/User.php';


// $admin =  new User;
$client =  new Client();

// var_dump($admin);
var_dump($client::class);