<?php

    require __DIR__ . '/inc/all.inc.php';
?>
<?php
    $char = (string) ($_GET['char'] ?? '');

    if (strlen($char) > 1) {
    $char = $char[0];
    }

    if (strlen($char) === 0) {
    header("Location:index.php");
    die();
    }

    $char  = strtoupper($char);
    $names = fecth_all_names($char);
?>

<?php
    render('char.view', [
    'names' => $names,
]);
?>