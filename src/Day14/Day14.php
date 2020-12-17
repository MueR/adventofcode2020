<?php

declare(strict_types=1);

namespace MueR\AdventOfCode2020\Day14;

use MueR\AdventOfCode2020\AbstractSolver;

class Day14 extends AbstractSolver
{
    public function __construct()
    {
        parent::__construct();

        $this->input = explode("\n", $this->input);
    }

    public function partOne(): int
    {
        $mask = '';
        $buffer = [];
        foreach ($this->input as $line) {
            if (preg_match('/^mask = (?<mask>[X01]{36})$/',$line,$matches)) {
                $mask = $matches['mask'];
            } elseif (preg_match('/^mem\[(?<reg>\d+)\] = (?<value>\d+)$/',$line,$matches)) {
                $buffer[$matches['reg']] = $this->applyMask((int)$matches['value'], $mask);
            }
        }
        return array_sum($buffer);
    }

    public function partTwo(): int
    {
        $mask = '';
        $buffer = [];
        foreach ($this->input as $line) {
            if (preg_match('/^mask = (?<mask>[X01]{36})$/',$line,$matches)) {
                $mask = $matches['mask'];
            } elseif (preg_match('/^mem\[(?<reg>\d+)\] = (?<value>\d+)$/',$line,$matches)) {
                foreach ($this->getAddresses((int)$matches['reg'], $mask) as $address) {
                    $buffer[$address] = (int)$matches['value'];
                }
            }
        }
        return array_sum($buffer);
    }

    private function applyMask(int $input, string $mask): int
    {
        $input &= bindec(str_replace('X', '1', $mask));
        $input |= bindec(str_replace('X','0', $mask));
        $input |= bindec(str_replace('X','0', $mask));

        return $input;
    }

    private function getAddresses(int $input, string $mask): array
    {
        $converted = str_pad(decbin($input), strlen($mask), '0', STR_PAD_LEFT);
        $floating = array_keys(array_filter(str_split($mask), fn (string $x) => 'X' === $x));
        foreach (str_split($mask) as $i => $char) {
            switch ($char) {
                /** @noinspection PhpMissingBreakStatementInspection */
                case 'X':
                    $floating[] = $i;
                case '1':
                    $converted[$i] = $char;
                    break;
            }
        }
        $addresses = [];
        $total = substr_count($converted, 'X');
        for ($i = 0, $m = 1 << $total; $i < $m; ++$i) {
            $maskVal = str_pad(decbin($i), $total, '0', STR_PAD_LEFT);
            $tmp = $converted;
            for ($j = 0; $j < $total; ++$j) {
                $tmp[($floating[$j])] = $maskVal[$j];
            }
            $addresses[] = bindec($tmp);
        }

        return $addresses;
    }
}
