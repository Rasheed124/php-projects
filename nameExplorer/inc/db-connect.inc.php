<?php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=names;charset=utf8mb4', 'names', '(C]o!Mfwos]c4[7S', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
}
catch (PDOException $e) {
    echo 'A problem occured with the database connection...';
    die();
}

// $stmt = $pdo->prepare('SELECT * FROM `names`');
// $stmt->execute();
// var_dump($stmt->fetch(PDO::FETCH_ASSOC));