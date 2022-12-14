<?php

namespace App\Solvers;

use App\Entity\Position;

class Day14_sand
{
    const DATA_PATH = __DIR__ . '/../assets/14-rocks';

    const startX = 500;
    const startY = 0;
    private Position $startPosition;

    private int $xMin;
    private int $xMax;
    private int $yMin;
    private int $yMax;

    private array $map;


    public function countUnitsOfSand($isEndlessVoid = true): int
    {
        $this->startPosition = new Position(self::startX, self::startY);
        $count = 0;

        $this->xMin = $this->xMax = 500;
        $this->yMin = $this->yMax = 0;
        $this->readInput($isEndlessVoid);
        // $this->displayMap();


        while ($this->dropSand()) {
            $count++;
        }
        if(!$isEndlessVoid) $count++;
        // $this->displayMap();

        return $count;
    }

    private function readInput(bool $isEndlessVoid)
    {
        $handle = fopen(self::DATA_PATH, 'r');

        while (($rockPath = fgets($handle)) !== false) {
            $this->addRockPathToMap(trim($rockPath));
        }

        $this->map[self::startX][self::startY] = '+';

        if (!$isEndlessVoid) {
            $rockPath =
                self::startX - $this->yMax - 3 . ',' . $this->yMax + 2 . ' => ' .
                self::startX + $this->yMax + 3 . ',' . $this->yMax + 2;

            $this->addRockPathToMap($rockPath);
        }
    }

    private function addRockPathToMap(string $rockPath): void
    {
        $regex = '/(\d+),(\d+)/';
        preg_match_all($regex, $rockPath, $points);

        for ($i = 0; $i < count($points[0]) - 1; $i++) {
            $x1 = (int)$points[1][$i];
            $x2 = (int)$points[1][$i + 1];
            $this->xMin = min($this->xMin, $x1, $x2);
            $this->xMax = max($this->xMax, $x1, $x2);

            $y1 = (int)$points[2][$i];
            $y2 = (int)$points[2][$i + 1];
            $this->yMin = min($this->yMin, $y1, $y2);
            $this->yMax = max($this->yMax, $y1, $y2);

            if ($x1 == $x2) {
                //vertical Line
                for ($j = min($y1, $y2); $j <= max($y1, $y2); $j++) {
                    $this->map[$x1][$j] = "#";
                }
            } else {
                //horizontal line
                for ($j = min($x1, $x2); $j <= max($x1, $x2); $j++) {
                    $this->map[$j][$y1] = "#";
                }
            }
        }
    }

    private function displayMap(): void
    {
        $count = 0;

        echo "\n";

        for ($j = $this->yMin; $j <= $this->yMax; $j++) {
            echo "\n" . str_pad($j, 4);
            for ($i = $this->xMin; $i <= $this->xMax; $i++) {
                $char = $this->map[$i][$j] ?? '.';
                echo $char;
                $count++;
            }
        }
    }

    private function dropSand(): bool
    {

        $position = clone $this->startPosition;

        while (!$this->isSandStopped($position)) {
            if ($this->isOutOfBoundaries($position)) return false;
        }
        $this->map[$position->X][$position->Y] = 'O';
        if ($position == $this->startPosition) return false;

        return true;
    }

    public function isSandStopped(Position $p): bool
    {
        //down
        if (!isset($this->map[$p->X][$p->Y + 1])) {
            $p->Y++;
            return false;
        }
        //down and left
        if (!isset($this->map[$p->X - 1][$p->Y + 1])) {
            $p->Y++;
            $p->X--;
            return false;
        }
        //down and right
        if (!isset($this->map[$p->X + 1][$p->Y + 1])) {
            $p->Y++;
            $p->X++;
            return false;
        }
        return true;
    }

    public function isOutOfBoundaries(Position $p): bool
    {
        return $p->X > $this->xMax || $p->X < $this->xMin || $p->Y > $this->yMax;
    }
}
