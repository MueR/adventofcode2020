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
            'byr', 'iyr', 'eyr', 'hgt',
            'hcl', 'ecl', 'pid',
        ];

        $valid = 0;
        foreach ($this->passports as $index => $passport) {
            $passKeys = \array_keys($passport);
            $intersect = \array_intersect($requiredFields, $passKeys);
            if (count($intersect) === count($requiredFields)) {
                $valid++;
            }
        }

        return $valid;
    }

    public function partTwo(): int
    {
        $requiredFields = [
            'byr' => fn($test) => \preg_match('/^[\d]{4}$/', $test) && $this->numBetween((int)$test, 1920, 2002),
            'iyr' => fn($test) => \preg_match('/^20(1[\d]|20)$/', $test) && $this->numBetween((int)$test, 2010, 2020),
            'eyr' => fn($test) => \preg_match('/^20(2[\d]|30)$/', $test) && $this->numBetween((int)$test, 2020, 2030),
            'hgt' => function ($test) {
                if (!\preg_match('/^(\d{2,3})(cm|in)$/', $test)) {
                    return false;
                }
                return (false !== \stripos($test, 'cm') && $this->numBetween((int)$test, 150, 193))
                    || (false !== \stripos($test, 'in') && $this->numBetween((int)$test, 59, 76));
            },
            'hcl' => fn($test) => \preg_match('/^#[0-9a-f]{6}$/i', $test),
            'ecl' => fn($test) => \preg_match('/^(amb|blu|brn|gry|grn|hzl|oth)$/', $test),
            'pid' => fn($test) => \preg_match('/^[\d]{9}$/', $test),
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

    protected function numBetween(int $val, int $min, int $max): bool
    {
        return $val >= $min && $val <= $max;
    }

    protected function readInput(): void
    {
        parent::readInput();

        $this->passports = \array_map(
            static function ($passport) {
                \preg_match_all('/((?<keys>[a-z]+):\s?(?<values>[\S]+))/im', $passport, $parts);
                return \array_combine($parts['keys'], $parts['values']);
            },
            \explode("\n\n", $this->input)
        );
    }
}
