<?php
// RsDwuzI5(ofJk@AI

try {
    $pdo = new PDO('mysql:host=localhost;dbname=cities;charset=utf8mb4', 'cities', 'RsDwuzI5(ofJk@AI', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

}
catch (PDOException $e) {
    // var_dump($e->getMessage());
    echo 'A problem occured with the database connection...';
    die();
}
