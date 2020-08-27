<?php

namespace Moehrenzahn\ScriptureKit\Data;

use JsonSerializable;

/**
 * Contains information needed to render a verse.
 *
 * Use \Moehrenzahn\ScriptureKit\VerseRequestBuilder to create
 *
 */
class VerseRequest implements JsonSerializable
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
    private $startChapter;

    /**
     * @var int
     */
    private $endChapter;

    /**
     * @var int
     */
    private $collection;

    /**
     * @var ?int
     */
    private $startVerse;

    /**
     * @var ?int
     */
    private $endVerse;

    /**
     * @var bool
     */
    private $showAnnotations;

    /**
     * @var bool
     */
    private $showHeadings;

    /**
     * @var bool
     */
    private $inferLinebreaks;

    /**
     * @var VersePosition[]
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
     * @param VersePosition[]    $highlightedVerses
     * @param string[] $tanachBookNames
     * @param string[] $bibleBookNames
     * @param string[] $quranChapterNames
     */
    public function __construct(
        ?int $bookNumber,
        int $startChapter,
        int $endChapter,
        int $collection,
        ?int $startVerse,
        ?int $endVerse,
        bool $showAnnotations,
        bool $showHeadings,
        bool $inferLinebreaks,
        array $highlightedVerses,
        bool $returnHtml,
        array $tanachBookNames,
        array $bibleBookNames,
        array $quranChapterNames,
        string $chapterVerseSeparator
    ) {
        $this->bookNumber = $bookNumber;
        $this->startChapter = $startChapter;
        $this->endChapter = $endChapter;
        $this->collection = $collection;
        $this->startVerse = $startVerse;
        $this->endVerse = $endVerse;
        $this->showAnnotations = $showAnnotations;
        $this->showHeadings = $showHeadings;
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
     */
    public function getBookNumber(): ?int
    {
        return $this->bookNumber;
    }

    /**
     * @deprecated
     */
    public function getChapter(): int
    {
        return $this->startChapter;
    }

    /**
     *
     * @return int[]
     */
    public function getChapters(): array
    {
        return range($this->startChapter, $this->endChapter);
    }

    public function getStartChapter(): int
    {
        return $this->startChapter;
    }

    public function getEndChapter(): int
    {
        return $this->endChapter;
    }

    public function getStartVerse(): ?int
    {
        return $this->startVerse;
    }

    public function getEndVerse(): ?int
    {
        return $this->endVerse;
    }

    public function getCollection(): int
    {
        return $this->collection;
    }

    public function isShowAnnotations(): bool
    {
        return $this->showAnnotations;
    }

    public function isShowHeadings(): bool
    {
        return $this->showHeadings;
    }

    public function isInferLinebreaks(): bool
    {
        return $this->inferLinebreaks;
    }

    /**
     * @return VersePosition[]
     */
    public function getHighlightedVerses(): array
    {
        return $this->highlightedVerses;
    }

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

    public function getChapterVerseSeparator(): string
    {
        return $this->chapterVerseSeparator;
    }

    public function jsonSerialize(): array
    {
        return [
            'bookNumber' => $this->getBookNumber(),
            'startChapter' => $this->getStartChapter(),
            'endChapter' => $this->getEndChapter(),
            'collection' => $this->getCollection(),
            'startVerse' => $this->getstartVerse(),
            'endVerse' => $this->getendVerse(),
            'showAnnotations' => $this->isShowAnnotations(),
            'showHeadings' => $this->isShowHeadings(),
            'inferLinebreaks' => $this->isInferLinebreaks(),
            'highlightedVerses' => $this->getHighlightedVerses(),
            'returnHtml' => $this->isReturnHtml(),
            'tanachBookNames' => (object) $this->getTanachBookNames(),
            'bibleBookNames' => (object) $this->getBibleBookNames(),
            'quranChapterNames' => (object) $this->getQuranChapterNames(),
            'chapterVerseSeparator' => $this->getChapterVerseSeparator(),
        ];
    }
}
