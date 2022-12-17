<?php

namespace App\Solvers;

use App\Entity\Position;
use App\Entity\Valve;
use Generator;

class Day16_valves
{
    const DATA_PATH = __DIR__ . '/../assets/16-valves';

    private array $valves;
    private array $usefulValves;
    private array $distances;

    private array $cache;

    public function __construct()
    {
        $this->readInput();
    }

    public function partOne(): int
    {
        $this->cache = [];
        return $this->nextTimeStep('AA', $this->usefulValves, 30, $this->getCacheKey('AA', $this->usefulValves, 30));
    }

    public function partOneTakeTwo(): int
    {
        $this->cache = [];
        $this->distances = $this->getDistanceBetweenUsefulValves();
        $seenValves = ['AA'];
        return $this->nextValve('AA', 30, $seenValves, 0);
    }

    private function nextTimeStep(string $valve, array $usefulValves, int $remainingTime, string $cacheKey)
    {
        // echo $cacheKey . "\n";
        if ($remainingTime == 0 || empty($usefulValves)) return 0;

        $pressures = [];
        $remainingTime--;
        $localPressure = 0;

        // echo "Valve pressure : " . $this->valves[$valve]['value'] . "\n";
        // echo "Valve is " . (isset($usefulValves[$valve]) ? "open\n" : "closed\n");
        // $a = readline("continue ?");
        // if ($a == 'n') die();

        //Open valve and go to next valves
        if ($this->valves[$valve]['value'] > 0 && isset($usefulValves[$valve])) {
            // die();
            $usefulValves;
            unset($usefulValves[$valve]);
            $localPressure = $this->valves[$valve]['value'] * $remainingTime;

            $cacheKey = $this->getCacheKey($valve, $usefulValves, $remainingTime);
            if (!isset($this->cache[$cacheKey])) {
                $this->cache[$cacheKey] = $this->nextTimeStep($valve, $usefulValves, $remainingTime, $cacheKey);
            }
            $pressures[] = $localPressure + $this->cache[$cacheKey];
            // $pressures[] = $pressure + $this->nextStep($valve, $newUsefulValves, $remainingTime, $cacheKey);
        }

        // go to next valves
        foreach ($this->valves[$valve]['neighbours'] as $nextValve) {

            $cacheKey = $this->getCacheKey($nextValve, $usefulValves, $remainingTime);
            if (!isset($this->cache[$cacheKey])) {
                $this->cache[$cacheKey] = $this->nextTimeStep($nextValve, $usefulValves, $remainingTime, $cacheKey);
            }

            $pressures[] = $this->cache[$cacheKey];
            // $pressures[] = $this->nextStep($nextValve, $usefulValves, $remainingTime, $cacheKey);
        }

        return max($pressures);
    }

    private function nextValve(string $valve, int $remainingTime, array $seenValves)
    {
        $pressure = 0;
        if (!in_array($valve, $seenValves)) {
            $pressure += $this->valves[$valve]['value'] * $remainingTime;
            $seenValves[] = $valve;
            sort($seenValves);
        }
        $pressures = [0];
        foreach ($this->distances[$valve] as $nextValve => $distance) {
            if (($remainingTime - $distance - 1) > 0) {
                $cacheKey = $this->getCacheKeyTake2($nextValve, $seenValves, $remainingTime - $distance);
                if (!isset($this->cache[$cacheKey])) {
                    $this->cache[$cacheKey] = $this->nextValve($nextValve, $remainingTime - $distance, $seenValves);
                }

                $pressures[] = $pressure +  $this->cache[$cacheKey];
            }
        }

        return max($pressures);
    }

    private function getCacheKey(string $valve, array $usefulValves, int $remainingTime): string
    {
        return $remainingTime . $valve . implode('', $usefulValves);
    }

    private function getCacheKeyTake2(string $valve, array $seenValves, int $remainingTime)
    {
        return $remainingTime . $valve . implode('', $seenValves);
    }

    private function readInput()
    {
        $handle = fopen(self::DATA_PATH, 'r');

        $regex = '/Valve (.*) has flow rate=(.*); tunnels? leads? to valves? (.*)/';
        while (($line = fgets($handle)) !== false) {
            preg_match($regex, trim($line), $matches);
            $name = $matches[1];
            $value = (int)$matches[2];
            if ($value > 0) $usefulValves[] = $name;
            $neighbours = explode(', ', $matches[3]);
            $this->valves[$name] = ['value' => $value, 'neighbours' => $neighbours];
        }
        $this->usefulValves = array_flip($usefulValves);
    }

    private function getDistanceBetweenUsefulValves(): array
    {
        $valves = array_flip($this->usefulValves);
        $valves[] = 'AA';
        $distances = [];
        for ($i = 0; $i < count($valves); $i++) {
            for ($j = $i + 1; $j < count($valves); $j++) {
                $distance = $this->getDistanceBetweenValves($valves[$i], $valves[$j]);
                $distances[$valves[$i]][$valves[$j]] = $distance;
                $distances[$valves[$j]][$valves[$i]] = $distance;
            }
        }
        return $distances;
    }

