<?php

declare(strict_types=1);

namespace MueR\AdventOfCode2020\Day10;

use JetBrains\PhpStorm\Pure;
use MueR\AdventOfCode2020\AbstractSolver;

class Day10 extends AbstractSolver
{
    public function __construct(
        private array $adapters = [],
        private array $paths = []
    ) {
        parent::__construct();
        ini_set('xdebug.max_nesting_level', '500');
    }

    #[Pure] public function partOne(): int
    {
        $d1 = 0;
        $d3 = 1;
        foreach ($this->adapters as $idx => $jolts) {
            match ($jolts - ($this->adapters[$idx - 1] ?? 0)) { 1 => $d1++, 3 => $d3++ };
        }
        return $d1 * $d3;
    }

    public function partTwo(): int
    {
        return $this->combinations(0, $this->adapters);
    }

    protected function readInput(): void
    {
        parent::readInput();
        $this->adapters = array_map(static fn ($jolts) => (int)$jolts, explode("\n", $this->input));
        sort($this->adapters, SORT_NUMERIC);
    }

    private function combinations(int $currentValue, array $input): int
    {
        if (empty($input)) {
            return 1;
        }
        if (array_key_exists($currentValue, $this->paths)) {
            return $this->paths[$currentValue];
        }
        $next = array_filter($input, static fn ($x) => $x > $currentValue && $x <= $currentValue + 3);
        $this->paths[$currentValue] = array_sum(array_map(
            fn ($x, $idx) => $this->combinations($x, array_slice($input, $idx + 1)),
            $next,
            array_keys($next)
        ));
        return $this->paths[$currentValue];
    }
}
