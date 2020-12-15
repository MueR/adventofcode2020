<?php

declare(strict_types=1);

namespace MueR\AdventOfCode2020\Day13;

use MueR\AdventOfCode2020\AbstractSolver;

class Day13 extends AbstractSolver
{
    private int $timestamp;
    private array $busses;

    public function __construct()
    {
        parent::__construct();

        [$timestamp, $busses] = explode("\n", $this->input);
        $this->timestamp = (int)$timestamp;
        $this->busses = array_map(fn ($bus) => $bus === 'x' ? -1 : (int)$bus, explode(',', $busses));
    }

    public function partOne(): int
    {
        $timeToWait = [];
        foreach ($this->busses as $bus) {
            $timeToWait[$bus] = $bus - $this->timestamp % $bus;
        }
        uasort($timeToWait, static fn ($a, $b) => $a === $b ? 0 : ($a < $b ? -1 : 1));
        $keys = array_keys($timeToWait);
        $bus = array_shift($keys);
        return $bus * array_shift($timeToWait);
    }

    public function partTwo(): int
    {
        $schedules = [];
        foreach (array_filter($this->busses, static fn ($id) => 0 < $id) as $idx => $schedule) {
            $schedules[] = ['idx' => $idx, 'id' => (int)$schedule];
        }
        $inc = array_shift($schedules)['id'];
        $start = 100000000000000;
        $timestamp = $start - $start % $inc;
        foreach ($schedules as $bus) {
            while (($timestamp + $bus['idx']) % $bus['id'] !== 0) {
                $timestamp += $inc;
            }
            $inc *= $bus['id'];
        }
        return $timestamp;
    }


}