    private function getDistanceBetweenValves(string $v1, string $v2): int
    {
        $seen = [];
        $queue = [['name' => $v1, 'distance' => 0]];

        while (!empty($queue)) {
            $v = array_shift($queue);
            $currentValve = $v['name'];
            $currentDistance = $v['distance'];

            $seen[] = $currentValve;

            if (in_array($v2, $this->valves[$currentValve]['neighbours'])) return $currentDistance + 2;

            foreach ($this->valves[$currentValve]['neighbours'] as $n) {
                if (!in_array($n, $seen)) $queue[] = ['name' => $n, 'distance' => $currentDistance + 1];
            }
        }

        return -1;
    }

    public function partTwo(): int
    {
        $maxPressure = 0;

        $valves = $this->getDistanceBetweenUsefulValves();
        $this->displayDistances($valves);

        foreach ($this->getAllPath($valves) as $i => $path) {

            $seenValves = array_flip($path['seen']);
            unset($seenValves['AA']);
            $remainingValves = $this->removeValvesFromDistances($valves, $seenValves);
            $remainingPath = $this->getBestRemainingPath($remainingValves);

            if($path['pressure'] + $remainingPath['pressure'] > $maxPressure){
                $maxPressure = $path['pressure'] + $remainingPath['pressure'];
                echo "------------------\n";
                echo "-New best path : -\n";
                echo "------------------\n";
                echo "Path 1 : [" . implode(", ", $path['seen']) . "]\n";
                echo "Path 2 : [" . implode(", ", $remainingPath['seen']) . "]\n";
                echo "Pressure : $maxPressure\n";
            }

            if ((int)$i % 1000 ==  0) {
                echo "\n--- $i ---\n";
                echo "\tPressure : " . $path['pressure'] . "\n";
                echo "\t[" . implode(", ", $path['seen']) . "]\n";
                echo "RemainingPath :\n";
                echo "\tPressure : " . $remainingPath['pressure'] . "\n";
                echo "\t[" . implode(", ", $remainingPath['seen']) . "]\n";
            }

        }

        return $maxPressure;
    }

    private function displayDistances(array $distances): void
    {
        $allValves = array_keys($distances);

        echo "\n   ";
        foreach ($allValves as $v) {
            echo $v . " ";
        }
        echo "\n";

        for ($i = 0; $i < count($allValves); $i++) {
            echo $allValves[$i] . " ";
            for ($j = 0; $j < count($allValves); $j++) {
                if ($i <= $j) {
                    echo "|  ";
                } else {
                    echo str_pad($distances[$allValves[$i]][$allValves[$j]], 3);
                }
            }
            echo "\n";
        }
    }

    private function getAllPath(array $valves): Generator
    {
        $stack = [['valve' => 'AA', 'time' => 26, 'seen' => [], 'pressure' => 0]];
        while (!empty($stack)) {
            $current = array_pop($stack);
            $current['pressure'] += $this->valves[$current['valve']]['value'] * $current['time'];
            $current['seen'][] = $current['valve'];
            $nexts = $this->getNextValves($valves, $current);
            if (empty($nexts)) {
                yield $current;
            } else {
                foreach ($nexts as $next) {
                    $stack[] = $next;
                }
            }
        }
    }

    private function getBestRemainingPath(array $valves): array
    {
        $bestPath = ['valve' => 'AA', 'time' => 26, 'seen' => [], 'pressure' => 0];
        $stack = [$bestPath];

        while (!empty($stack)) {
            $current = array_pop($stack);
            $current['pressure'] += $this->valves[$current['valve']]['value'] * $current['time'];
            $current['seen'][] = $current['valve'];
            $nexts = $this->getNextValves($valves, $current);
            if (empty($nexts) && $bestPath['pressure'] < $current['pressure']) {
                $bestPath = $current;
            } else {
                foreach ($nexts as $next) {
                    $stack[] = $next;
                }
            }
        }

        return $bestPath;
    }

    private function getNextValves(array $valves, array $current): array
    {
        $nexts = [];

        foreach ($valves[$current['valve']] as $to => $distance) {
            if (!in_array($to, $current['seen']) && $current['time'] - $distance > 0) {
                $nexts[] = [
                    'valve' => $to,
                    'time' => $current['time'] - $distance,
                    'seen' => $current['seen'],
                    'pressure' => $current['pressure']
                ];
            }
        }

        return $nexts;
    }

    private function removeValvesFromDistances(array $distances, array $valves)
    {
        $distances = array_diff_key($distances, $valves);
        foreach ($distances as &$tos) {
            $tos = array_diff_key($tos, $valves);
        }
        return $distances;
    }
}
