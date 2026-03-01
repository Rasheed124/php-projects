<?php

require __DIR__ . '/inc/all.inc.php';
?>
<?php
$char = (string) ($_GET['name'] ?? '');

if (empty($char)) {
    header("Location:index.php");
    die();
}

$user_lists = fecth_names_specifically($char);

?>
<?php

render("name.view", [
    'char'       => $char[0],
    'name'       => $char,
    'user_lists' => $user_lists,
]);
