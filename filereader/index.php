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

    $getFIlePath = pathinfo('index.php', PATHINFO_EXTENSION);

    // var_dump($getFIlePath);

    // var_dump(file_exists(__DIR__ . '/..images'));
    // var_dump(is_dir(__DIR__ . '/images'));

    $isfolderExist = is_dir(__DIR__ . '/images');

    if ($isfolderExist) {

    $handle            = opendir(__DIR__ . '/images');
    $allowedExtensions = [
        'jpg',
        'png'
    ];
    $textContents = [];
    $contents     = [];

    $images = [];
    while (($currentFile = readdir($handle)) !== false) {
        if ($currentFile === '.' || $currentFile === '..') {
            continue;
        }
        $contents[] = $currentFile;
    }
    closedir($handle);

    foreach ($contents as $textContent) {
        $filePath = pathinfo($textContent, PATHINFO_EXTENSION);

        if ($filePath === 'txt') {
            $textContents[] = file_get_contents("images/$textContent");
        }
    }

    foreach ($contents as $imageContent) {
        $filePath = pathinfo($imageContent, PATHINFO_EXTENSION);

        if (! in_array($filePath, $allowedExtensions)) {
            continue;
        }

        $images[] = $imageContent;
    }

    }

?>
</pre>


<?php foreach ($images as $image): ?>
    <img src="images/<?php echo rawurlencode($image) ?>" alt="">
<?php endforeach; ?>
<?php foreach ($textContents as $text): ?>
    <p><?php echo $text ?></p>
<?php endforeach; ?>

</body>
</html>