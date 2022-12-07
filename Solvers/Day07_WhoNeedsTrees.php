<?php

namespace App\Solvers;

use App\Entity\Folder;
use Exception;

class Day07_WhoNeedsTrees
{

    const DATA_PATH = __DIR__ . '/../assets/07-DirNavigation.txt';
    const MAX_FOLDER_SIZE = 100000;
    const DISK_SPACE = 70000000;
    const NEEDED_SPACE = 30000000;

    public function getTotalSizeOfSmallFolders(): int
    {
        $files = $this->listAllFiles();
        $folders = $this->initializeFoldersFromFiles($files);
        $this->calculateFoldersSizes($folders, $files);

        // Filter by size.
        $totalSize = 0;
        foreach ($folders as $path => $size) {
            if ($size <= self::MAX_FOLDER_SIZE) $totalSize += $size;
        }

        return $totalSize;
    }

    public function getSizeOfFolderToDelete(): int
    {
        $files = $this->listAllFiles();
        $folders = $this->initializeFoldersFromFiles($files);
        $this->calculateFoldersSizes($folders, $files);

        $usedSpace = $folders['/'];
        $freeSpace = self::DISK_SPACE - $usedSpace;
        $spaceToDelete = self::NEEDED_SPACE - $freeSpace;
        $smallest = $folders['/'];

        foreach ($folders as $path => $size) {
            if ($size >= $spaceToDelete && $size < $smallest) $smallest = $size;
        }
        return $smallest;
    }

    private function initializeFoldersFromFiles(array $files): array
    {
        $folders = [];

        //Initialize folders list
        foreach ($files as $path => $value) {
            $dir = substr($path, 0, strrpos($path, "/") + 1);
            $folders[$dir] = 0;
        }
        return $folders;
    }

    private function calculateFoldersSizes(array &$folders, array $files): void
    {
        //Calculate size of folders
        foreach ($folders as $folderPath => $size) {
            foreach ($files as $filePath => $size) {
                if (strpos($filePath, $folderPath) !== false) {
                    $folders[$folderPath] += $size;
                }
            }
        }
    }

    private function listAllFiles(): array
    {
        $handle = fopen(self::DATA_PATH, 'r');
        fgets($handle);
        $files = [];
        $currentDir = '/';
        $files[$currentDir] = 0;
        $regex = "/(\d+) (.*)/";
        while (($line = fgets($handle)) !== false) {
            $line = trim($line);
            if (substr($line, 0, 5) == '$ cd ') {
                $destDir = substr($line, 5);
                if ($destDir == '..') {
                    $currentDir = substr($currentDir, 0, strrpos($currentDir, "/", -2) + 1);
                } else {
                    $currentDir .= $destDir . '/';
                    $files[$currentDir] = 0;
                }
            } elseif (preg_match($regex, $line, $matches) === 1) {
                $size = $matches[1];
                $name = $matches[2];
                $files[$currentDir . $name] = $size;
            }
        }
        return $files;
    }
}
