<?php

namespace App\Solvers;

class Day02_RockPaperScissors
{

    const DATA_PATH = __DIR__ . '/../assets/02-RockPaperScissors.txt';

    public function getScoreWithDeterminedChoices()
    {
        $handle = fopen(self::DATA_PATH, 'r');

        $matchScores = $this->initMatchScoresWithDeterminedChoices();
        $score = 0;
        while (($line = fgets($handle)) !== false) {
            $line = trim($line);
            $score += $matchScores[$line];
        }
        return $score;
    }

    public function getScoreWithDeterminedResult()
    {
        $handle = fopen(self::DATA_PATH, 'r');

        $matchScores = $this->initMatchScoresWithDeterminedResult();
        $score = 0;
        while (($line = fgets($handle)) !== false) {
            $line = trim($line);
            $score += $matchScores[$line];
        }
        return $score;
    }

    /**
     * A,B,C : opponent choice
     * X,Y,Z : your choice
     * 
     * Score is based on choice + result of match.
     * 
     * Choice value :
     * rock = 1
     * paper = 2
     * scissors = 3
     * 
     * Result of match value:
     * Loss : 0
     * Draw : 3
     * Win : 6
     * 
     * With
     * A = X = rock
     * B = Y = paper
     * C = Z = scissors
     */
    private function initMatchScoresWithDeterminedChoices()
    {
        return [
            'A X' => 4,
            'B X' => 1,
            'C X' => 7,
            'A Y' => 8,
            'B Y' => 5,
            'C Y' => 2,
            'A Z' => 3,
            'B Z' => 9,
            'C Z' => 6
        ];
    }

    /**
     * A,B,C : opponent choice, (rock, paper, scissor respectively)
     * X,Y,Z : the result of the game (lose, draw, win respectively)
     * 
     * Score is based on your choice + result of match.
     * 
     * Choice value :
     * rock = 1
     * paper = 2
     * scissors = 3
     * 
     * Result of match value:
     * Loss : 0
     * Draw : 3
     * Win : 6
     */
    private function initMatchScoresWithDeterminedResult()
    {
        return [
            'A X' => 3,
            'B X' => 1,
            'C X' => 2,
            'A Y' => 4,
            'B Y' => 5,
            'C Y' => 6,
            'A Z' => 8,
            'B Z' => 9,
            'C Z' => 7
        ];
    }
}
