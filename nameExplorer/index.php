<?php

    require __DIR__ . '/inc/all.inc.php';
?>
<?php
    $overviewed_user = fecth_names_overview();



render("index.view", [
    'overviewed_user' => $overviewed_user
]);
