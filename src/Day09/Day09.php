<?php

declare(strict_types=1);

namespace MueR\AdventOfCode2020\Day09;

use MueR\AdventOfCode2020\AbstractSolver;

class Day09 extends AbstractSolver
{
    private int $weaknessTarget;
    private array $numbers = [];

    public function partOne(): int
    {
        for ($i = 25, $max = count($this->numbers); $i < $max; $i++) {
            if (!$this->isValid($i, $this->numbers[$i])) {
                $this->weaknessTarget = $this->numbers[$i];
                return $this->numbers[$i];
            }
        }

        return -1;
    }

    public function partTwo(): int
    {
        for ($i = 0, $max = count($this->numbers); $i < $max; $i++) {
            $result = $this->findRange($i);
            if ($result) {
                $range = array_slice($this->numbers, $result[0], $result[1]);
                sort($range, SORT_NUMERIC);
                return array_shift($range) + array_pop($range);
            }
        }
        return -1;
    }

    private function isValid(int $index, int $expected): bool
    {
        $preamble = array_slice($this->numbers, $index - 25, 25);
        foreach ($preamble as $num) {
            $match = array_filter($preamble, static fn (int $val) => $val + $num === $expected);
            if (!empty($match)) {
                return true;
            }
        }

        return false;
    }

    private function findRange(int $startIndex): array|null
    {
        $sum = 0;
        for ($i = $startIndex, $max = count($this->numbers); $i < $max; $i++) {
            $sum += $this->numbers[$i];
            if ($sum > $this->weaknessTarget) {
                return null;
            }
            if ($sum === $this->weaknessTarget) {
                return [$startIndex, $i - $startIndex];
            }
        }

        return null;
    }

    protected function readInput(): void
    {
        parent::readInput();

        $this->numbers = array_map(static fn (string $val) => (int)$val, \explode("\n", $this->input));
    }
}
