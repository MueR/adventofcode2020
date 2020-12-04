<?php

use AdventOfCode2020\Day04\CheckPassport;

require 'AbstractSolver.php';
require 'day-04/CheckPassport.php';

$test = new CheckPassport();
print $test->partOne() . "\n\n";
print $test->partTwo();
