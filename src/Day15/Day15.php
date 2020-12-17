<?php

declare(strict_types=1);

namespace MueR\AdventOfCode2020\Day15;

use JetBrains\PhpStorm\Pure;
use MueR\AdventOfCode2020\AbstractSolver;

class Day15 extends AbstractSolver
{
    /** @var int[] */
    private array $startingNumbers;

    public function __construct()
    {
        parent::__construct();

        $this->startingNumbers = array_map(static fn ($number) => (int)$number, explode(',', $this->input));
    }

    public function partOne(): int
    {
        return $this->memory($this->startingNumbers, 2020);
    }

    public function partTwo(): int
    {
        return $this->memory($this->startingNumbers, 30_000_000);
    }

    #[Pure] private function memory(array $input, int $iteration): int
    {
        $indexed = array_flip($input);
        $next = $input[array_key_last($input)];
        for ($i = count($input); $i < $iteration; ++$i) {
            $last = $next;
            $current = $i - 1;
            $next = isset($indexed[$last]) ? $current - $indexed[$last] : 0;
            $indexed[$last] = $current;
        }
        return $next;
    }
}
