<?php

require __DIR__ . '/inc/all.inc.php';
?>
<?php
$char = (string) ($_GET['name'] ?? '');

$char = strtolower($char);

$user_lists = fecth_names_specifically($char);

?>
<?php

render("name.view", [
    'char'       => $char,
    'user_lists' => $user_lists,
]);
