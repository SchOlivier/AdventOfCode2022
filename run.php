<?php

use App\Entity\Monkey;
use App\Solvers\Day01_Calories;
use App\Solvers\Day02_RockPaperScissors;
use App\Solvers\Day03_Rucksack;
use App\Solvers\Day04_CleanupPairs;
use App\Solvers\Day05_Stacks;
use App\Solvers\Day06_Datastream;
use App\Solvers\Day07_FolderParser;
use App\Solvers\Day07_WhoNeedsTrees;
use App\Solvers\Day08_TreeHouse;
use App\Solvers\Day09_RopeMovement;
use App\Solvers\Day10_CRT;
use App\Solvers\Day11_Monkeys;
use App\Solvers\Day12_Graph;
use App\Solvers\Day13_distressSignal;
use App\Solvers\Day14_sand;
use App\Solvers\Day15_Beacons;

require 'classLoader.php';

$solver = new Day15_Beacons;
displayResult($solver->partOne(2000000, 4000000));

$start = hrtime(true);
displayResult($solver->partTwo(4000000)); // 1st try : 16.4s
$end = hrtime(true);
$time = ($end - $start) / 1e6; // ms
echo "\nTime taken : {$time}ms.\n";

$start = hrtime(true);
displayResult($solver->partTwoTake2(4000000)); // 2nd try 0.17ms \o/
$end = hrtime(true);
$time = ($end - $start) / 1e6; // ms
echo "\nTime taken : {$time}ms.\n";



function displayResult($result)
{
    echo "Result : $result\n";
}
