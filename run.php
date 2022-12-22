<?php

use App\Solvers\Day20_mixing;
use App\Solvers\Day21_monkeys;
use App\Solvers\Day22_map;

require 'classLoader.php';

// $start = hrtime(true);
$solver = new Day22_map;
$solver->partOne();

// $end = hrtime(true);
// echo "time taken : " . ($end - $start)/1e6 . "ms\n";