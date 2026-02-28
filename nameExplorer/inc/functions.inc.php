<?php

function e($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}


function render($view, $params) {
    extract($params);

    ob_start();
    require __DIR__ . '/../views/pages/' . $view . '.php';
    $contents = ob_get_clean();

    $alphabets = gen_alhabets();
    require __DIR__ . '/../views/layouts/main.view.php';
  

}

function gen_alhabets()
{
    $A = ord('A');
    $B = ord('Z');

    $letters = [];

    for ($x = $A; $x <= $B; $x++) {
        $letter    = chr($x);
        $letters[] = $letter;
    }

    return $letters;

}
