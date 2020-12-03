<?php

$map = require 'input.php';

function travel(array $map, int $stepLeft, int $stepDown)
{
    $trees = 0;
    $charsInLine = strlen($map[0]);
    $numLines = count($map);
    for ($left = $stepLeft, $down = $stepDown; $down < $numLines; $left += $stepLeft, $down += $stepDown) {
        if ('#' === substr($map[$down], $left % $charsInLine, 1)) {
            $trees++;
        }
    }

    return $trees;
}
print 'Part 1: ' . travel($map, 3, 1) . "\n";

$total = 1;
foreach ([[1,1],[3,1],[5,1],[7,1],[1,2]] as $steps) {
    $result = travel($map, $steps[0], $steps[1]);
    $total *= $result;
    printf("Left: %d, down: %d, result: %d trees, total %d\n", $steps[0], $steps[1], $result, $total);
}

print 'Part 2: ' . $total . "\n\n";
