<?php /** @noinspection PhpMultipleClassesDeclarationsInOneFile */

declare(strict_types=1);

namespace MueR\AdventOfCode2020\Day17;

use JetBrains\PhpStorm\Pure;
use MueR\AdventOfCode2020\AbstractSolver;
use SplObjectStorage;

class Day17 extends AbstractSolver
{
    public function __construct()
    {
        parent::__construct();


    }

    public function partOne(): int
    {
        return -1;
    }

    public function partTwo(): int
    {
        return -1;
    }
}

class Cube
{
    public function __construct(public int $x, public int $y, public int $z) {}
    public function getCoordinates(): array { return [$this->x, $this->y, $this->z]; }
    public function setCoordinates(int $x, int $y, int $z): void { $this->x = $x; $this->y = $y; $this->z = $z; }
}

class CubeGrid
{
    public SplObjectStorage $cubes;

    public function getCube(int $x, int $y, int $z): ?Cube
    {
        return $this->cubes->contains(new Cube($x, $y, $z))
            ? new Cube($x, $y, $z)
            : null;
    }

    public function addCube(Cube $cube, bool $active = false): void
    {
        $this->cubes->attach($cube, $active);
    }

    public function isActive(Cube $cube): bool
    {
        return true === $this->cubes[$cube];
    }

    public function toggleState(Cube $cube): void
    {
        if ($this->cubes->contains($cube)) {
            $this->cubes[$cube] = !$this->cubes[$cube];
        }
    }

    public function activate(Cube $cube): void
    {
        $this->cubes[$cube] = true;
    }

    public function deactivate(Cube $cube): void
    {
        $this->cubes[$cube] = false;
    }

    /** @returns Cube[] */
    public function getNeighbours(Cube $cube): iterable
    {
        $target = clone $cube;
        for ($x = $cube->x - 1; $x <= $cube->x + 1; $x++) {
            for ($y = $cube->y - 1; $y < $cube->y + 1; $y++) {
                for ($z = $cube->z - 1; $z < $cube->z + 1; $z++) {
                    $target->setCoordinates($x, $y, $z);
                    if ($target !== $cube && $this->cubes->contains($target)) {
                        yield $this->cubes[$target];
                    }
                }
            }
        }
    }

    public function simulate(int $cycles): void
    {
        for ($cycle = 0; $cycle < $cycles; $cycle++) {
            $toActivate = [];
            $toDeactivate = [];

            foreach ($this->cubes as $cube => $state) {
                //$activeNeighbours = count(array_filter(iterator_to_array($this->getNeighbours($cube)), fn ($state) => $state))
            }
        }
    }
}
