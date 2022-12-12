<?php

namespace App\Entity;

class Node
{
    public array $adjacent;

    public function __construct(
        public bool $visited = false,
        public int $distance = PHP_INT_MAX,
    ) {
    }
}
