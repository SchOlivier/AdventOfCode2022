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

    public function inspectNextItem(false|int $commonMultiple): int|false
    {
        if (empty($this->items)) return false;

        $this->nbItemsInspected++;
        $item = $this->items[0];
        $item = $this->inspect($item);
        $item = $this->getBored($item, $commonMultiple);
        $this->items[0] = $item;
        return $this->testItem($item) ? $this->sendToIfTrue : $this->sendToIfFalse;
    }

    private function inspect($item): int
    {
        $new = 0;
        eval('$new = ' . $this->leftOperand . ' ' . $this->operation . ' ' . $this->rightOperand . ';');
        return $new;
    }

    private function getBored(int $item, false|int $commonMultiple): int
    {
        if(!$commonMultiple) return floor($item / 3);
        return $item % $commonMultiple;
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
