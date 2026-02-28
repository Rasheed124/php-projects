<?php

// pw:  (C]o!Mfwos]c4[7S (C]o!Mfwos]c4[7S
// name : names

try {
    $db = new PDO('mysql:host=localhost;dbname=diary', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

} catch (PDOException $e) {
    // var_dump($e->getMessage());

    echo 'A problem occured with the database connection';
    die();
}


?>