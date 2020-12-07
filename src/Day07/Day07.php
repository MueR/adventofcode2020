<?php

declare(strict_types=1);

namespace MueR\AdventOfCode2020\Day07;

use MueR\AdventOfCode2020\AbstractSolver;

class Day07 extends AbstractSolver
{
    private array $rules = [];
    private array $canContain = [];
    private int $totalBags = 0;

    public function partOne(): int
    {
        return count(array_unique($this->resolveUp('shiny gold')));
    }

    public function partTwo(): int
    {
        return $this->resolveDown('shiny gold');
    }

    protected function readInput(): void
    {
        parent::readInput();

        $this->input = explode("\n", $this->input);
        foreach ($this->input as $line) {
            preg_match('/(?<container>.*(?!(contain))) contain (?<contains>.*)\./i', $line, $matches);
            $container = trim(preg_replace('/bags?/', '', $matches['container']));
            $this->rules[$container] = [];
            if ('no other bags' === $matches['contains']) {
                continue;
            }
            array_map(function ($contentRule) use ($container) {
                preg_match('/(?<amount>\d+) (?<type>.*)/i', trim(preg_replace('/bags?/', '', $contentRule)), $contents);
                $this->rules[$container][($contents['type'])] = (int)$contents['amount'];
                $this->canContain[($contents['type'])] = array_merge($this->canContain[($contents['type'])] ?? [], [$container]);
            }, explode(',', $matches['contains']));
        }
    }

    private function resolveUp(string $bag): array
    {
        $possible = [];
        foreach ($this->canContain[$bag] ?? [] as $bagType) {
            array_push($possible, $bagType, ...$this->resolveUp($bagType));
        }

        return $possible;
    }

    private function resolveDown(string $bag): int
    {
        $containsBags = 0;
        foreach ($this->rules[$bag] as $bagType => $amount) {
            $containsBags += $amount + ($amount * $this->resolveDown($bagType));
        }
        return $containsBags;
    }
}
