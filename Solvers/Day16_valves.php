<?php

namespace App\Solvers;

use App\Entity\Position;
use App\Entity\Valve;

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

    // public function partTwo(): int
    // {
    //     $this->cache = [];
    //     $this->distances = $this->getDistanceBetweenUsefulValves();
    //     $seenValves = ['AA'];
    //     return $this->nextValve('AA', 30, $seenValves, 0);
    // }


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
            $remainingTime--;
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

    private function getDistanceBetweenUsefulValves()
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

            if (in_array($v2, $this->valves[$currentValve]['neighbours'])) return $currentDistance + 1;

            foreach ($this->valves[$currentValve]['neighbours'] as $n) {
                if (!in_array($n, $seen)) $queue[] = ['name' => $n, 'distance' => $currentDistance + 1];
            }
        }

        return -1;
    }
}
