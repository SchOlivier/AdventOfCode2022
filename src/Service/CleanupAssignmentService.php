<?php

namespace App\Service;

use App\Entity\Assignment;

class CleanupAssignmentService
{

    const DATA_PATH = __DIR__ . '/../../assets/04-cleanupAssignments.txt';

    public function countFullyContainedAssignments(): int
    {
        $handle = fopen(self::DATA_PATH, 'r');
        $count = 0;

        while (($line = fgets($handle)) !== false) {
            $rawPair = trim($line);
            list($assignment1, $assignment2) = $this->getAssignmentsFromRawPair($rawPair);
            if (
                $this->isAssignmentFullyContainedInAnother($assignment1, $assignment2) ||
                $this->isAssignmentFullyContainedInAnother($assignment2, $assignment1)
            ) $count++;
        }

        return $count;
    }

    public function countOverlappingAssignments(): int
    {
        $handle = fopen(self::DATA_PATH, 'r');
        $count = 0;

        while (($line = fgets($handle)) !== false) {
            $rawPair = trim($line);
            list($assignment1, $assignment2) = $this->getAssignmentsFromRawPair($rawPair);
            if ($this->isAssignmentsOverlapping($assignment1, $assignment2)) $count++;
        }

        return $count;
    }

    private function isAssignmentsOverlapping(Assignment $assignment1, Assignment $assignment2):bool
    {
        return $assignment1->start <= $assignment2->end && $assignment1->end >= $assignment2->start;
    }

    private function isAssignmentFullyContainedInAnother(Assignment $needle, Assignment $hayStack): bool
    {
        return $hayStack->start <= $needle->start && $hayStack->end >= $needle->end;
    }

    private function getAssignmentsFromRawPair(string $rawPair): array
    {
        $stringAssignments = explode(",", trim($rawPair));
        $ranges1 = explode('-', $stringAssignments[0]);
        $ranges2 = explode('-', $stringAssignments[1]);

        return [
            new Assignment(
                start: (int)$ranges1[0],
                end: (int)$ranges1[1]
            ),
            new Assignment(
                start: (int)$ranges2[0],
                end: (int)$ranges2[1]
            ),
        ];
    }
}
