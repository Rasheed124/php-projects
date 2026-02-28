<?php

function e($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
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
