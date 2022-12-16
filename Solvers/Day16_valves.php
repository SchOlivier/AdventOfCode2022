<?php

namespace App\Solvers;

use App\Entity\Position;
use App\Entity\Valve;

class Day16_valves
{
    const DATA_PATH = __DIR__ . '/../assets/16-valves';

    private array $valves;
    private array $closedValves = [];

    private array $cache;




    public function partOne(): int
    {
        $this->readInput();
        $this->cache = [];
        print_r($this->valves);
        print_r($this->closedValves);

        return $this->nextTimeStep('AA', $this->closedValves, 30, $this->getCacheKey('AA', $this->closedValves, 30));
    }

    private function nextTimeStep(string $valve, array $closedValves, int $remainingTime, string $cacheKey)
    {
        // echo $cacheKey . "\n";
        if ($remainingTime == 0 || empty($closedValves)) return 0;

        $pressures = [];
        $remainingTime--;
        $localPressure = 0;

        // echo "Valve pressure : " . $this->valves[$valve]['value'] . "\n";
        // echo "Valve is " . (isset($closedValves[$valve]) ? "open\n" : "closed\n");
        // $a = readline("continue ?");
        // if ($a == 'n') die();

        //Open valve and go to next valves
        if ($this->valves[$valve]['value'] > 0 && isset($closedValves[$valve])) {
            // die();
            $closedValves;
            unset($closedValves[$valve]);
            $localPressure = $this->valves[$valve]['value'] * $remainingTime;

            $cacheKey = $this->getCacheKey($valve, $closedValves, $remainingTime);
            if (!isset($this->cache[$cacheKey])) {
                $this->cache[$cacheKey] = $this->nextTimeStep($valve, $closedValves, $remainingTime, $cacheKey);
            }
            $pressures[] = $localPressure + $this->cache[$cacheKey];
            // $pressures[] = $pressure + $this->nextStep($valve, $newClosedValves, $remainingTime, $cacheKey);
        }

        // go to next valves
        foreach ($this->valves[$valve]['neighbours'] as $nextValve) {

            $cacheKey = $this->getCacheKey($nextValve, $closedValves, $remainingTime);
            if (!isset($this->cache[$cacheKey])) {
                $this->cache[$cacheKey] = $this->nextTimeStep($nextValve, $closedValves, $remainingTime, $cacheKey);
            }

            $pressures[] = $this->cache[$cacheKey];
            // $pressures[] = $this->nextStep($nextValve, $closedValves, $remainingTime, $cacheKey);
        }

        return max($pressures);
    }

    private function getCacheKey(string $valve, array $closedValves, int $remainingTime): string
    {
        return $remainingTime . $valve . implode('', $closedValves);
    }




    private function readInput()
    {
        $handle = fopen(self::DATA_PATH, 'r');

        $regex = '/Valve (.*) has flow rate=(.*); tunnels? leads? to valves? (.*)/';
        while (($line = fgets($handle)) !== false) {
            preg_match($regex, trim($line), $matches);
            $name = $matches[1];
            $value = (int)$matches[2];
            if ($value > 0) $closedValves[] = $name;
            $neighbours = explode(', ', $matches[3]);
            $this->valves[$name] = ['value' => $value, 'neighbours' => $neighbours];
        }
        $this->closedValves = array_flip($closedValves);
    }
}
