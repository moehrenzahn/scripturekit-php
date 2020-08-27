<?php

namespace Moehrenzahn\ScriptureKit\Data;

use JsonSerializable;

/**
 * Class VersePosition
 *
 * Represents the position of a specific verse. Used for example to identify verse to highlight.
 */
class VersePosition implements JsonSerializable
{
    /**
     * @var ?int
     */
    private $book;

    /**
     * @var int
     */
    private $chapter;

    /**
     * @var int
     */
    private $verse;

    public function __construct(?int $book, int $chapter, int $verse)
    {
        $this->book = $book;
        $this->chapter = $chapter;
        $this->verse = $verse;
    }

    public function getBook(): ?int
    {
        return $this->book;
    }

    public function getChapter(): int
    {
        return $this->chapter;
    }

    public function getVerse(): int
    {
        return $this->verse;
    }

    public function jsonSerialize(): array
    {
        return [
            'book' => $this->getBook(),
            'chapter' => $this->getChapter(),
            'verse' => $this->getVerse(),
        ];
    }
}
