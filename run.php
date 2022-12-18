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
use App\Solvers\Day16_valves;
use App\Solvers\Day17_tetris;
use App\Solvers\Day18_lava;

require 'classLoader.php';

$start = hrtime(true);
$solver = new Day18_lava;
echo "real surface area : " . $solver->partTwo() . "\n";

$end = hrtime(true);
echo "time taken : " . ($end - $start)/1e6 . "ms\n";