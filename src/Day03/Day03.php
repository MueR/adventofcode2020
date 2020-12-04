<?php

namespace MueR\AdventOfCode2020\Day03;

use MueR\AdventOfCode2020\AbstractSolver;
use function count;
use function strlen;
use function substr;

class Day03 extends AbstractSolver
{
    private int $charsInLine;

    private int $numLines;

    public function __construct()
    {
        parent::__construct();

        $this->charsInLine = strlen($this->input[0]);
        $this->numLines = count($this->input);
    }

    public function partOne(): int
    {
        return $this->travel(3, 1);
    }

    public function partTwo(): int
    {
        $total = 1;
        foreach ([[1, 1], [3, 1], [5, 1], [7, 1], [1, 2]] as $steps) {
            $result = $this->travel($steps[0], $steps[1]);
            $total *= $result;
        }

        return $total;
    }

    private function travel(int $stepLeft, int $stepDown): int
    {
        $trees = 0;
        for ($left = $stepLeft, $down = $stepDown; $down < $this->numLines; $left += $stepLeft, $down += $stepDown) {
            if ('#' === substr($this->input[$down], $left % $this->charsInLine, 1)) {
                $trees++;
            }
        }

        return $trees;
    }
}
