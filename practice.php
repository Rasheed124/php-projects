<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<pre>

<?php

    function e($value)
    {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    $zip = new ZipArchive();

    $zip->open(__DIR__ . '/Archive.zip');

    // var_dump($zip->count());

    $file_count = $zip->count();

    // for ($x = 0; $x < $file_count; $x++) {
    // var_dump($zip->getNameIndex($x));
    // }

    // var_dump($zip->getFromName('message.txt'))

    try {
    $db = new PDO('mysql:host=localhost;dbname=note_app', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    } catch (PDOException $e) {
    var_dump($e->getMessage());

    echo 'A problem occured with the database connection';
    die();
    }

    // $stmt = $db->prepare('SELECT * FROM `notes` WHERE `id` =  2 ORDER BY `id` ASC ');
    // $id   = $_GET['id'];

    $title   = "Being Kind";
    $content = "Kindness creates positive relationships and builds trust. Simple acts like helping others or saying thank you can brighten someone’s day. Being kind also improves your own happiness.";

    $id    = 8;
    $title = "Morning Routine Editted";

    $stmt = $db->prepare("DELETE FROM `notes` WHERE `id` = :id");
    // $stmt = $db->prepare("UPDATE `notes` SET `title` = :title WHERE `id` =  :id");
    // $stmt = $db->prepare("INSERT INTO `notes` (`title`, `content`) VALUES (:title, :content)");

    // $stmt = $db->prepare('SELECT * FROM `notes` WHERE `id` =  :id');
    $stmt->bindValue('id', $id);
    // $stmt->bindValue('title', $title);

    $stmt->execute();

    // $results = $stmt->fetch(PDO::FETCH_ASSOC);

    // var_dump($results);
    // $results = $stmt->fetch(PDO::FETCH_ASSOC);

    // $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // var_dump($results);
    // var_dump($stmt->fetch(PDO::FETCH_ASSOC));
    // var_dump($stmt->fetch(PDO::FETCH_ASSOC));
    // var_dump($stmt->fetch(PDO::FETCH_ASSOC));

    // while(($result =  $stmt->fetch(PDO::FETCH_ASSOC)) !== false){
    //     var_dump($result);
    // }
?>

</pre>


  <?php foreach ($results as $result): ?>
     <h2><?php echo e($result['title']) ?></h2>
     <p><?php echo e($result['content']) ?></p>
   <?php endforeach?>
</body>
</html>