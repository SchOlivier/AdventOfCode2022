<?php

namespace App\Solvers;

use App\Entity\Monkey;

class Day11_Monkeys
{
    const DATA_PATH = __DIR__ . '/../assets/11-Monkeys.txt';

    private array $monkeys;

    public function __construct()
    {
        $this->monkeys = $this->readInput();
    }

    public function getTwoMostActiveMonkeys()
    {
        $nbMoved = array_map(function (Monkey $monkey){
            return $monkey->nbItemsInspected;
        }, $this->monkeys);

        rsort($nbMoved);
        return $nbMoved[0] * $nbMoved[1];
    }

    public function playNRounds($n)
    {
        while ($n > 0) {
            $n--;
            $this->playARound();
        }
    }

    private function playARound(): void
    {
        foreach ($this->monkeys as $i => $monkey) {
            // echo "\n\n Tour du singe $i\n";
            $this->playATurn($monkey);
        }
    }

    private function playATurn(Monkey $monkey)
    {
        while (($toMonkey = $monkey->inspectNextItem()) !== false) {
            $item = $monkey->throwItem();
            // echo "\t Il a désormais une valeur de $item\n";
            // echo "\t Je l'envoie au singe $toMonkey\n";
            $this->monkeys[$toMonkey]->catchItem($item);
            // echo "\n";
        }
    }

    private function readInput(): array
    {
        $monkeys = [];

        $handle = fopen(self::DATA_PATH, 'r');
        while (($line = fgets($handle))) {
            $monkeys[] = $this->getMonkeyFromInput($handle);
        }
        return $monkeys;
    }

    private function getMonkeyFromInput(mixed $handle): Monkey
    {
        $monkey = new Monkey;

        //items
        $line = trim(fgets($handle));
        $monkey->items = explode(", ", substr($line, strlen('Starting items: ')));

        //Operation
        $line = trim(fgets($handle));
        list($leftOperand, $operation, $rightOperand) = explode(" ", substr($line, strlen('Operation: new = ')));
        $monkey->leftOperand = 'old' == $leftOperand ? Monkey::OLD : $leftOperand;
        $monkey->rightOperand = 'old' == $rightOperand ? Monkey::OLD : $rightOperand;
        $monkey->operation = $operation;

        //test
        $line = trim(fgets($handle));
        $monkey->divisibleBy = (int)substr($line, strlen('Test: divisible by '));

        //if True
        $line = trim(fgets($handle));
        $monkey->sendToIfTrue = (int)substr($line, strlen('If true: throw to monkey '));

        //if False
        $line = trim(fgets($handle));
        $monkey->sendToIfFalse = (int)substr($line, strlen('If false: throw to monkey '));

        //Empty line
        fgets($handle);

        return $monkey;
    }
}
