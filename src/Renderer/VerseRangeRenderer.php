<?php

namespace Moehrenzahn\ScriptureKit\Renderer;

/**
 * Formats an array of verse numbers into a string with
 * verse hyphenated verse groups separated by periods.
 */
class VerseRangeRenderer
{
    /**
     * @param int[] $verseNumbers
     * @return string
     */
    public function render(array $verseNumbers): string
    {
        sort($verseNumbers);
        $last = -1;
        $ranges = [];
        $currentRange = [];
        while (!empty($verseNumbers)) {
            $current = (int) array_shift($verseNumbers);
            if ($this->isAdjacent($last, $current)) {
                $currentRange[] = $current;
            } else {
                $ranges[] = $currentRange;
                $currentRange = [$current];
            }
            $last = $current;
        }
        $ranges[] = $currentRange;

        return $this->rangesToString(array_filter($ranges));
    }

    /**
     * @param int $a
     * @param int $b
     * @return bool
     */
    private function isAdjacent($a, $b): bool
    {
        return (($a + 1) === $b);
    }

    /**
     * @param array $ranges
     * @return string
     */
    private function rangesToString(array $ranges): string
    {
        $rangeStrings = [];
        foreach ($ranges as $range) {
            $rangeStrings[] = $this->rangeToString($range);
        }

        return implode('.', $rangeStrings);
    }

    /**
     * @param int[] $range
     * @return string
     */
    private function rangeToString(array $range): string
    {
        $rangeString = array_shift($range);
        if (!empty($range)) {
            $rangeString .= '–' . array_pop($range);
        }

        return (string) $rangeString;
    }
}
