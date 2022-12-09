<?php

namespace App\Solvers;

class Day01_Calories
{

    const DATA_PATH = __DIR__ . '/../assets/01-ElvesAndCalories.txt';

    public function findMaxCalories($nbElves = 1)
    {
        $handle = fopen(self::DATA_PATH, 'r');

        $topNMaxCalories = array_fill(0, $nbElves, 0); // Sorted from lowest to highest
        $currentCalories = 0;
        while(($line = fgets($handle)) !== false){
            $line = trim($line);
            if($line == ''){
                $topNMaxCalories[0] = max($topNMaxCalories[0], $currentCalories);
                sort($topNMaxCalories);
                $currentCalories = 0;
                continue;
            }
            $currentCalories += (int)$line;
        }
        return array_sum($topNMaxCalories);
    }
}
