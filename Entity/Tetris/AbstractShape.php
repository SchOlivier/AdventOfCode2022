<?php

namespace App\Entity\Tetris;

use App\Entity\Position;

abstract class AbstractShape {

    const RIGHT = self::RIGHT;
    const LEFT = self::LEFT;

    public array $shape;
    public int $height;
    public int $width;
    public Position $position;

    public function moveRight(): Position
    {
        $this->position->X++;
        return $this->position;
    }
    public function moveLeft(): Position
    {
        $this->position->X--;
        return $this->position;
    }
    public function moveDown(): Position|false
    {
        $this->position->Y--;
        return $this->position;
    }
    
    abstract public function getPositionsToCheck(string $direction): array;
    abstract public function getPositions(): array;
}