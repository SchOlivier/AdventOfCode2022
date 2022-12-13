<?php

namespace App\Solvers;

class Day13_distressSignal
{
    const DATA_PATH = __DIR__ . '/../assets/13-distressSignal';

    public function findCorrectPackets(): int
    {
        $handle = fopen(self::DATA_PATH, 'r');
        $i = 0;
        $sum = 0;
        while (($line = fgets($handle)) !== false) {
            $i++;
            $left = $right = [];
            eval("\$left = " . trim($line) . ";");
            eval("\$right = " . trim(fgets($handle)) . ";");
            fgets($handle); // empty line
            if ($this->compareArrays($left, $right) == -1) $sum += $i;
        }
        return $sum;
    }

    public function getDistressSignalKey(): int
    {
        $key1 = [[2]];
        $key2 = [[6]];

        $packets = [$key1, $key2];

        $handle = fopen(self::DATA_PATH, 'r');
        while (($line = fgets($handle)) !== false) {
            if (trim($line) == '') continue;

            eval("\$packets[] = " . $line . ";");
        }

        usort($packets, array($this, 'compareArrays'));

        return (array_search($key1, $packets) + 1) * (array_search($key2, $packets) + 1);
    }

    private function compareArrays(array $left, array $right): int
    {
        //Check for Empty Arrays
        $nbLeft = count($left);
        $nbRight = count($right);
        if ($nbLeft == 0) {
            if ($nbRight == 0) return 0;
            return -1;
        }
        if ($nbRight == 0) return +1;

        //Compare values
        for ($i = 0; $i < min($nbLeft, $nbRight); $i++) {
            if (is_array($right[$i]) || is_array($left[$i])) {
                $right[$i] = is_array($right[$i]) ? $right[$i] : [$right[$i]];
                $left[$i] = is_array($left[$i]) ? $left[$i] : [$left[$i]];
                $arrayComparison = $this->compareArrays($left[$i], $right[$i]);
                if ($arrayComparison != 0) return $arrayComparison;
            } else {
                if ($left[$i] != $right[$i]) return $left[$i] <=> $right[$i];
            }
        }

        //Compare sizes
        return $nbLeft <=> $nbRight;
    }
}
