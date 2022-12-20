<?php

use App\Solvers\Day20_mixing;

require 'classLoader.php';

// $start = hrtime(true);
$solver = new Day20_mixing;
// echo "Quality sum : " . $solver->partOne(24) . "\n";
$solver->partOne()  ;
$solver->partTwo()  ;

// $end = hrtime(true);
// echo "time taken : " . ($end - $start)/1e6 . "ms\n";