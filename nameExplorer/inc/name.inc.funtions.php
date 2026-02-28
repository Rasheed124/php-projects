<?php
declare (strict_types = 1);

function fecth_all_names(string $char): array
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

function fecth_names_specifically(string $value): array
{
    global $pdo;

    $stmt = $pdo->prepare('SELECT * FROM `names` WHERE `name` LIKE :val ORDER BY `names`.`year` ASC;');
    $stmt->bindValue('val', "{$value}");
    $stmt->execute();

    $name_lists = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $name_lists;
}


function fecth_names_overview(): array
{
    global $pdo;

    $stmt = $pdo->prepare('SELECT `name`, SUM(`count`) AS sum FROM `names` GROUP BY `name` ORDER BY `sum` DESC LIMIT 10;');
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
     
}
