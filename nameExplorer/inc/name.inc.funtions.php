<?php
declare (strict_types = 1);

function fecth_all_inital_name(string $char): array
{
    global $pdo;

    $stmt = $pdo->prepare('SELECT DISTINCT `name` FROM `names` WHERE `name` LIKE :exp ORDER BY `name` ASC;');
    $stmt->bindValue('exp', "{$char}%");
    $stmt->execute();
    $names = [];

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($results as $result) {
        $names[] = $result['name'];
    }

    return $names;
}
