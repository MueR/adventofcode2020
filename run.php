<?php

declare(strict_types=1);

use MueR\AdventOfCode2020\AbstractSolver;

require_once __DIR__ . '/vendor/autoload.php';

$arguments = $argv;

$informationArray = [];

if (array_key_exists(1, $arguments)) {
    $day = $arguments[1];
    $informationArray[] = printDay((int)$day);
} else {
    foreach (range(1, 24) as $day) {
        $informationArray[] = printDay((int)$day);
    }
}

function printDay(int $day): array
{
    $class = sprintf('MueR\\AdventOfCode2020\\Day%02d\\Day%02d', $day, $day);

    echo sprintf('--- Day %s ---', $day) . PHP_EOL;

    $dayString = sprintf('%02d', $day);

    if (!class_exists($class)) {
        echo 'Skipped day because '.$class.' does not exist' . PHP_EOL;

        return [
            'day' => $day,
        ];
    }

    try {
        /** @var AbstractSolver $instance */
        $instance = new $class();
    } catch (RuntimeException $e) {
        echo 'Skipped day because "' . $e->getMessage() . '"' . PHP_EOL;

        return [
            'day' => $dayString,
            'part_one_solution' => null,
            'part_two_solution' => null,
        ];
    }

    try {
        $partOneSolution = $instance->partOne();
    } catch (RuntimeException $e) {
        $partOneSolution = 'Skipped part 1 because "' . $e->getMessage() . '"';
        echo $partOneSolution . PHP_EOL;
    }

    try {
        $partTwoSolution = $instance->partTwo();
    } catch (RuntimeException $e) {
        $partTwoSolution = 'Skipped part 2 because "' . $e->getMessage() . '"';
        echo $partTwoSolution . PHP_EOL;
    }

    return [
        'day' => $dayString,
        'part_one_solution' => $partOneSolution,
        'part_two_solution' => $partTwoSolution,
    ];
}

$content = '';

foreach ($informationArray as $information) {
    $content .= '| ' . sprintf('%02d', $information['day']) . ' ';
    $content .= '| ' . ($information['part_one_solution'] ?? '-') . ' ';
    $content .= '| ' . ($information['part_two_solution'] ?? '-') . ' ';
    $content .= "|\n";
}

print $content;
