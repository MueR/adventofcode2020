<?php
$data = require 'input.php';

function findTotal($data, $inputNumber1) {
    foreach ($data as $number) {
        if ($number === $inputNumber1) {
            continue;
        }
        if (2020 === ($number + $inputNumber1)) {
            return $number * $inputNumber1;
        }
    }
    return false;
}
foreach ($data as $num) {
    $result = findTotal($data, $num);
    if (false !== $result) {
        print $result;
        exit;
    }
}
