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

require 'classLoader.php';

$solver = new Day12_Graph;
displayResult($solver->findShortestPathWithFixedStartPoint());
displayResult($solver->findShortestPathFromLowestElevation());



function displayResult($result)
{
    echo "Result : $result\n";
}
