<?php

namespace Moehrenzahn\ScriptureKit\Data;

/**
 * Contains information needed to render a verse.
 *
 * Use \Moehrenzahn\ScriptureKit\VerseRequestBuilder to create
 *
 */
class VerseRequest
{
    const COLLECTION_TANAKH = 0;
    const COLLECTION_OT = 1;
    const COLLECTION_NT = 2;
    const COLLECTION_QURAN = 3;

    /**
     * @var int|null
     */
    private $bookNumber;

    /**
     * @var int
     */
    private $chapter;

    /**
     * @var int
     */
    private $collection;

    /**
     * @var int[]
     */
    private $verses = [];

    /**
     * @var bool
     */
    private $showAnnotations = true;

    /**
     * @var bool
     */
    private $inferLinebreaks = true;

    /**
     * @var int[]
     */
    private $highlightedVerses = [];

    /**
     * @var bool
     */
    private $returnHtml = false;

    /**
     * VerseRequest constructor.
     *
     * @param int|null $bookNumber
     * @param int      $chapter
     * @param int      $collection
     * @param int[]    $verses
     * @param bool     $showAnnotations
     * @param bool     $inferLinebreaks
     * @param int[]    $highlightedVerses
     * @param bool     $returnHtml
     */
    public function __construct(
        ?int $bookNumber,
        int $chapter,
        int $collection,
        array $verses,
        bool $showAnnotations,
        bool $inferLinebreaks,
        array $highlightedVerses,
        bool $returnHtml
    ) {
        $this->bookNumber = $bookNumber;
        $this->chapter = $chapter;
        $this->collection = $collection;
        $this->verses = $verses;
        $this->showAnnotations = $showAnnotations;
        $this->inferLinebreaks = $inferLinebreaks;
        $this->highlightedVerses = $highlightedVerses;
        $this->returnHtml = $returnHtml;
    }

    /**
     * Book number is not applicable for quran verses
     *
     * @return int|null
     */
    public function getBookNumber(): ?int
    {
        return $this->bookNumber;
    }

    /**
     * @return int
     */
    public function getChapter(): int
    {
        return $this->chapter;
    }

    /**
     * @return int
     */
    public function getCollection(): int
    {
        return $this->collection;
    }

    /**
     * @return int[]
     */
    public function getVerses(): array
    {
        return $this->verses;
    }

    /**
     * @return bool
     */
    public function isShowAnnotations(): bool
    {
        return $this->showAnnotations;
    }

    /**
     * @return bool
     */
    public function isInferLinebreaks(): bool
    {
        return $this->inferLinebreaks;
    }

    /**
     * @return int[]
     */
    public function getHighlightedVerses(): array
    {
        return $this->highlightedVerses;
    }

    /**
     * @return bool
     */
    public function isReturnHtml(): bool
    {
        return $this->returnHtml;
    }
}
