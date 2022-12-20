<?php

namespace App\Solvers;

class Day20_mixing
{
    const FILE = 'assets/20-mixing';

    public function partOne()
    {
        $file = fopen(self::FILE, 'r');
        $initial = [];
        while (($n = fgets($file)) !== false) {
            $initial[] = (int)trim($n);
        }
        $n = count($initial);
        $indexes = range(0, $n - 1);

        $indexes = $this->mix($initial, $indexes);

        foreach ($indexes as $key => $value) {
            $mixed[$value] = $initial[$key];
        }

        $zeroIndex = array_search(0, $initial);
        echo "new zeroIndex : " . $indexes[$zeroIndex] . "\n";
        $n1000 = ($indexes[$zeroIndex] + 1000) % $n;
        $n2000 = ($indexes[$zeroIndex] + 2000) % $n;
        $n3000 = ($indexes[$zeroIndex] + 3000) % $n;
        echo $mixed[$n1000] + $mixed[$n2000] + $mixed[$n3000] . " !!\n";
    }


    public function mix($initial, $indexes): array
    {
        $n = count($initial);


        for ($i = 0; $i < $n; $i++) {
            $number = $initial[$i];
            if ($number == 0) {
                continue;
            }

            $start = $indexes[$i];
            $end = ($start + $number) % ($n - 1);
            if ($end < 0) $end += $n - 1;

            if ($start < $end) {
                $indexes = array_map(
                    function ($x) use ($start, $end) {
                        return ($x >= $start && $x <= $end) ? $x - 1 : $x;
                    },
                    $indexes
                );
                $indexes[$i] = $end;
            } else {
                $indexes = array_map(
                    function ($x) use ($start, $end) {
                        return ($x >= $end && $x <= $start) ? $x + 1 : $x;
                    },
                    $indexes
                );
                $indexes[$i] = $end;
            }
        }

        return $indexes;
    }

    public function partTwo()
    {
        $file = fopen(self::FILE, 'r');
        $initial = [];
        while (($n = fgets($file)) !== false) {
            $initial[] = (int)trim($n) * 811589153;
        }
        $n = count($initial);
        $indexes = range(0, $n - 1);

        for ($i = 0; $i < 10; $i++) {
            $indexes = $this->mix($initial, $indexes);
        }

        foreach ($indexes as $key => $value) {
            $mixed[$value] = $initial[$key];
        }

        $zeroIndex = array_search(0, $initial);
        echo "new zeroIndex : " . $indexes[$zeroIndex] . "\n";
        $n1000 = ($indexes[$zeroIndex] + 1000) % $n;
        $n2000 = ($indexes[$zeroIndex] + 2000) % $n;
        $n3000 = ($indexes[$zeroIndex] + 3000) % $n;
        echo $mixed[$n1000] + $mixed[$n2000] + $mixed[$n3000] . " !!\n";
    }
}
