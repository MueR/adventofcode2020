<?php

namespace MueR\AdventOfCode2020;

abstract class AbstractSolver
{
    protected string|array $input;

    public function __construct()
    {
        $this->readInput();
    }

    abstract public function partOne(): int;
    abstract public function partTwo(): int;

    protected function readInput(): void
    {
        $ns = substr(get_class($this), 0, strrpos(get_class($this), '\\'));
        \preg_match('/(Day[\d]{2})/', $ns, $match);
        if ($match) {
            $this->input = require __DIR__ . '/' . $match[1] . '/input.php';
        }
    }
}
