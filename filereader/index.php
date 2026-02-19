<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        img{
            width: 400px;
            height: 400px;
        }
    </style>
</head>
<body>
<pre>
<?php

    $getFIlePath = pathinfo('index.php', PATHINFO_EXTENSION);
    function e($value)
    {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    // var_dump($getFIlePath);

    // var_dump(file_exists(__DIR__ . '/..images'));
    // var_dump(is_dir(__DIR__ . '/images'));

    $isfolderExist = is_dir(__DIR__ . '/images');

    $handle            = opendir(__DIR__ . '/images');
    $allowedExtensions = [
    'jpg',
    'png',
    ];
    $textContents = [];
    $contents     = [];

    $images = [];
    while (($currentFile = readdir($handle)) !== false) {
    if ($currentFile === '.' || $currentFile === '..') {
        continue;
    }
    $filePath = pathinfo($currentFile, PATHINFO_EXTENSION);

    if (! in_array($filePath, $allowedExtensions)) {
        continue;
    }

    $fileWithouExt = pathinfo($currentFile, PATHINFO_FILENAME);
    $txtFile       = __DIR__ . '/images/' . $fileWithouExt . '.txt';
    $title         = '';
    $content       = [];
    if (file_exists($txtFile)) {
        $txt            = file_get_contents($txtFile);
        $array_form_txt = explode("\n", $txt);

        $title = $array_form_txt[0];
        unset($array_form_txt[0]);
        // var_dump($array_form_txt);
        $content = array_values($array_form_txt);
    }

    $images[] = [
        'images'  => $currentFile,
        'title'   => $title,
        'content' => $content,
    ];
    }
    closedir($handle);

    // var_dump($images);

?>
</pre>


<?php foreach ($images as $image): ?>
    <figure>


    </figure>
    <p><?php echo e($image['title']) ?></p>
    <img src="images/<?php echo rawurlencode($image['images']) ?>" alt="">
    
    <?php foreach ($image['content'] as $contentParagraph): ?>
       <figcaption>
               <p><?php echo e($contentParagraph) ?></p>
       </figcaption>
    <?php endforeach; ?>
<?php endforeach; ?>


</body>
</html>