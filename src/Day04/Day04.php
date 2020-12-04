<?php

namespace MueR\AdventOfCode2020\Day04;

use JetBrains\PhpStorm\Pure;
use MueR\AdventOfCode2020\AbstractSolver;

class Day04 extends AbstractSolver
{
    private array $passports = [];

    #[Pure] public function partOne(): int
    {
        $requiredFields = [
            'byr',
            'iyr',
            'eyr',
            'hgt',
            'hcl',
            'ecl',
            'pid',
            //'cid',
        ];

        $valid = 0;
        foreach ($this->passports as $index => $passport) {
            $passKeys = \array_keys($passport);
            $intersect = \array_intersect($requiredFields, $passKeys);
            if (count($intersect) === count($requiredFields)) {
                $valid ++;
            }
        }

        return $valid;
    }

    public function partTwo(): int
    {
        $requiredFields = [
            'byr' => fn ($test) => \preg_match('/^[\d]{4}$/', $test) && $this->numBetween((int)$test, 1920, 2002),
            'iyr' => fn ($test) => \preg_match('/^20(1[\d]|20)$/', $test) && $this->numBetween((int)$test, 2010, 2020),
            'eyr' => fn ($test) => \preg_match('/^20(2[\d]|30)$/', $test) && $this->numBetween((int)$test, 2020, 2030),
            'hgt' => function ($test) {
                if (!\preg_match('/^(\d{2,3})(cm|in)$/', $test)) {
                    return false;
                }
                if (false !== \stripos($test, 'cm')) {
                    return $this->numBetween((int)$test, 150, 193);
                }
                if (false !== \stripos($test, 'in')) {
                    return $this->numBetween((int)$test, 59, 76);
                }
                return false;
            },
            'hcl' => fn ($test) => \preg_match('/^#[0-9a-f]{6}$/i', $test),
            'ecl' => fn ($test) => \preg_match('/^(amb|blu|brn|gry|grn|hzl|oth)$/', $test),
            'pid' => fn ($test) => \preg_match('/^[\d]{9}$/', $test),
        ];

        foreach ($this->passports as $index => $passport) {
            foreach ($requiredFields as $field => $testFn) {
                if (!\array_key_exists($field, $passport) || !$testFn($passport[$field])) {
                    unset($this->passports[$index]);
                    continue 2;
                }
            }
        }

        return count($this->passports);
    }

    protected function numBetween(int $val, $min, $max): bool
    {
        return $val >= $min && $val <= $max;
    }

    protected function readInput(): void
    {
        parent::readInput();

        $this->passports = \explode("\n\n", $this->input);
        $this->passports = \array_map(function ($passport) {
            $parts = [];
            $result = [];
            \preg_match_all('/([a-z]+:[\S]+)/im', $passport, $parts);
            foreach ($parts[1] as $part) {
                \preg_match('/([a-z]+):\s?([\S]+)/m', $part, $match);
                \array_shift($match);
                if (\count($match) === 2) {
                    $result[$match[0]] = $match[1];
                }
            }
            return $result;
        }, $this->passports);
    }
}
