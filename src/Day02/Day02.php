<?php

namespace MueR\AdventOfCode2020\Day02;

use MueR\AdventOfCode2020\AbstractSolver;
use function array_filter;
use function count;
use function substr;
use function substr_count;

class Day02 extends AbstractSolver
{
    public function partOne(): int
    {
        $valid = array_filter($this->input, static function ($entry) {
            $count = substr_count($entry['password'], $entry['char']);
            return ($count >= $entry['min'] && $count <= $entry['max']);
        });
        return count($valid);
    }

    public function partTwo(): int
    {
        $valid = array_filter($this->input, static function ($entry) {
            $first = substr($entry['password'], $entry['min'] - 1, 1) === $entry['char'];
            $second = substr($entry['password'], $entry['max'] - 1, 1) === $entry['char'];
            return ($first && !$second) || (!$first && $second);
        });

        return count($valid);
    }
}
