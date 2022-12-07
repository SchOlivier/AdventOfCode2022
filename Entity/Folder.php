<?php

namespace App\Entity;

class Folder
{
    private ?int $folderSize = null;

    public function __construct(
        public string $name,
        public Folder|null $parent = null,
        public array $children = [],
        public array $files = []
    ) {
    }

    public function getFolderSize(): int
    {
        return $this->folderSize ?? $this->calculateFolderSize();
    }

    private function calculateFolderSize(): int
    {
        $folderSize = 0;
        foreach($this->files as $size){
            $folderSize += $size;
        }
        foreach ($this->children as $child) {
            $folderSize += $child->getFolderSize();
        }
        $this->folderSize = $folderSize;
        return $this->folderSize;
    }
}
