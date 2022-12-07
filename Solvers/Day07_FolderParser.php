<?php

namespace App\Solvers;

use App\Entity\Folder;
use Exception;

class Day07_FolderParser
{

    const DATA_PATH = __DIR__ . '/../assets/07-DirNavigation.txt';
    const MAX_FOLDER_SIZE = 100000;
    const DISK_SPACE = 70000000;
    const NEEDED_SPACE = 30000000;
    const LS = 'ls';
    const CD = 'cd';
    const FOLDER = 'dir';

    private mixed $handle;

    public function getTotalSizeOfSmallFolders(): int
    {
        $root = $this->createFolderStructure();
        $smallFolders = $this->getSmallFolders($root);
        $totalSize = array_sum(array_map(function($array) {return $array->getFolderSize();}, $smallFolders));
        return $totalSize;
    }

    public function getSizeOfFolderToDelete():int
    {
        $root = $this->createFolderStructure();
        $usedSpace = $root->getFolderSize();
        $freeSpace = self::DISK_SPACE - $usedSpace;
        $spaceToDelete = self::NEEDED_SPACE - $freeSpace;
        $smallest = $this->findSmallestFolderBiggerThanSize($root, $root, $spaceToDelete);
        return $smallest->getFolderSize();
    }

    private function findSmallestFolderBiggerThanSize(Folder $root, Folder $currentSmallest, int $minSize):Folder
    {
        if($root->getFolderSize() >= $minSize){
            $currentSmallest = $root->getFolderSize() < $currentSmallest->getFolderSize() ? $root : $currentSmallest;
            foreach($root->children as $child){
                $childSmallest = $this->findSmallestFolderBiggerThanSize($child, $currentSmallest, $minSize);
                $currentSmallest = $childSmallest->getFolderSize() < $currentSmallest->getFolderSize() ? $childSmallest : $currentSmallest;
            }
        }
        return $currentSmallest;
    }

    private function createFolderStructure(): Folder
    {
        $root = new Folder(name: '/');
        $this->handle = fopen(self::DATA_PATH, 'r');
        fgets($this->handle); //skipping the first line;

        $currentFolder = $root;
        while (($line = fgets($this->handle)) !== false) {
            $line = explode(" ", trim($line));
            $command = $line[1];
            $destFolder = $line[2] ?? null;

            switch ($command) {
                case self::LS:
                    $folderContent = $this->listContent();
                    $this->addContentToFolder($folderContent, $currentFolder);
                    break;
                case self::CD:
                    $currentFolder = $this->goToFolder($destFolder, $currentFolder);
                    break;
            }
        }
        return $root;
    }

    private function getSmallFolders(Folder $root, &$smallFolders = []) : array
    {
        $size = $root->getFolderSize();
        if($size <= self::MAX_FOLDER_SIZE) $smallFolders[] = $root;
        foreach($root->children as $folder){
            $this->getSmallFolders($folder, $smallFolders);
        }
        return $smallFolders;
    }

    private function listContent(): array
    {
        $startOfLine = ftell($this->handle);
        $folders = [];
        $files = [];

        while (($line = fgets($this->handle)) !== false
            && strpos($line, '$') === false
        ) {
            $startOfLine = ftell($this->handle);
            $content = explode(" ", trim($line));
            if ($content[0] == self::FOLDER) {
                $folders[] = $content[1];
            } else {
                $files[$content[1]] = $content[0]; // Filename => size
            }
        }
        fseek($this->handle, $startOfLine);
        return [
            'folders' => $folders, 'files' => $files
        ];
    }

    private function addContentToFolder(array $folderContent, Folder $currentFolder)
    {
        $currentFolder->files = $folderContent['files'];

        $children = [];
        foreach ($folderContent['folders'] as $foldername) {
            $children[] = new Folder(name: $foldername, parent: $currentFolder);
        }
        $currentFolder->children = $children;
    }

    private function goToFolder(string $destFolderName, Folder $currentFolder): Folder
    {
        if($destFolderName == '..') return $currentFolder->parent;
        
        foreach ($currentFolder->children as $folder) {
            if ($folder->name == $destFolderName) return $folder;
        }
        throw new Exception("I shouldn't be here. Looking for folder $destFolderName in folder " . $currentFolder->name);
    }
}
