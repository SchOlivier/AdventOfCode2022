<?php

namespace App\Entity;

class Monkey
{
    const OLD = '$item';

    public array $items;
    public string $leftOperand;
    public string $rightOperand;
    public string $operation;
    public int $divisibleBy;
    public int $sendToIfTrue;
    public int $sendToIfFalse;

    public int $nbItemsInspected = 0;

    public function inspectNextItem(): int|false
    {
        if (empty($this->items)) return false;

        // echo "\t J'inspecte l'item " . $this->items[0] . "\n"; 

        $this->nbItemsInspected++;
        $item = $this->items[0];
        // echo "J'inspecte un object de valeur $item\n";
        $item = $this->inspect($item);
        // echo "Nouvelle valeur : $item\n";
        $item = $this->getBored($item);
        // echo "I'm bored : $item\n";
        $this->items[0] = $item;
        return $this->testItem($item) ? $this->sendToIfTrue : $this->sendToIfFalse;
    }

    private function inspect($item): int
    {
        $new = 0;
        eval('$new = ' . $this->leftOperand . ' ' . $this->operation . ' ' . $this->rightOperand . ';');
        return $new;
    }

    private function getBored($item): int
    {
        return floor($item / 3);
    }

    public function testItem($item): bool
    {
        return $item % $this->divisibleBy == 0;
    }

    public function throwItem():int
    {
        return array_shift($this->items);
    }

    public function catchItem($item): void
    {
        $this->items[] = $item;
    }
}
