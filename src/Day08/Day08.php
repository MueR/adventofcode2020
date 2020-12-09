<?php

declare(strict_types=1);

namespace MueR\AdventOfCode2020\Day08;

use MueR\AdventOfCode2020\AbstractSolver;

class Day08 extends AbstractSolver
{
    private array $instructions = [];

    public function partOne(): int
    {
        return $this->execute($this->instructions)->accumulator;
    }

    public function partTwo(): int
    {
        for ($i = 0, $max = count($this->instructions); $i < $max; $i++) {
            if ('acc' === $this->instructions[$i]['action']) {
                continue;
            }

            $result = $this->execute($this->swapInstruction($i));
            if (!$result->terminated) {
                return $result->accumulator;
            }
        }

        return -1;
    }

    protected function swapInstruction(int $i): array
    {
        $instructions = $this->instructions;
        $instructions[$i]['action'] = 'nop' === $instructions[$i]['action'] ? 'jmp' : 'nop';

        return $instructions;
    }

    protected function readInput(): void
    {
        parent::readInput();

        array_map(function ($instruction) {
            preg_match('/(acc|jmp|nop) ([+-]\d+)/', $instruction, $parsed);
            $this->instructions[] = ['action' => $parsed[1], 'acc' => (int)$parsed[2]];
        }, explode("\n", $this->input));
    }

    private function execute(array $instructions): Result
    {
        $accumulator = 0;
        $pointer = 0;
        $executed = [];

        while ($pointer < count($instructions)) {
            if (in_array($pointer, $executed, true)) {
                return new Result(true, $accumulator, $pointer);
            }
            $executed[] = $pointer;
            switch ($instructions[$pointer]['action']) {
                case 'jmp':
                    $pointer += $instructions[$pointer]['acc'];
                    break;
                case 'acc':
                    $accumulator += $instructions[$pointer]['acc'];
                    $pointer++;
                    break;
                case 'nop':
                    $pointer++;
                    break;
            }
        }

        return new Result($pointer !== count($instructions), $accumulator, $pointer);
    }
}

class Result
{
    public function __construct(public bool $terminated, public int $accumulator, public int $pointer) {}
}
