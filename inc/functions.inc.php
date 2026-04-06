<?php

define('BASE_PATH', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));

function e($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function url(...$segments)
{
    $base = BASE_PATH ? BASE_PATH : '';

    $path = implode('/', array_map(function ($segment) {
        return trim($segment, '/');
    }, $segments));

    return $base . '/' . $path;
}

function asset($path = '')
{
    return (BASE_PATH ? BASE_PATH : '') . '/assets/' . ltrim($path, '/');
}

