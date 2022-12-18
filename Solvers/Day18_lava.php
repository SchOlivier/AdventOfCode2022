<?php

namespace App\Solvers;

use Generator;

class Day18_lava
{
    private array $droplets;

    const DATA_PATH = __DIR__ . '/../assets/18-lava';
    public function partOne():int
    {
        $file = fopen(self::DATA_PATH, 'r');
        $regex = '/(\d+),(\d+),(\d+)/';
        while (($line = fgets($file)) !== false) {
            preg_match($regex, $line, $matches);
            $coord = $matches[0];
            $x = $matches[1];
            $y = $matches[2];
            $z = $matches[3];

            $this->droplets[$coord] = 6;

            foreach ($this->getNeighboursCoords($x, $y, $z) as $neighbour) {
                if (isset($this->droplets[$neighbour])) {
                    $this->droplets[$coord]--;
                    $this->droplets[$neighbour]--;
                }
            }
        }

        return array_sum($this->droplets);
    }

    public function partTwo()
    {

        //Map droplets
        $naiveSurfaceArea = $this->partOne();

        //Map air cells exposed to water
        $stack = ["-1,-1,-1"];
        while (!empty($stack)) {
            $current = array_pop($stack);
            $exposedAir[$current] = true;
            foreach ($this->getNeighboursInCube($current) as $neighbour) {
                if (isset($this->droplets[$neighbour])) continue;
                if (!isset($exposedAir[$neighbour])) $stack[] = $neighbour;
            }
        }

        $hiddenAir = [];
        for ($x = 0; $x < 22; $x++) {
            for ($y = 0; $y < 22; $y++) {
                for ($z = 0; $z < 22; $z++) {
                    $coords = "$x,$y,$z";
                    if (isset($this->droplets[$coords])) continue;
                    if (isset($exposedAir[$coords])) continue;
                    $hiddenAir[$coords] = 6;
                    foreach ($this->getNeighboursCoords($x, $y, $z) as $neighbour) {
                        if (isset($hiddenAir[$neighbour])) {
                            $hiddenAir[$coords]--;
                            $hiddenAir[$neighbour]--;
                        }
                    }
                }
            }
        }

        $hiddenAirSurfaceArea = array_sum($hiddenAir);
        return $naiveSurfaceArea - $hiddenAirSurfaceArea;
    }

    private function getNeighboursCoords(int $x, int $y, int $z): array
    {
        return [
            $x + 1 . ',' . $y . ',' . $z,
            $x - 1 . ',' . $y . ',' . $z,
            $x . ',' . $y + 1 . ',' . $z,
            $x . ',' . $y - 1 . ',' . $z,
            $x . ',' . $y . ',' . $z + 1,
            $x . ',' . $y . ',' . $z - 1
        ];
    }

    private function getNeighboursInCube($coords): array
    {
        // echo "coords : $coords\n";
        $regex = '/(-?\d+),(-?\d+),(-?\d+)/';
        preg_match($regex, $coords, $matches);
        $x = $matches[1];
        $y = $matches[2];
        $z = $matches[3];

        $neighbours = [];

        if ($x < 22) $neighbours[] = $x + 1 . ',' . $y . ',' . $z;
        if ($x > -1) $neighbours[] = $x - 1 . ',' . $y . ',' . $z;
        if ($y < 22) $neighbours[] = $x . ',' . $y + 1 . ',' . $z;
        if ($y > -1) $neighbours[] = $x . ',' . $y - 1 . ',' . $z;
        if ($z < 22) $neighbours[] = $x . ',' . $y . ',' . $z + 1;
        if ($z > -1) $neighbours[] = $x . ',' . $y . ',' . $z - 1;

        return $neighbours;
    }
}
