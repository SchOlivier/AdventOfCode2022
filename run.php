<?php

use App\Solvers\Day20_mixing;
use App\Solvers\Day21_monkeys;

require 'classLoader.php';

// $start = hrtime(true);
$solver = new Day21_monkeys;
// echo "Quality sum : " . $solver->partOne(24) . "\n";
$solver->partOne()  ;
$solver->partTwo()  ;

// $end = hrtime(true);
// echo "time taken : " . ($end - $start)/1e6 . "ms\n";