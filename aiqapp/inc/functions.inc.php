<?php

function e($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function countryCodeToFlag($countryCode) {
    $countryCode = strtoupper($countryCode);
    $flag = '';

    for ($i = 0; $i < strlen($countryCode); $i++) {
        $flag .= mb_chr(ord($countryCode[$i]) + 127397, 'UTF-8');
    }

    return $flag;
}
