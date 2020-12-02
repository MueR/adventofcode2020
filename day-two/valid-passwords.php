<?php
$input = require 'input.php';

$valid = array_filter($input, static function ($entry) {
    $count = substr_count($entry['password'], $entry['char']);
    return ($count >= $entry['min'] && $count <= $entry['max']);
});
print count($valid) . "\n\n";


$valid = array_filter($input, static function ($entry) {
    $first = substr($entry['password'], $entry['min'] - 1, 1) === $entry['char'];
    $second = substr($entry['password'], $entry['max'] - 1, 1) === $entry['char'];
    return ($first && !$second) || (!$first && $second);
});

print count($valid) . "\n\n";
