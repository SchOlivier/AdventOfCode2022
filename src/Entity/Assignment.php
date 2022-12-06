<?php

namespace App\Entity;

class Assignment {
    public function __construct(
        public int $start,
        public int $end
    ) {}
}