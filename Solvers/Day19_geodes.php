<?php

namespace App\Solvers;

class Day19_geodes
{

    const FILE = __DIR__ . '/../assets/19-geodes';
    const BOTS = ['geode', 'obsidian', 'clay', 'ore'];

    public array $botCosts;
    public int $maxOrePerMinute = 0;



    public function partOne(int $duration): int
    {
        $file = fopen(self::FILE, 'r');

        $answer = 0;

        while (($blueprint = fgets($file)) !== false) {
            $i = $this->parseBlueprint($blueprint);
            $geodes = $this->mineGeodes($duration);

            echo "Blueprint $i : best I can do is $geodes geodes\n";

            $answer += $i * $geodes;
        }


        return $answer;
    }

    public function partTwo(int $duration): int
    {
        $file = fopen(self::FILE, 'r');

        $answer = 1;

        while (($blueprint = fgets($file)) !== false) {
            $i = $this->parseBlueprint($blueprint);
            $geodes = $this->mineGeodes($duration);
            echo "Blueprint $i : best I can do is $geodes geodes\n";
            $answer *= $geodes;
            if ($i == 3) break;
        }


        return $answer;
    }

    private function parseBlueprint(string $blueprint): int
    {
        //(on a single line:)
        //Blueprint 1: Each ore robot costs 4 ore. Each clay robot costs 2 ore.
        //Each obsidian robot costs 3 ore and 14 clay. Each geode robot costs 2 ore and 7 obsidian.
        $regex = '/Blueprint (\d+).*ore robot costs (\d+).*clay robot costs (\d+).*obsidian robot costs (\d+) ore and (\d+).*geode robot costs (\d+) ore and (\d+)/';
        preg_match($regex, $blueprint, $matches);

        $i = (int)$matches[1];

        $this->botCosts = [
            'ore' => ['ore' => (int) $matches[2]],
            'clay' => ['ore' => (int) $matches[3]],
            'obsidian' => ['ore' => (int) $matches[4], 'clay' => (int) $matches[5]],
            'geode' => ['ore' => (int) $matches[6], 'obsidian' => (int) $matches[7]]
        ];

        $this->maxOrePerMinute = max($matches[2], $matches[3], $matches[4], $matches[6]);

        return $i;
    }

    private function mineGeodes(int $duration): int
    {
        $maxGeodes = 0;

        $state = [
            'time' => 0,
            'stocks' => [
                'ore' => 0,
                'clay' => 0,
                'obsidian' => 0,
                'geode' => 0
            ],
            'bots' => [
                'ore' => 1,
                'clay' => 0,
                'obsidian' => 0,
                'geode' => 0
            ],
            'dontBuild' => []
        ];

        $queue = [$state];
        $maxGeodes = 0;
        $seen = [];
        while (!empty($queue)) {
            $currentState = array_shift($queue);
            $key = $currentState['time'] . ',' . implode(',', $currentState['bots']);
            $maxGeodes = max($maxGeodes, $currentState['stocks']['geode']);

            if ($currentState['time'] == $duration || $currentState['stocks']['geode'] + 1 < $maxGeodes) {
                continue;
            }
            if (!$this->isStateWorthIt($currentState, $seen)) continue;

            $seen[$key][] = $currentState['stocks'];
            foreach ($this->getNextStates($currentState) as $nextState) {
                $queue[] = $nextState;
            }
        }

        return $maxGeodes;
    }

    private function isStateWorthIt(array $state, array $seen): bool
    {
        $key = $state['time'] . ',' . implode(',', $state['bots']);
        if (!isset($seen[$key])) return true;

        $stocks = $state['stocks'];

        foreach ($seen[$key] as $seenStocks) {
            if (
                $stocks['ore'] <= $seenStocks['ore']
                && $stocks['clay'] <= $seenStocks['clay']
                && $stocks['obsidian'] <= $seenStocks['obsidian']
                && $stocks['geode'] <= $seenStocks['geode']
            ) return false;
        }
        return true;
    }

    private function getNextStates(array $currentState)
    {
        $nextStates = [];
        $currentState['time']++;

        // I will assume that building the geode bot each time it is possible is the optimal solution.
        if ($this->canBuildBot('geode', $currentState['stocks'])) {
            $nextState = $currentState;
            $nextState['stocks'] = $this->buildBot('geode', $nextState['stocks']);
            $nextState['stocks'] = $this->gatherStuff($nextState['bots'], $nextState['stocks']);
            $nextState['bots']['geode']++;
            $nextState['dontBuild'] = [];
            $nextStates[] = $nextState;
            return $nextStates;
        }

        $doNothingState = $currentState;
        $doNothingState['stocks'] = $this->gatherStuff($doNothingState['bots'], $doNothingState['stocks']);
        $doNothingState['dontBuild'] = []; // If we decide to skip building a bot, we don't ever build it until we've built something else

        if ($this->canBuildBot('obsidian', $currentState['stocks'])) {
            if (!in_array('obsidian', $currentState['dontBuild'])) {
                $nextState = $currentState;
                $nextState['stocks'] = $this->buildBot('obsidian', $nextState['stocks']);
                $nextState['stocks'] = $this->gatherStuff($nextState['bots'], $nextState['stocks']);
                $nextState['bots']['obsidian']++;
                $nextState['dontBuild'] = [];
                $nextStates[] = $nextState;
            }
            $doNothingState['dontBuild'][] = ['obsidian'];
        }

        //clay if current production doen't exceed what's needed to build one obs bot per minute
        if (
            $this->canBuildBot('clay', $currentState['stocks'])
            && $currentState['bots']['clay'] < $this->botCosts['obsidian']['clay']
        ) {
            if (!in_array('clay', $currentState['dontBuild'])) {

                $nextState = $currentState;
                $nextState['stocks'] = $this->buildBot('clay', $nextState['stocks']);
                $nextState['stocks'] = $this->gatherStuff($nextState['bots'], $nextState['stocks']);
                $nextState['bots']['clay']++;
                $nextState['dontBuild'] = [];
                $nextStates[] = $nextState;
            }

            $doNothingState['dontBuild'][] = ['clay'];
        }

        //ore if current production doen't exceed what's needed to build every bot
        if (
            $this->canBuildBot('ore', $currentState['stocks'])
            && $currentState['bots']['ore'] < $this->maxOrePerMinute
        ) {
            if (!in_array('ore', $currentState['dontBuild'])) {
                $nextState = $currentState;
                $nextState['stocks'] = $this->buildBot('ore', $nextState['stocks']);
                $nextState['stocks'] = $this->gatherStuff($nextState['bots'], $nextState['stocks']);
                $nextState['bots']['ore']++;
                $nextState['dontBuild'] = [];
                $nextStates[] = $nextState;
            }
            $doNothingState['dontBuild'][] = ['ore'];
        }
        $nextStates[] = $doNothingState;

        return $nextStates;
    }

    private function buildBot(string $newBot, array $stocks): array
    {
        foreach ($this->botCosts[$newBot] as $material => $cost) {
            $stocks[$material] -= $cost;
        }
        return $stocks;
    }

    private function gatherStuff(array $bots, array $stocks): array
    {
        foreach ($bots as $material => $number) {
            $stocks[$material] += $number;
        }
        return $stocks;
    }

    private function canBuildBot(string $bot, array $stocks): bool
    {
        foreach ($this->botCosts[$bot] as $material => $cost) {
            if ($stocks[$material] - $cost < 0) return false;
        }
        return true;
    }
}
