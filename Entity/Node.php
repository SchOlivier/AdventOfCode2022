<?php

namespace App\Entity;

class Node
{
    public array $adjacent;

    public function __construct(
        public Position $position,
        public bool $visited = false,
        public int $distance = PHP_INT_MAX,
    ) {
    }
}
