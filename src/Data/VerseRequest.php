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
    private $verses;

    /**
     * @var bool
     */
    private $showAnnotations;

    /**
     * @var bool
     */
    private $inferLinebreaks;

    /**
     * @var int[]
     */
    private $highlightedVerses;

    /**
     * @var bool
     */
    private $returnHtml;

    /**
     * @var string[]
     */
    private $tanachBookNames;

    /**
     * @var string[]
     */
    private $bibleBookNames;

    /**
     * @var string[]
     */
    private $quranChapterNames;

    /**
     * @var string
     */
    private $chapterVerseSeparator;

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
     * @param string[] $tanachBookNames
     * @param string[] $bibleBookNames
     * @param string[] $quranChapterNames
     * @param string   $chapterVerseSeparator
     */
    public function __construct(
        ?int $bookNumber,
        int $chapter,
        int $collection,
        array $verses,
        bool $showAnnotations,
        bool $inferLinebreaks,
        array $highlightedVerses,
        bool $returnHtml,
        array $tanachBookNames,
        array $bibleBookNames,
        array $quranChapterNames,
        string $chapterVerseSeparator
    ) {
        $this->bookNumber = $bookNumber;
        $this->chapter = $chapter;
        $this->collection = $collection;
        $this->verses = $verses;
        $this->showAnnotations = $showAnnotations;
        $this->inferLinebreaks = $inferLinebreaks;
        $this->highlightedVerses = $highlightedVerses;
        $this->returnHtml = $returnHtml;
        $this->tanachBookNames = $tanachBookNames;
        $this->bibleBookNames = $bibleBookNames;
        $this->quranChapterNames = $quranChapterNames;
        $this->chapterVerseSeparator = $chapterVerseSeparator;
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

    /**
     * @return string[]
     */
    public function getTanachBookNames(): array
    {
        return $this->tanachBookNames;
    }

    /**
     * @return string[]
     */
    public function getBibleBookNames(): array
    {
        return $this->bibleBookNames;
    }

    /**
     * @return string[]
     */
    public function getQuranChapterNames(): array
    {
        return $this->quranChapterNames;
    }

    /**
     * @return string
     */
    public function getChapterVerseSeparator(): string
    {
        return $this->chapterVerseSeparator;
    }
}
