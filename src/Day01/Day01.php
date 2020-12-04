<?php

namespace MueR\AdventOfCode2020\Day01;

use JetBrains\PhpStorm\Pure;
use MueR\AdventOfCode2020\AbstractSolver;

class Day01 extends AbstractSolver
{
    #[Pure] public function partOne(): int
    {
        foreach ($this->input as $num) {
            $result = $this->findTotal($num);
            if (false !== $result) {
                return $result;
            }
        }

        return -1;
    }

    #[Pure] public function partTwo(): int
    {
        foreach ($this->input as $num1) {
            foreach ($this->input as $num2) {
                $result = $this->findPart2($num1, $num2);
                if (false !== $result) {
                    return $result;
                }
            }
        }
        return -1;
    }

    private function findTotal(int $inputNumber1): bool|int
    {
        foreach ($this->input as $number) {
            if ($number === $inputNumber1) {
                continue;
            }
            if (2020 === ($number + $inputNumber1)) {
                return $number * $inputNumber1;
            }
        }
        return false;
    }

    private function findPart2(int $inputNumber1, int $inputNumber2): bool|int
    {
        if (2020 < $inputNumber1 + $inputNumber2) {
            return false;
        }
        foreach ($this->input as $number) {
            if ($number === $inputNumber1 || $number === $inputNumber2) {
                continue;
            }
            if (2020 === ($number + $inputNumber1 + $inputNumber2)) {
                return $number * $inputNumber1 * $inputNumber2;
            }
        }
        return false;
    }
}
