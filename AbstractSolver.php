<?php

namespace AdventOfCode2020;

abstract class AbstractSolver
{
    protected string $input;

    public function __construct(
        public int $day
    )
    {
    }

    abstract public function partOne(): int;
    abstract public function partTwo(): int;

    protected function readInput()
    {
        $this->input = require \sprintf('%s/day-%02d/input.php', __DIR__, $this->day);
    }
}
