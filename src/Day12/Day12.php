<?php

namespace MueR\AdventOfCode2020\Day12;

use MueR\AdventOfCode2020\AbstractSolver;

class Day12 extends AbstractSolver
{
    private static Vector $north;
    private static Vector $east;
    private static Vector $south;
    private static Vector $west;
    private Vector $position;
    private Vector $direction;

    private array $commands;

    public function __construct()
    {
        parent::__construct();

        self::$north = new Vector(0, 1);
        self::$east = new Vector(1, 0);
        self::$south = new Vector(0, -1);
        self::$west = new Vector(-1, 0);

        $this->commands = array_map(fn ($line) => [$line[0], (int)substr($line, 1)] ,explode("\n", $this->input));
    }

    public function partOne(): int
    {
        $this->direction = self::$east;
        $this->position = new Vector(0, 0);
        foreach ($this->commands as $command) {
            $this->move($command[0], $command[1]);
        }
        return abs($this->position->x) + abs($this->position->y);
    }

    public function partTwo(): int
    {
        $this->direction = new Vector(10, 1);
        $this->position = new Vector(0, 0);
        foreach ($this->commands as $command) {
            $this->moveWaypoint($command[0], $command[1]);
        }
        print abs($this->position->x) . ' ' . abs($this->position->y);
        return abs($this->position->x) + abs($this->position->y);
    }

    private function move(string $direction, int $units): void
    {
        switch ($direction) {
            case 'N': $this->position->y += $units * self::$north->y; break;
            case 'E': $this->position->x += $units * self::$east->x; break;
            case 'S': $this->position->y += $units * self::$south->y; break;
            case 'W': $this->position->x += $units * self::$west->x; break;
            case 'L': $this->turn($this->direction, -$units); break;
            case 'R': $this->turn($this->direction, $units); break;
            case 'F':
                $this->position->x += $units * $this->direction->x;
                $this->position->y += $units * $this->direction->y;
                break;
        }
    }

    private function moveWaypoint(string $direction, int $units): void
    {
        switch ($direction) {
            case 'N': $this->direction->y += $units * self::$north->y; break;
            case 'E': $this->direction->x += $units * self::$east->x; break;
            case 'S': $this->direction->y += $units * self::$south->y; break;
            case 'W': $this->direction->x += $units * self::$west->x; break;
            case 'L': $this->turn($this->direction, -$units); break;
            case 'R': $this->turn($this->direction, $units); break;
            case 'F':
                $this->position->x += $units * $this->direction->x;
                $this->position->y += $units * $this->direction->y;
                break;
        }
    }

    private function turn(Vector $current, int $degrees): void
    {
        $this->direction = match ($degrees < 0 ? 360 + $degrees : $degrees) {
            90 => new Vector($current->y, -$current->x),
            180 => new Vector(-$current->x, -$current->y),
            270 => new Vector(-$current->y, $current->x),
        };
    }
}

class Vector { public function __construct(public int $x, public int $y) {} }
