<?php

namespace Moehrenzahn\ScriptureKit\Parser;

use Moehrenzahn\ScriptureKit\Data\ScripturePiece;

interface ParserInterface
{
    public function loadVerseText(
        string $filePath,
        int $bookNumber,
        int $chapter,
        int $verse
    ): ScripturePiece;

    /**
     * @param string $filePath
     * @param int    $bookNumber
     * @param int    $chapter
     * @param array  $verses
     *
     * @return ScripturePiece[]
     */
    public function loadVersesText(
        string $filePath,
        int $bookNumber,
        int $chapter,
        array $verses
    ): array;

    /**
     * @param string $filePath
     * @param int    $bookNumber
     * @param int    $chapter
     *
     * @return ScripturePiece[]
     */
    public function loadChapterText(
        string $filePath,
        int $bookNumber,
        int $chapter
    ): array;

    /**
     * @param string $filePath
     * @param int|null $startBookNumber
     * @param int    $startChapter
     * @param int    $startVerse
     * @param int|null $endBookNumber
     * @param int    $endChapter
     * @param int    $endVerse
     *
     * @return ScripturePiece[]
     */
    public function loadVerseRange(
        string $filePath,
        ?int $startBookNumber,
        int $startChapter,
        int $startVerse,
        ?int $endBookNumber,
        int $endChapter,
        int $endVerse
    ): array;

    public function loadTitle(string $filePath): string;

    public function loadLanguageCode(string $filePath): string;

    /**
     * @param string $filePath
     *
     * @return string[]
     */
    public function loadVersionDetails(string $filePath): array;
}
