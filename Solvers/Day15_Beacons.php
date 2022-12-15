<?php

namespace App\Solvers;

use App\Entity\Position;

class Day15_Beacons
{
    const DATA_PATH = __DIR__ . '/../assets/15-beacons';

    private array $sensors;
    private array $beacons;

    public function __construct()
    {
        $this->readInput();
    }

    public function partOne(int $y): int
    {
        $ranges = $this->getCoveredRangesForLine($y);

        $count = 0;
        foreach ($ranges as $range) {
            $count += $range['end'] - $range['start'] + 1;
            $count -= $this->countBeaconsInRange($y, $range);
        }

        return $count;
    }

    public function partTwo(int $gridSize): int
    {
        $x = false;
        for ($y = 0; $y <= $gridSize; $y++) {
            $ranges = $this->getCoveredRangesForLine($y);
            $x = $this->findHoleInRanges($gridSize, $ranges);
            if ($x !== false) {
                return $x * 4000000 + $y;
            }
        }
        return 0;
    }

    public function partTwoTake2(int $gridSize): int
    {
        $xMin = $yMin = 0;
        $xMax = $yMax = $gridSize;

        $closeSensors = [];
        for ($i = 0; $i < count($this->sensors); $i++) {
            for ($j = $i + 1; $j < count($this->sensors); $j++) {
                if (2 == $this->getSpaceBetweenSensors($this->sensors[$i], $this->sensors[$j])) {
                    $closeSensors[] = [$this->sensors[$i], $this->sensors[$j]];
                }
            }
        }

        $point = $this->findIntersection($closeSensors[0], $closeSensors[1]);

        return $point->X * 4000000 + $point->Y;
    }

    private function findIntersection(array $pair1, array $pair2): Position
    {
        //Determine equation (y = ax + b) of the line between sensors of pair 1.
        // a is either +1 or -1, coeff of pair 2 will be the opposite.
        $a1 = $this->getCoeffA($pair1[0], $pair1[1]);
        $a2 = -$a1;

        $b1 = $this->getCoeffB($pair1[0], $pair1[1], $a1);
        $b2 = $this->getCoeffB($pair2[0], $pair2[1], $a2);

        $x = abs(($b1 - $b2) / 2);
        $y = ($b1 + $b2) / 2;

        return new Position($x, $y);
    }

    private function getCoeffA(Position $s1, Position $s2): int
    {
        $dx = ($s1->X - $s2->X) / abs(($s1->X - $s2->X));
        $dy = ($s1->Y - $s2->Y) / abs(($s1->Y - $s2->Y));
        return $dx / $dy;
    }

    private function getCoeffB(Position $s1, Position $s2, int $coeffA): int
    {
        // use the leftmost one :
        $s = $s1->X < $s2->X ? $s1 : $s2;
        return $coeffA == -1 ?
        $s->Y - $s->X - $s->value - 1 :
            $s->Y + $s->X + $s->value + 1;
    }

    private function getCoveredRangesForLine(int $y): array
    {
        $ranges = [];
        foreach ($this->sensors as $sensor) {
            $distance = $this->getDistanceToMaxRange($sensor, $y);
            if ($distance >= 0) {
                $start = $sensor->X - $distance;
                $end = $sensor->X + $distance;
                foreach ($ranges as $i => $range) {
                    if ($start < $range['start'] && $end > $range['end']) {
                        unset($ranges[$i]);
                        continue;
                    }
                    if ($range['start'] <= $start && $start <= $range['end']) {
                        $start = $range['end'] + 1;
                    }
                    if ($range['start'] <= $end && $end <= $range['end']) {
                        $end = $range['start'] - 1;
                    }
                }
                if ($start <= $end) {
                    $ranges[] = ['start' => $start, 'end' => $end];
                }
            }
        }
        return $ranges;
    }

    private function findHoleInRanges(int $gridSize, array $ranges): int|false
    {
        usort($ranges, function ($a, $b) {
            return $a['start'] <=> $b['start'];
        });
        for ($i = 0; $i < count($ranges) - 1; $i++) {
            if (
                $ranges[$i]['end'] + 1 != $ranges[$i + 1]['start']
                && 0 <= $ranges[$i]['end'] + 1 && $ranges[$i]['end'] + 1 <= $gridSize
            ) {
                return $ranges[$i]['end'] + 1;
            }
        }
        return false;
    }

    private function countBeaconsInRange(int $y, array $range): int
    {
        $count = 0;
        foreach ($this->beacons as $beacon) {
            if ($beacon->Y == $y && $range['start'] <= $beacon->X && $beacon->X <= $range['end']) {
                $count++;
            }
        }
        return $count;
    }

    private function readInput()
    {
        $handle = fopen(self::DATA_PATH, 'r');

        $regex = '/(-?\d+).*?(-?\d+).*?(-?\d+).*?(-?\d+)/';
        while (($line = fgets($handle)) !== false) {
            preg_match($regex, trim($line), $matches);
            $distance = abs($matches[1] - $matches[3]) + abs($matches[2] - $matches[4]);
            $this->sensors[] = new Position($matches[1], $matches[2], $distance);
            $this->beacons[] = new Position($matches[3], $matches[4]);
        }

        $this->beacons = array_unique($this->beacons);
    }

    // 0 when at max range. Positive if in range, negative otherwise. 
    private function getDistanceToMaxRange(Position $sensor, int $y): int
    {
        return $sensor->value - abs($sensor->Y - $y);
    }

    private function getSpaceBetweenSensors(Position $s1, Position $s2): int
    {
        return abs($s1->X - $s2->X) + abs($s1->Y - $s2->Y) - $s1->value - $s2->value;
    }
}
