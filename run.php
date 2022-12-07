<?php

use App\Solvers\Day01_Calories;
use App\Solvers\Day02_RockPaperScissors;
use App\Solvers\Day03_Rucksack;
use App\Solvers\Day04_CleanupPairs;
use App\Solvers\Day05_Stacks;
use App\Solvers\Day06_Datastream;

require 'classLoader.php';

// $solver = new Day01_Calories;
// displayResult($solver->findMaxCalories());
// displayResult($solver->findMaxCaloriesFromTopThreeElves());

// $solver = new Day02_RockPaperScissors;
// displayResult($solver->getScoreWithDeterminedChoices());
// displayResult($solver->getScoreWithDeterminedResult());

// $solver = new Day03_Rucksack;
// displayResult($solver->getTotalPriorityOfItemsInBothCompartments());
// displayResult($solver->getTotalPriorityInGroupsOfThree());

// $solver = new Day04_CleanupPairs;
// displayResult($solver->countFullyContainedAssignments());
// displayResult($solver->countOverlappingAssignments());

// $solver = new Day05_Stacks;
// displayResult($solver->getTopCratesAfterRearrangement9000());
// displayResult($solver->getTopCratesAfterRearrangement9001());

// $solver = new Day06_Datastream;
// displayResult($solver->getIndexOfStartOfPacket());
// displayResult($solver->getIndexOfStartOfMessage());

function displayResult($result){
    echo "\nResult : $result\n";
}