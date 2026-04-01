<?php

try {
    $pdo = new PDO( 'mysql:host=127.0.0.1;dbname=blognest;charset=utf8mb4', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
}
catch (PDOException $e) {
    var_dump($e->getMessage());
    echo 'A problem occured with the database connection...';
    die();
}

return $pdo;

