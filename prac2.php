<?php
declare (strict_types = 1);

function getHighestNumber(int $value): int
{
    return $value < 10 ? $value : 10;
}

$reslut = getHighestNumber(5);
var_dump($reslut);
// date_default_timezone_set('America/Argentina/Ushuaia');

// var_dump(date('Y-M-D H:i:s')
// )

// function f(int )
