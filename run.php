<?php

use App\Solvers\Day20_mixing;
use App\Solvers\Day21_monkeys;
use App\Solvers\Day22_map;
use App\Solvers\Day24_Blizzard;

require 'classLoader.php';

// $start = hrtime(true);
$solver = new Day24_Blizzard;
$solver->partOne();

// $end = hrtime(true);
// echo "time taken : " . ($end - $start)/1e6 . "ms\n";