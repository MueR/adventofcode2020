<?php

declare(strict_types=1);

namespace MueR\AdventOfCode2020\Day11;

use MueR\AdventOfCode2020\AbstractSolver;
use function array_fill;
use function array_filter;
use function array_key_exists;
use function array_map;
use function array_sum;
use function array_walk;
use function explode;
use function str_split;
use const ARRAY_FILTER_USE_BOTH;

class Day11 extends AbstractSolver
{
    private const TAKEN = '#';
    private const EMPTY = 'L';
    private const FLOOR = '.';
    private array $seats;
    private array $result = [];
    private array $directions;
    private int $width;
    private int $height;

    public function __construct()
    {
        parent::__construct();

        $this->seats = array_map(static fn ($line) => str_split($line), explode("\n", $this->input));
        $this->directions = [
            new Direction(-1, -1), new Direction(0, -1), new Direction(1, -1),
            new Direction(-1, 0), new Direction(1, 0),
            new Direction(-1, 1), new Direction(0, 1), new Direction(1, 1),
        ];
        $this->height = count($this->seats);
        $this->width = count($this->seats[0]);
    }

    public function partOne(): int
    {
        $last = 0;
        while (true) {
            $taken = $this->solveOne($this->seats);
            if ($taken === $last) {
                return $taken;
            }
            $last = $taken;
            $this->seats = $this->result;
        }
        return -1;
    }

    public function partTwo(): int
    {
        $this->seats = array_map(static fn ($line) => str_split($line), explode("\n", $this->input));
        $last = 0;
        $i = 0;
        while (true) {
            $taken = $this->solveTwo($this->seats);
            if ($taken === $last) {
                return $taken;
            }
            $last = $taken;
            $this->seats = $this->result;
        }
        return -1;
    }

    public function solveOne(array $input): int
    {
        $this->result = [];
        array_walk($input, function ($row, $y) {
            array_walk($row, function ($seat, $x) use ($y) {
                $this->result[$y][$x] = (
                    ($seat === self::TAKEN && 4 <= $this->adjacentTakenSeats($y, $x)) ||
                    ($seat === self::EMPTY && 0 === $this->adjacentTakenSeats($y, $x))
                )
                    ? $this->swap($y, $x) :
                    $seat;
            }, ARRAY_FILTER_USE_BOTH);
        }, ARRAY_FILTER_USE_BOTH);

        return array_sum(array_map(fn ($row) => count(array_filter($row, fn ($seat) => $seat === self::TAKEN)), $this->result));
    }

    public function solveTwo(array $input): int
    {
        $this->result = [];
        array_walk($input, function ($row, $y) {
            array_walk($row, function ($seat, $x) use ($y) {
                $taken = $this->visibleTakenSeats($y, $x);
                $this->result[$y][$x] = (
                    ($seat === self::TAKEN && 5 <= $taken) ||
                    ($seat === self::EMPTY && 0 === $taken)
                )
                    ? $this->swap($y, $x) :
                    $seat;
            }, ARRAY_FILTER_USE_BOTH);
        }, ARRAY_FILTER_USE_BOTH);

        return array_sum(array_map(fn ($row) => count(array_filter($row, fn ($seat) => $seat === self::TAKEN)), $this->result));
    }

    private function swap(int $y, int $x): string
    {
        return match ($this->seats[$y][$x]) {
            self::TAKEN => self::EMPTY,
            self::EMPTY => self::TAKEN,
            self::FLOOR => self::FLOOR,
        };
    }

    private function isValid(int $y, int $x): bool
    {
        return array_key_exists($y, $this->seats) && array_key_exists($x, $this->seats[$y]);
    }

    private function adjacentTakenSeats(int $y, int $x): int
    {
        if (!$this->isValid($y, $x)) {
            return 0;
        }
        $result = 0;
        foreach ($this->directions as $direction) {
            $result += ($this->isTaken($y + $direction->y, $x + $direction->x) ? 1 : 0);
        }
        return $result;
    }

    private function visibleTakenSeats(int $y, int $x): int
    {
        if (!$this->isValid($y, $x)) {
            return 0;
        }
        $result = 0;
        $current = new Direction($x, $y);
        foreach ($this->directions as $direction) {
            $seat = $this->findSeat(new Direction($current->x + $direction->x, $current->y + $direction->y), $direction);
            if (null === $seat) {
                continue;
            }
            $result += ($this->isTaken($seat->y, $seat->x) ? 1 : 0);
        }
        return $result;
    }

    private function isTaken(int $y, int $x): bool
    {
        return $this->isValid($y, $x) && self::TAKEN === $this->seats[$y][$x];
    }

    private function findSeat(Direction $current, Direction $path): ?Direction
    {
        if (!$this->isValid($current->y, $current->x)) {
            return null;
        }
        if ($this->seats[$current->y][$current->x] !== self::FLOOR) {
            return $current;
        }
        return $this->findSeat(new Direction($current->x + $path->x, $current->y + $path->y), $path);
    }
}

class Direction
{
    public function __construct(public int $x, public int $y) {}
}
