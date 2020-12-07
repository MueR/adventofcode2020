<?php


namespace MueR\AdventOfCode2020\Day05;


use JetBrains\PhpStorm\Pure;
use MueR\AdventOfCode2020\AbstractSolver;

class Day05 extends AbstractSolver
{
    private array $passes = [];
    private array $seatsTaken = [];

    public function partOne(): int
    {
        $highestSeatId = 0;
        foreach ($this->passes as $pass) {
            $row = $this->solvePartition($pass['row'], 127);
            $seat = $this->solvePartition($pass['seat'], 7);
            $seatId = $row * 8 + $seat;
            $this->seatsTaken[] = $seatId;
            $highestSeatId = max($highestSeatId, $seatId);
        }

        return $highestSeatId;
    }

    #[Pure] public function partTwo(): int
    {
        foreach (array_diff(range(0, (127*8)+7), $this->seatsTaken) as $seatNumber) {
            if (in_array($seatNumber - 1, $this->seatsTaken, true) && in_array($seatNumber + 1, $this->seatsTaken, true)) {
                return $seatNumber;
            }
        }
    }

    protected function solvePartition(string $rowPartition, int $max): int
    {
        $parts = str_split($rowPartition, 1);
        $min = 0;
        $diff = ceil($max / 2);
        do {
            $letter = array_shift($parts);
            match ($letter) {
                'B','R' => $min += $diff,
                'F','L' => $max -= $diff,
            };
            $diff = ceil($diff / 2);
        } while (!empty($parts));

        return $min;
    }

    protected function readInput(): void
    {
        parent::readInput();

        $this->passes = \array_map(
            fn ($pass) => ['row' => substr($pass, 0, 7), 'seat' => substr($pass, 7, 3)],
            explode("\n", $this->input)
        );
    }
}
