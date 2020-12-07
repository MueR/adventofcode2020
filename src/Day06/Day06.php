<?php

declare(strict_types=1);

namespace MueR\AdventOfCode2020\Day06;

use MueR\AdventOfCode2020\AbstractSolver;

class Day06 extends AbstractSolver
{
    private array $groups = [];
    private array $questions = [];

    public function partOne(): int
    {
        $total = 0;
        foreach ($this->questions as $index => $byPerson) {
            $total += count(array_filter($byPerson, static fn ($item) => $item > 0));
        }
        return $total;
    }

    public function partTwo(): int
    {
        $total = 0;
        foreach ($this->questions as $index => $byPerson) {
            $total += count(array_filter($byPerson, fn ($item) => $item === count($this->groups[$index])));
        }

        return $total;
    }

    protected function readInput(): void
    {
        parent::readInput();

        $this->groups = array_map(
            static fn ($group) => array_map(
                static fn ($person) => array_unique(str_split($person, 1)),
                explode("\n", $group)
            ),
            explode("\n\n", $this->input)
        );
        foreach ($this->groups as $index => $byPerson) {
            $this->questions[$index] = array_fill_keys(range(ord('a'), ord('z')), 0);
            foreach ($byPerson as $answers) {
                foreach ($answers as $letter) {
                    $this->questions[$index][ord($letter)]++;
                }
            }
        }
    }
}
