<?php

namespace App\Solvers;

use App\Entity\Monkey;

class Day11_Monkeys
{
    const DATA_PATH = __DIR__ . '/../assets/11-Monkeys.txt';

    private array $monkeys;
    private int $commonMultiple;

    public function getTwoMostActiveMonkeys()
    {
        $nbMoved = array_map(function (Monkey $monkey) {
            return $monkey->nbItemsInspected;
        }, $this->monkeys);

        rsort($nbMoved);
        return $nbMoved[0] * $nbMoved[1];
    }

    public function playNRounds($n, $isGetBored = true)
    {
        $this->monkeys = $this->readInput();

        $this->commonMultiple = $isGetBored ? false : array_product(array_map(
            function (Monkey $monkey) {
                return $monkey->divisibleBy;
            },
            $this->monkeys
        ));
        while ($n > 0) {
            $n--;
            $this->playARound();
        }
    }

    private function playARound(): void
    {
        foreach ($this->monkeys as $i => $monkey) {
            $this->playATurn($monkey);
        }
    }

    private function playATurn(Monkey $monkey)
    {
        while (($toMonkey = $monkey->inspectNextItem($this->commonMultiple)) !== false) {
            $item = $monkey->throwItem();
            $this->monkeys[$toMonkey]->catchItem($item);
        }
    }

    private function readInput(): array
    {
        $monkeys = [];

        $handle = fopen(self::DATA_PATH, 'r');
        while (($line = fgets($handle)) !== false) {
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
