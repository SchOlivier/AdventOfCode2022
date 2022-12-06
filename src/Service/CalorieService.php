<?php

namespace App\Service;

class CalorieService
{

    const DATA_PATH = __DIR__ . '/../../assets/01-ElvesAndCalories.txt';

    public function findMaxCalories()
    {
        $handle = fopen(self::DATA_PATH, 'r');

        $maxCalories = 0;
        $currentCalories = 0;
        while(($line = fgets($handle)) !== false){
            $line = trim($line);
            if($line == ''){
                $maxCalories = max($maxCalories, $currentCalories);
                $currentCalories = 0;
                continue;
            }
            $currentCalories += (int)$line;
        }
        return $maxCalories;
    }

    public function findMaxCaloriesFromTopThreeElves()
    {
        $handle = fopen(self::DATA_PATH, 'r');

        $topThreeMaxCalories = [0,0,0]; // Sorted from lowest to highest
        $currentCalories = 0;
        while(($line = fgets($handle)) !== false){
            $line = trim($line);
            if($line == ''){
                $topThreeMaxCalories[0] = max($topThreeMaxCalories[0], $currentCalories);
                sort($topThreeMaxCalories);
                $currentCalories = 0;
                continue;
            }
            $currentCalories += (int)$line;
        }
        return array_sum($topThreeMaxCalories);
    }
}
