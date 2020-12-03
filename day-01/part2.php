<?php
$data = require 'input.php';

function findTotal($data, $inputNumber1, int $inputNumber2) {
    foreach ($data as $number) {
        if ($number === $inputNumber1 || $number === $inputNumber2) {
            continue;
        }
        if (2020 === ($number + $inputNumber1 + $inputNumber2)) {
            return $number * $inputNumber1 * $inputNumber2;
        }
    }
    return false;
}

foreach ($data as $num1) {
    foreach ($data as $num2) {
        $result = findTotal($data, $num1, $num2);
        if (false !== $result) {
            print $result;
            exit;
        }
    }
}
