<?php

    require __DIR__ . '/inc/all.inc.php';
?>
<pre>
<?php

    $alphabets = gen_alhabets();

    $char = (string) ($_GET['char'] ?? '');

    if (strlen($char) > 1) {
    $char = $char[0];
    }

    $char = strtoupper($char);

    $names = fecth_all_inital_name($char);
?>

</pre>


<?php require __DIR__ . '/views/header.php'; ?>

    <ul>
        <?php foreach ($names as $name): ?>
            <li>
               <a href="name.php?<?php echo http_build_query(['name' => $name]) ?>">
                   <?php echo e($name) ?>
               </a>
            </li>
        <?php endforeach; ?>
    </ul>


<?php require __DIR__ . '/views/footer.php'; ?>